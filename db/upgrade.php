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
 * Plugin upgrade steps are defined here.
 *
 * File         upgrade.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 * @category    upgrade
 *
 * @author      2020 R.J. van Dongen
 * @copyright   2020 R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute auth_antihammer upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_auth_antihammer_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020121600) {
        // Add repeat offenders table.
        $table = new xmldb_table('auth_antihammer_ro');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ip', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'id');
        $table->add_field('counter', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'ip');
        $table->add_field('blockcounter', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'counter');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, null, null, 'blockcounter');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'timecreated');
        // Add KEYS.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2020121600, 'auth', 'antihammer');
    }

    return true;
}
