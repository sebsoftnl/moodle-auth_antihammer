<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Privacy provider.
 *
 * File         provider.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;

/**
 * Privacy provider.
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\core_userlist_provider {

    /**
     * Provides meta data that is stored about a user with auth_antihammer
     *
     * @param  collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table(
            'auth_antihammer',
            [
                'userid' => 'privacy:metadata:auth_antihammer:userid',
                'username' => 'privacy:metadata:auth_antihammer:username',
                'ip' => 'privacy:metadata:auth_antihammer:ip',
                'blocked' => 'privacy:metadata:auth_antihammer:blocked',
                'firstattempt' => 'privacy:metadata:auth_antihammer:firstattempt',
                'blocktime' => 'privacy:metadata:auth_antihammer:blocktime',
            ],
            'privacy:metadata:auth_antihammer'
        );
        $collection->add_database_table(
            'auth_antihammer_log',
            [
                'userid' => 'privacy:metadata:auth_antihammer_log:userid',
                'data' => 'privacy:metadata:auth_antihammer_log:data',
                'datecreated' => 'privacy:metadata:auth_antihammer_log:datecreated',
            ],
            'privacy:metadata:auth_antihammer_log'
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int           $userid       The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new \core_privacy\local\request\contextlist();
        // Since this system works on a global level (it hooks into the authentication system), the only context is CONTEXT_SYSTEM.
        $contextlist->add_system_context();
        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts, using the supplied exporter instance.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        if (empty($contextlist->count())) {
            return;
        }
        $user = $contextlist->get_user();
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            $contextid = $context->id;

            // Add hammering records.
            $sql = "SELECT ah.*
                      FROM {auth_antihammer} ah
                      WHERE ah.userid = :userid OR ah.username = :username";
            $params = ['userid' => $user->id, 'username' => $user->username];
            $alldata = [];
            $antihammers = $DB->get_recordset_sql($sql, $params);
            foreach ($antihammers as $antihammer) {
                $alldata[$contextid][] = (object)[
                        'userid' => $antihammer->userid,
                        'username' => $antihammer->username,
                        'ip' => $antihammer->ip,
                        'blocked' => $antihammer->blocked,
                        'firstattempt' => transform::datetime($antihammer->firstattempt),
                        'firstattempt' => transform::datetime($antihammer->firstattempt),
                    ];
            }
            $antihammers->close();

            // The data is organised in: {? }/hammering.json.
            // where X is the attempt number.
            array_walk($alldata, function($hammeringdata, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['auth_antihammer'],
                    'hammering',
                    (object)['hammering' => $hammeringdata]
                );
            });

            // Add hammering log records.
            $sql = "SELECT ahl.*
                      FROM {auth_antihammer_log} ahl
                      WHERE ahl.userid = :userid";
            $params = ['userid' => $user->id];
            $alldata = [];
            $antihammerlogs = $DB->get_recordset_sql($sql, $params);
            foreach ($antihammerlogs as $antihammerlog) {
                $alldata[$contextid][] = (object)[
                        'userid' => $antihammerlog->userid,
                        'data' => $antihammerlog->data,
                        'timecreated' => transform::datetime($antihammerlog->timecreated),
                        'timemodified' => transform::datetime($antihammerlog->timemodified),
                    ];
            }
            $antihammerlogs->close();

            // The data is organised in: {?}/hammerlogs.json.
            // where X is the attempt number.
            array_walk($alldata, function($antihammerlog, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['auth_antihammer'],
                    'hammerlogs',
                    (object)['hammerlog' => $antihammerlog]
                );
            });
        }
    }

    /**
     * Delete all use data which matches the specified context.
     *
     * @param context $context The module context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }

        // Delete hammering records.
        $DB->delete_records('auth_antihammer');
        // Delete log records.
        $DB->delete_records('auth_antihammer_log');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            $user = $contextlist->get_user();
            // Delete hammering records.
            $DB->delete_records('auth_antihammer', ['userid' => $user->id]);
            $DB->delete_records('auth_antihammer', ['username' => $user->username]);
            // Delete log records.
            $DB->delete_records('auth_antihammer_log', ['userid' => $user->id]);
        }
    }
    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }
        // Since we work on a global level, this means "all context".
        $userids1 = $DB->get_fieldset_sql('SELECT DISTINCT userid FROM {auth_antihammer}');
        $userids2 = $DB->get_fieldset_sql('SELECT DISTINCT userid FROM {auth_antihammer_log}');
        $userids = array_unique(array_merge($userids1, $userids2));
        $userlist->add_users($userids);
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param  approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }

        foreach ($userlist->get_userids() as $userid) {
            $DB->delete_records('auth_antihammer', ['userid' => $userid]);
            $DB->delete_records('auth_antihammer_log', ['userid' => $userid]);
        }
    }

}
