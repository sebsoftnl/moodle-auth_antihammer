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
 * messaging helper
 *
 * File         messaging.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer;

/**
 * auth_antihammer\messaging
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class messaging {

    /**
     * send out messages about blocked IPs and/or accounts
     *
     * @param \stdClass $a
     * @param \stdClass $config
     */
    public static function message_notifyblocking($a, $config = null) {
        if (empty($config)) {
            $config = get_config('auth_antihammer');
        }
        // Get support user.
        if (method_exists('core_user', 'get_support_user')) {
            $supportuser = \core_user::get_support_user();
        } else {
            $supportuser = generate_email_supportuser();
        }
        // Process using messaging?
        if (!empty($config->usemessaging)) {
            $contexturl = new \moodle_url('/auth/antihammer/admin.php', array('page' => 'apreport'));
            $admins = get_admins();
            foreach ($admins as $admin) {
                // Set recipient's name.
                $a->firstname = $admin->firstname;
                $a->lastname = $admin->lastname;
                // Always use proper preferred language for each user :).
                $subject = get_string_manager()->get_string('mail:blocking:subject', 'auth_antihammer', null, $admin->lang);
                $messagehtml = get_string_manager()->get_string('mail:blocking:message', 'auth_antihammer', $a, $admin->lang);
                $messagetext = format_text_email($messagehtml, FORMAT_HTML);

                if (class_exists('\core\message\message')) {
                    $message = new \core\message\message();
                    $message->component = 'auth_antihammer';
                    $message->name = 'antihammerblocking';
                    $message->userfrom = $supportuser;
                    $message->userto = $admin;
                    $message->subject = $subject;
                    $message->fullmessage = $messagetext;
                    $message->fullmessageformat = FORMAT_PLAIN;
                    $message->fullmessagehtml = $messagehtml;
                    $message->smallmessage = $messagetext;
                    $message->notification = 0;
                    $message->contexturl = $contexturl->out();
                    $message->contexturlname = get_string('ap:report', 'auth_antihammer');
                    $message->courseid = SITEID;
                } else {
                    $message = new \stdClass();
                    $message->component = 'auth_antihammer';
                    $message->name = 'antihammerblocking';
                    $message->userfrom = $supportuser;
                    $message->userto = $admin;
                    $message->subject = $subject;
                    $message->fullmessage = $messagetext;
                    $message->fullmessageformat = FORMAT_PLAIN;
                    $message->fullmessagehtml = $messagehtml;
                    $message->smallmessage = $messagetext;
                    $message->notification = 0;
                    $message->contexturl = $contexturl->out();
                    $message->contexturlname = get_string('ap:report', 'auth_antihammer');
                }
                message_send($message);
            }
        }
        // Notify main admin?
        if (!empty($config->notifymainadmin)) {
            $admins = get_admins();
            $mainadmin = reset($admins);
            if (!empty($mainadmin)) {
                // Set recipient's name.
                $a->firstname = $mainadmin->firstname;
                $a->lastname = $mainadmin->lastname;
                // Always use proper preferred language for user :).
                $subject = get_string_manager()->get_string('mail:blocking:subject', 'auth_antihammer', null, $mainadmin->lang);
                $messagehtml = get_string_manager()->get_string('mail:blocking:message', 'auth_antihammer', $a, $mainadmin->lang);
                $messagetext = format_text_email($messagehtml, FORMAT_HTML);
                email_to_user($mainadmin, $supportuser, $subject, $messagetext, $messagehtml);
            }

        }
    }

}
