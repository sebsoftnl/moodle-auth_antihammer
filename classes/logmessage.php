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
 * Logmessage object class for auth antihammer
 *
 * File         logmessage.php
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
 * \auth_antihammer\logmessage
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logmessage {

    /**
     * primary key identifier
     * @var int
     */
    public $id;
    /**
     * ID of the moodle user that caused this record to be inserted
     * @var int
     */
    public $userid;
    /**
     * module identifier for this log message
     * @var string
     */
    public $module;
    /**
     * log message type
     * @var string
     */
    public $type;
    /**
     * log message
     * @var string
     */
    public $msg;
    /**
     * log code
     * @var int
     */
    public $code = 0;
    /**
     * action taken, this may or may not be usable or relevant
     * @var string
     */
    public $action;
    /**
     * any extra log data
     * @var string (mixed)
     */
    public $data;
    /**
     * date of creation for this record
     * @var int
     */
    public $datecreated;

    /**
     * gets the primary key identifier
     * @return int record primary key identifier
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * gets the ID of the moodle user that caused this record to be inserted
     * @return int $userid
     */
    public function get_userid() {
        return $this->userid;
    }

    /**
     * get the module identifier for this log message
     * @return string log module identifier
     */
    public function get_module() {
        return $this->module;
    }

    /**
     * get the log message type
     * @return string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * get the log message
     * @return string
     */
    public function get_msg() {
        return $this->msg;
    }

    /**
     * get the log code
     * @return string
     */
    public function get_code() {
        return $this->code;
    }

    /**
     * get the action taken, this may or may not be usable or relevant
     * @return string
     */
    public function get_action() {
        return $this->action;
    }

    /**
     * get any extra log data (may be serialized)
     * @return string
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * get date of creation for this record
     * @return int
     */
    public function get_datecreated() {
        return $this->datecreated;
    }

    /**
     * sets the primary key identifier
     * @param int $id record primary key identifier
     * @return \auth_antihammer\logmessage
     */
    public function set_id($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * sets the ID of the moodle user that caused this record to be inserted
     * @param int $userid
     * @return \auth_antihammer\logmessage
     */
    public function set_userid($userid) {
        $this->userid = $userid;
        return $this;
    }

    /**
     * set the module identifier for this log message
     * @param string $module
     * @return \auth_antihammer\logmessage
     */
    public function set_module($module) {
        $this->module = $module;
        return $this;
    }

    /**
     * set the log message type
     * @param string $type
     * @return \auth_antihammer\logmessage
     */
    public function set_type($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * set the log message
     * @param string $msg
     * @return \auth_antihammer\logmessage
     */
    public function set_msg($msg) {
        $this->msg = $msg;
        return $this;
    }

    /**
     * set the log code
     * @param int $code
     * @return \auth_antihammer\logmessage
     */
    public function set_code($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * set the action taken, this may or may not be usable or relevant
     * @param string $action
     * @return \auth_antihammer\logmessage
     */
    public function set_action($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * set any extra log data (should be serialized if not a scalar value)
     * @param string $data
     * @return \auth_antihammer\logmessage
     */
    public function set_data($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * set date of creation for this record
     * @param int $datecreated
     * @return \auth_antihammer\logmessage
     */
    public function set_datecreated($datecreated) {
        $this->datecreated = $datecreated;
        return $this;
    }

    /**
     * set properties on instance based on given object
     *
     * @param \stdClass $obj
     */
    public function set_from_db($obj) {
        foreach ($obj as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Save (insert or update) this instance to the database
     *
     * @return bool true if inserted or correctly updated, false otherwise
     */
    public function save() {
        global $DB;
        if ($this->id > 0) {
            return $DB->update_record('auth_antihammer_log', $this);
        } else {
            $this->id = $DB->insert_record('auth_antihammer_log', $this);
        }
        return true;
    }

    /**
     * Delete this record from the database (if primary key field is available)
     *
     * @return bool true if success, false otherwise
     */
    public function delete() {
        global $DB;
        if ($this->id > 0) {
            return $DB->delete_records('auth_antihammer_log', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Create new instance based on given parameters
     *
     * @param \stdClass $obj
     * @return \self
     */
    final public static function create_from_object($obj) {
        $self = new self();
        $self->set_from_db($obj);
        return $self;
    }

    /**
     * Insert a log message
     *
     * @param string $type message type
     * @param string $msg message
     * @param string $module module
     * @param string $action action
     * @param string $data any extra data
     * @param int $code message code
     *
     * @return int insert ID
     */
    final public static function log_message($type, $msg, $module, $action, $data = null, $code = 0) {
        global $USER;

        $rmsg = new static();
        $rmsg->set_action($action);
        $rmsg->set_data($data);
        $rmsg->set_datecreated(time());
        $rmsg->set_module($module);
        $rmsg->set_msg($msg);
        $rmsg->set_code($code);
        $rmsg->set_type($type);
        $rmsg->set_userid($USER->id);

        return $rmsg->save();
    }

    /**
     * Insert an info log message
     *
     * @param string $msg message
     * @param string $module module
     * @param string $action action
     * @param string $data any extra data
     * @param int $code message code
     *
     * @return int insert ID
     */
    final public static function log_info($msg, $module, $action, $data = null, $code = 0) {
        return self::log_message('info', $msg, $module, $action, $data, $code);
    }

    /**
     * Insert a warning log message
     *
     * @param string $msg message
     * @param string $module module
     * @param string $action action
     * @param string $data any extra data
     * @param int $code message code
     *
     * @return int insert ID
     */
    final public static function log_warning($msg, $module, $action, $data = null, $code = 0) {
        return self::log_message('warning', $msg, $module, $action, $data, $code);
    }

    /**
     * Insert an error log message
     *
     * @param string $msg message
     * @param string $module module
     * @param string $action action
     * @param string $data any extra data
     * @param int $code message code
     *
     * @return int insert ID
     */
    final public static function log_error($msg, $module, $action, $data = null, $code = 0) {
        return self::log_message('error', $msg, $module, $action, $data, $code);
    }

    /**
     * Find a message by it's primary key
     *
     * @param int $id primary key value
     * @return \auth_hammer\logmessage
     */
    final public static function get_by_id($id) {
        global $DB;

        $data = $DB->get_record('auth_antihammer_log', array('id' => $id));
        if (!$data) {
            return null;
        }

        return static::create_from_object($data);
    }

    /**
     * Find all logmessage based on the given conditions
     *
     * @param array $params
     * @param int $start
     * @param int $limit
     * @return array list of \auth_hammer\logmessage
     */
    final public static function find_all($params, $start, $limit) {
        global $DB;
        $sort = '';
        $fields = '*';
        return $DB->get_records('auth_antihammer_log', $params, $sort, $fields, $start, $limit);
    }

}
