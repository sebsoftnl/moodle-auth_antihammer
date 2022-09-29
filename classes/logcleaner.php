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
 * Logcleaner task implementation for auth antihammer
 *
 * File         logcleaner.php
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
 * auth_antihammer\logcleaner
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logcleaner extends \core\task\scheduled_task {

    /**
     * Return the localised name for this task
     *
     * @return string task name
     */
    public function get_name() {
        return get_string('task:logcleaner', 'auth_antihammer');
    }

    /**
     * Executes the task
     *
     * @return void
     */
    public function execute() {
        global $DB;
        if (!(bool)config::get('autocleanlog')) {
            mtrace(get_string('config:autocleanlog:disabled', 'auth_antihammer'));
            return;
        }
        $params = array(time() - (int)config::get('autocleanlog_after'));
        $DB->delete_records_select('auth_antihammer_log', 'datecreated < ?', $params);
    }

}
