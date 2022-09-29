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
 * this file contains the repeat offenders table class for displaying data overviews.
 *
 * File         rotable.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

/**
 * auth_antihammer\rotable
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rotable extends \table_sql {
    /**
     * Localised string for 'delete item' message
     *
     * @var string
     */
    protected $strdelete;
    /**
     * Localised string to indicate IP lookup on whatismyipaddress.com
     *
     * @var string
     */
    protected $strwhatismyip;
    /**
     * Localised string to indicate IP lookup from Moodle's iplookup page
     *
     * @var string
     */
    protected $striplookup;
    /**
     * Localised string to indicate user type blocking
     *
     * @var string
     */
    protected $strtypeuser;
    /**
     * Localised string to indicate log type 'info'
     *
     * @var string
     */
    protected $strtypeinfo;
    /**
     * Localised string to indicate adding to whitelist
     *
     * @var string
     */
    protected $strwhitelist;
    /**
     * Localised string to indicate YES
     *
     * @var string
     */
    protected $stryes;
    /**
     * Localised string to indicate NO
     *
     * @var string
     */
    protected $strno;
    /**
     * Plugin configuration
     *
     * @var stdClass
     */
    protected $config;

    /**
     * Create a new instance of the table
     */
    public function __construct() {
        global $USER;
        parent::__construct(__CLASS__. $USER->id);
        // Load localised strings to save CPU for lookups.
        $this->strdelete = get_string('action:delete:roitem', 'auth_antihammer');
        $this->strwhatismyip = get_string('lookup:whatismyip', 'auth_antihammer');
        $this->striplookup = get_string('lookup:iplookup', 'auth_antihammer');
        $this->strwhitelist = get_string('ip:whitelist', 'auth_antihammer');
        $this->stryes = get_string('yes');
        $this->strno = get_string('no');
        $this->no_sorting('nextduration');
        $this->config = get_config('auth_antihammer');
        $this->config->ipwhitelist = isset($this->config->ipwhitelist) ?
                array_unique(array_map('trim', explode("\n", $this->config->ipwhitelist))) : [];
    }

    /**
     *
     * Set the sql to query the db.
     * This method is disabled for this class, since we use internal queries
     *
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param array $params
     * @throws exception
     */
    public function set_sql($fields, $from, $where, array $params = []) {
        // We'll disable this method.
        throw new exception('err:sqltable:set_sql');
    }

    /**
     * Display the general hammer status table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render($pagesize, $useinitialsbar = true) {
        $this->define_columns(array('action', 'ip', 'counter', 'blockcounter', 'nextduration', 'whitelisted'));
        $this->define_headers(array(
            get_string('thead:action', 'auth_antihammer'),
            get_string('thead:ip', 'auth_antihammer'),
            get_string('thead:count', 'auth_antihammer'),
            get_string('thead:blockcounter', 'auth_antihammer'),
            get_string('thead:nextblockduration', 'auth_antihammer'),
            get_string('thead:whitelisted', 'auth_antihammer'),
                ));
        $fields = 'ro.*,NULL AS action';
        $where = '1 = 1';
        $params = array();
        parent::set_sql($fields, '{auth_antihammer_ro} ro', $where, $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Take the data returned from the db_query and go through all the rows
     * processing each col using either col_{columnname} method or other_cols
     * method or if other_cols returns NULL then put the data straight into the
     * table.
     */
    public function build_table() {
        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {
                $formattedrow = $this->format_row($row);
                $this->add_data_keyed($formattedrow, $this->get_row_class($row));
            }
        }
    }

    /**
     * Render visual representation of the 'ip' column for use in the table
     *
     * @param \stdClass $row
     * @return string type string
     */
    public function col_ip($row) {
        global $CFG, $OUTPUT;
        $actions = [];
        $actions[] = $OUTPUT->action_icon(
                new \moodle_url($CFG->wwwroot . '/iplookup/index.php', ['ip' => $row->ip]),
                new \pix_icon('i/location', $this->striplookup),
                null,
                ['alt' => $this->striplookup, 'target' => '_new']);
        $actions[] = $OUTPUT->action_icon(
                new \moodle_url('https://whatismyipaddress.com/ip/'.$row->ip),
                new \pix_icon('i/publish', $this->strwhatismyip),
                null,
                ['alt' => $this->strwhatismyip, 'target' => '_new']);
        if (!in_array($row->ip, $this->config->ipwhitelist)) {
            $actions[] = $OUTPUT->action_icon(
                    new \moodle_url($this->baseurl, ['action' => 'whitelist',
                            'ip' => base64_encode($row->ip), 'sesskey' => sesskey()]),
                    new \pix_icon('t/check', $this->strwhitelist),
                    null,
                    ['alt' => $this->strwhitelist]);
        }

        return $row->ip . implode('', $actions);
    }

    /**
     * Render visual representation of the 'whitelisted' column for use in the table
     *
     * @param \stdClass $row
     * @return string type string
     */
    public function col_whitelisted($row) {
        if (in_array($row->ip, $this->config->ipwhitelist)) {
            return $this->stryes;
        } else {
            return $this->strno;
        }
    }

    /**
     * Render visual representation of the 'nextblockduration' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_nextduration($row) {
        $instance = repeatoffender::create_from_object($row);
        return format_time($instance->get_block_duration($this->config->ip_attemptcounter));
    }

    /**
     * Render visual representation of the 'blocktime' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_blocktime($row) {
        return date('Y-m-d H:i:s', $row->blocktime);
    }

    /**
     * Render visual representation of the 'datecreated' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_datecreated($row) {
        return date('Y-m-d H:i:s', $row->datecreated);
    }

    /**
     * Get any extra classes names to add to this row in the HTML.
     * @param \stdClass $row the data for this row.
     * @return string added to the class="" attribute of the tr.
     */
    public function get_row_class($row) {
        return '';
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        global $OUTPUT;
        $actions = [];
        $actions[] = $OUTPUT->action_icon(
                new \moodle_url($this->baseurl, ['action' => 'delete', 'id' => $row->id, 'sesskey' => sesskey()]),
                new \pix_icon('i/delete', $this->strdelete),
                null,
                ['alt' => $this->strdelete]);

        return implode('', $actions);
    }
}
