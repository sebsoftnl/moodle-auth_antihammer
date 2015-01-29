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
 * Status object class for auth antihammer
 *
 * File         : status.php
 * Encoding     : UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * */

namespace auth_antihammer;

/**
 * auth_antihammer\status
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class status {

    /**
     * primary key identifier
     * @var int
     */
    public $id = 0;
    /**
     * ID of the detected moodle user for which hammering could be detected
     * @var int
     */
    public $userid = 0;
    /**
     * username for which hammering is detected
     * @var string
     */
    public $username = '';
    /**
     * IP address
     * @var string
     */
    public $ip = '';
    /**
     * hammering counter
     * @var int
     */
    public $count = 0;
    /**
     * timestamp indicating when the record is first created.
     * This is used to determine blocking based on configuration
     * @var int
     */
    public $firstattempt = 0;
    /**
     * whether or not the IP address is blocked
     * @var int
     */
    public $blocked = 0;
    /**
     * timestamp indicating when this record will be removed
     * @var int
     */
    public $blocktime = 0;

    /**
     * gets the primary key identifier
     * @return int record primary key identifier
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * get the ID of the detected moodle user for which hammering could be detected
     * @return int user ID
     */
    public function get_userid() {
        return $this->userid;
    }

    /**
     * get the username for which hammering is detected
     * @return string username
     */
    public function get_username() {
        return $this->username;
    }

    /**
     * gets the IP address
     * @return string IP address
     */
    public function get_ip() {
        return $this->ip;
    }

    /**
     * gets the hammer counter
     * @return int hammering count
     */
    public function get_count() {
        return $this->count;
    }

    /**
     * get timestamp indicating when the record is first created.
     * This is used to determine blocking based on configuration
     * @return int
     */
    public function get_firstattempt() {
        return $this->firstattempt;
    }

    /**
     * get blocked status
     * @return int
     */
    public function get_blocked() {
        return $this->blocked;
    }

    /**
     * get the timestamp indicating when this record will be removed
     * @return int
     */
    public function get_blocktime() {
        return $this->blocktime;
    }

    /**
     * sets the primary key identifier
     * @param int $id record primary key identifier
     * @return \auth_antihammer\hammer
     */
    public function set_id($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * sets the ID of the detected moodle user for which hammering could be detected
     * @param int $userid
     * @return \auth_antihammer\status
     */
    public function set_userid($userid) {
        $this->userid = $userid;
        return $this;
    }

    /**
     * set the username for which hammering is detected
     * @param string $username
     * @return \auth_antihammer\status
     */
    public function set_username($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * sets the IP address
     * @param string $ip IP address
     * @return \auth_antihammer\hammer
     */
    public function set_ip($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * sets the hammering counter
     * @param int $count
     * @return \auth_antihammer\hammer
     */
    public function set_count($count) {
        $this->count = $count;
        return $this;
    }

    /**
     * sets timestamp indicating when the record is first created.
     * This is used to determine blocking based on configuration
     * @param int $firstattempt timestamp of first occurance
     * @return \auth_antihammer\hammer
     */
    public function set_firstattempt($firstattempt) {
        $this->firstattempt = $firstattempt;
        return $this;
    }

    /**
     * sets whether or not the IP address is blocked
     * @param int $blocked
     * @return \auth_antihammer\hammer
     */
    public function set_blocked($blocked) {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * set the timestamp indicating when this record will be removed
     * @param int $blocktime
     * @return \auth_antihammer\hammer
     */
    public function set_blocktime($blocktime) {
        $this->blocktime = $blocktime;
        return $this;
    }

    /**
     * set properties on instance based on given object
     *
     * @param \stdClass $obj
     */
    public function set_from_object($obj) {
        foreach ($obj as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Create new instance based on given parameters
     *
     * @param \stdClass $obj
     * @return \self
     */
    public static final function create_from_object($obj) {
        $self = new self();
        $self->set_from_object($obj);
        return $self;
    }

    /**
     * Find an instance in the database based on the given parameters.
     * If nothing is found, this method created a new "empty" instance
     *
     * @param array $params
     * @return \auth_hammer\status
     */
    static public final function find($params) {
        global $DB;
        $record = $DB->get_record('auth_antihammer_status', $params);
        if ($record !== false) {
            return self::create_from_object($record);
        } else {
            $self = new self();
            $self->firstattempt = time();
            return $self;
        }
    }

    /**
     * Find all statusses based on the given conditions
     *
     * @param array $params
     * @param int $start
     * @param int $limit
     * @return array 
     */
    static public final function find_all($params, $start, $limit) {
        global $DB;
        $sort = '';
        $fields = '*';
        return $DB->get_records('auth_antihammer_status', $params, $sort, $fields, $start, $limit);
    }

    /**
     * Save (insert or update) this instance to the database
     *
     * @return bool true if inserted or correctly updated, false otherwise
     */
    public function save() {
        global $DB;
        if ($this->id > 0) {
            return $DB->update_record('auth_antihammer_status', $this);
        } else {
            $this->id = $DB->insert_record('auth_antihammer_status', $this);
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
            return $DB->delete_records('auth_antihammer_status', array('id' => $this->id));
        }
        return false;
    }

}