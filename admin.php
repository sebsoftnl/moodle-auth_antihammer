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
 * Admin pages
 *
 * File         admin.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../config.php");
require_once($CFG->libdir . "/adminlib.php");

$page = required_param('page', PARAM_ALPHA);
$action = optional_param('action', 'list', PARAM_ALPHAEXT);

admin_externalpage_setup($page);
require_capability('auth/antihammer:administration', context_system::instance());

$pageparams = array('page' => $page, 'action' => $action);
$pageurl = new moodle_url('/auth/antihammer/admin.php', $pageparams);
$PAGE->set_url($pageurl);
$PAGE->set_heading($SITE->fullname);

$renderer = $PAGE->get_renderer('auth_antihammer');

auth_antihammer\util::check_notifications();

switch($page) {
    case 'aplog':
        switch ($action) {
            case 'delete':
                require_capability('auth/antihammer:delete', context_system::instance());
                require_sesskey();
                $id = required_param('id', PARAM_INT);
                $DB->delete_records('auth_antihammer_log', array('id' => $id));
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'details':
                $id = required_param('id', PARAM_INT);
                echo $renderer->admin_page_logs_details($id);
                break;

            case 'list':
            default:
                $PAGE->set_title(get_string('title:report:logs', 'auth_antihammer'));
                echo $renderer->admin_page_logs_overview();
                break;
        }
        break;

    case 'apreport':
        switch ($action) {
            case 'delete':
                require_capability('auth/antihammer:delete', context_system::instance());
                require_sesskey();
                $id = required_param('id', PARAM_INT);
                $DB->delete_records('auth_antihammer', array('id' => $id));
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'deleteall':
                require_capability('auth/antihammer:delete', context_system::instance());
                require_sesskey();
                $DB->delete_records('auth_antihammer');
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'deleteipblock':
                require_capability('auth/antihammer:delete', context_system::instance());
                require_sesskey();
                $ip = required_param('ip', PARAM_BASE64);
                $DB->delete_records('auth_antihammer', ['ip' => base64_decode($ip)]);
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'whitelist':
                require_capability('auth/antihammer:administration', context_system::instance());
                require_sesskey();
                $ip = required_param('ip', PARAM_BASE64);
                auth_antihammer\util::add_to_whitelist(base64_decode($ip));
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'list':
            default:
                $PAGE->set_title(get_string('title:report:hammer', 'auth_antihammer'));
                echo $renderer->admin_page_report_overview();
                break;
        }
        break;

    case 'roreport':
        switch ($action) {
            case 'delete':
                require_capability('auth/antihammer:delete', context_system::instance());
                require_sesskey();
                $id = required_param('id', PARAM_INT);
                $DB->delete_records('auth_antihammer_ro', array('id' => $id));
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'whitelist':
                require_capability('auth/antihammer:administration', context_system::instance());
                require_sesskey();
                $ip = required_param('ip', PARAM_BASE64);
                auth_antihammer\util::add_to_whitelist(base64_decode($ip));
                redirect(new moodle_url('/auth/antihammer/admin.php', array('page' => $page)));
                break;

            case 'list':
            default:
                $PAGE->set_title(get_string('title:report:repeatoffenders', 'auth_antihammer'));
                echo $renderer->admin_page_report_repeatoffenders();
                break;
        }
        break;

    default:
        break;
}
