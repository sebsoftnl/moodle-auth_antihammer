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
 * this file contains the main table class for displaying data overviews.
 *
 * File         table.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * auth_antihammer\table
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class table extends \table_sql {

    /**
     * table type identifier for hammering stats
     */
    const HAMMER = 'hammer';
    /**
     * table type identifier for logs
     */
    const LOG = 'log';

    /**
     * internal display type
     *
     * @var string
     */
    protected $displaytype;
    /**
     * Localised string for 'delete log item' message
     *
     * @var string
     */
    protected $strdeletelog;
    /**
     * Localised string for 'view log item details' message
     *
     * @var string
     */
    protected $strdetailslog;
    /**
     * Localised string for 'delete hammering item' message
     *
     * @var string
     */
    protected $strdeletehammer;
    /**
     * Localised string to indicate IP type blocking
     *
     * @var string
     */
    protected $strtypeip;
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
     * Create a new instance of the table
     *
     * @param string $type table type. See defined constant or get_viewtypes() for valid values
     * @see get_viewtypes
     */
    public function __construct($type = 'log') {
        global $USER;
        parent::__construct(__CLASS__. $USER->id);
        $this->displaytype = $type;
        // Load localised strings to save CPU for lookups.
        $this->strdeletelog = get_string('action:delete:logitem', 'auth_antihammer');
        $this->strdeletehammer = get_string('action:delete:hammeritem', 'auth_antihammer');
        $this->strdetailslog = get_string('action:view:logitem', 'auth_antihammer');
        $this->strtypeip = get_string('type:ip', 'auth_antihammer');
        $this->strtypeuser = get_string('type:user', 'auth_antihammer');
        $this->strtypeinfo = get_string('type:info', 'auth_antihammer');
        $this->stryes = get_string('yes');
        $this->strno = get_string('no');
    }

    /**
     * Return a list of applicable viewtypes for this table
     *
     * @return array list of view types
     */
    static public function get_viewtypes() {
        return array(
            self::HAMMER,
            self::LOG,
        );
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
    public function set_sql($fields, $from, $where, array $params = null) {
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
        switch ($this->displaytype) {
            case self::HAMMER:
                $this->render_hammering($pagesize, $useinitialsbar);
                break;
            case self::LOG:
            default:
                $this->render_logs($pagesize, $useinitialsbar);
                break;
        }
    }

    /**
     * Display the general hammer status table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_hammering($pagesize, $useinitialsbar = true) {
        $this->define_columns(array('action', 'type', 'username', 'ip', 'count', 'firstattempt', 'blocked', 'blocktime'));
        $this->define_headers(array(
            get_string('thead:action', 'auth_antihammer'),
            get_string('thead:type', 'auth_antihammer'),
            get_string('thead:username', 'auth_antihammer'),
            get_string('thead:ip', 'auth_antihammer'),
            get_string('thead:count', 'auth_antihammer'),
            get_string('thead:firstattempt', 'auth_antihammer'),
            get_string('thead:blocked', 'auth_antihammer'),
            get_string('thead:blocktime', 'auth_antihammer'),
                ));
        $fields = 'h.*,NULL AS action';
        $where = '1 = 1';
        $params = null;
        parent::set_sql($fields, '{auth_antihammer} h', $where, $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Display the general log table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_logs($pagesize, $useinitialsbar = true) {
        $this->define_columns(array('action', 'userid', 'module', 'type', 'msg', 'code', 'datecreated'));
        $this->define_headers(array(
            get_string('thead:action', 'auth_antihammer'),
            get_string('thead:userid', 'auth_antihammer'),
            get_string('thead:module', 'auth_antihammer'),
            get_string('thead:type', 'auth_antihammer'),
            get_string('thead:msg', 'auth_antihammer'),
            get_string('thead:code', 'auth_antihammer'),
            get_string('thead:datecreated', 'auth_antihammer'),
                ));
        $fields = 'l.*,NULL AS action';
        $where = '1 = 1';
        $params = null;
        parent::set_sql($fields, '{auth_antihammer_log} l', $where, $params);
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
     * Render visual representation of the 'type' column for use in the table
     *
     * @param \stdClass $row
     * @return string type string
     */
    public function col_type($row) {
        $identifier = 'strtype' . $row->type;
        return $this->$identifier;
    }

    /**
     * Render visual representation of the 'blocked' column for use in the table
     *
     * @param \stdClass $row
     * @return string blocked string
     */
    public function col_blocked($row) {
        if ($row->blocked) {
            return $this->stryes;
        } else {
            return $this->strno;
        }
    }

    /**
     * Render visual representation of the 'firstattempt' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_firstattempt($row) {
        return date('Y-m-d H:i:s', $row->firstattempt);
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
        if ($this->displaytype === self::HAMMER && !(bool)$row->blocked) {
            return 'dimmed_text';
        }
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        $actions = array();
        $actions[] = $this->get_action($row, 'delete', true);
        switch ($this->displaytype) {
            case self::LOG:
                $actions[] = $this->get_action($row, 'details');
                break;
            default:
                break;
        }
        return implode(' ', $actions);
    }

    /**
     * Return the image tag representing an action image
     *
     * @param string $action
     * @return string HTML image tag
     */
    protected function get_action_image($action) {
        global $OUTPUT;
        $actionstr = 'str' . $action . $this->displaytype;
        return '<img src="' . $OUTPUT->pix_url($action, 'auth_antihammer') .
                '" title="' . $this->{$actionstr} . '"/>';
    }

    /**
     * Return a string containing the link to an action
     *
     * @param \stdClass $row
     * @param string $action
     * @param bool $confirm whether or not to render a javascript confirm box
     * @return string link representing the action with an image
     */
    protected function get_action($row, $action, $confirm = false) {
        $actionstr = 'str' . $action . $this->displaytype;
        $onclick = '';
        if ($confirm) {
            $onclick = 'onclick="return confirm(\'' .
                    get_string('action:confirm-' . $action . '-' . $this->displaytype, 'auth_antihammer') .
                    '\');" ';
        }
        return '<a ' . $onclick . 'href="' . new \moodle_url($this->baseurl,
                array('action' => $action, 'id' => $row->id, 'sesskey' => sesskey())) .
                '" alt="' . $this->{$actionstr} .
                '">' . $this->get_action_image($action) . '</a>';
    }

}