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
 * antihammer object class for auth antihammer
 *
 * File         antihammer.php
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
 * auth_antihammer\antihammer
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class antihammer {

    /**
     * Blocking type IP
     */
    const TYPE_IP = 'ip';

    /**
     * Blocking type USER
     */
    const TYPE_USER = 'user';

    /**
     * primary key identifier
     * @var int
     */
    public $id = 0;
    /**
     * antihammering type
     * @var string
     */
    public $type = '';
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
     * get the antihammering type identifier
     * @return string antihammering type
     */
    public function get_type() {
        return $this->type;
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
     * @return \auth_antihammer\antihammer
     */
    public function set_id($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * set the antihammering type
     * @param string $type
     * @return \auth_antihammer\status
     */
    public function set_type($type) {
        $this->type = $type;
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
     * @return \auth_antihammer\antihammer
     */
    public function set_ip($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * sets the hammering counter
     * @param int $count
     * @return \auth_antihammer\antihammer
     */
    public function set_count($count) {
        $this->count = $count;
        return $this;
    }

    /**
     * sets timestamp indicating when the record is first created.
     * This is used to determine blocking based on configuration
     * @param int $firstattempt timestamp of first occurance
     * @return \auth_antihammer\antihammer
     */
    public function set_firstattempt($firstattempt) {
        $this->firstattempt = $firstattempt;
        return $this;
    }

    /**
     * sets whether or not the IP address is blocked
     * @param int $blocked
     * @return \auth_antihammer\antihammer
     */
    public function set_blocked($blocked) {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * set the timestamp indicating when this record will be removed
     * @param int $blocktime
     * @return \auth_antihammer\antihammer
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
     * Save (insert or update) this instance to the database
     *
     * @return bool true if inserted or correctly updated, false otherwise
     */
    public function save() {
        global $DB;
        if ($this->id > 0) {
            return $DB->update_record('auth_antihammer', $this);
        } else {
            $this->id = $DB->insert_record('auth_antihammer', $this);
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
            $config = get_config('auth_antihammer');
            if ((bool)$config->addcfgipblock && $this->type === self::TYPE_IP && $this->blocked) {
                self::remove_blocked_ip_from_global($this->ip);
            }
            return $DB->delete_records('auth_antihammer', array('id' => $this->id));
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
        $self = new static();
        $self->set_from_object($obj);
        return $self;
    }

    /**
     * Find an instance in the database based on the given parameters.
     * If nothing is found, this method created a new "empty" instance
     *
     * @param array $params
     * @return \auth_hammer\hammer
     */
    final public static function find($params) {
        global $DB;
        $sql = "SELECT * FROM {auth_antihammer} WHERE ";
        $conditions = array();
        foreach ($params as $key => $unused) {
            $conditions[] = "$key = ?";
        }
        $sql .= implode(' AND ', $conditions) . " ORDER BY blocked DESC, firstattempt DESC";
        $record = $DB->get_record_sql($sql, array_values($params));
        if ($record !== false) {
            return self::create_from_object($record);
        } else {
            $self = new self();
            $self->firstattempt = time();
            $self->type = (isset($params['type']) ? $params['type'] : self::TYPE_USER);
            return $self;
        }
    }

    /**
     * Find all hammering records based on the given conditions
     *
     * @param array $params
     * @param int $start
     * @param int $limit
     * @return array
     */
    final public static function find_all($params, $start, $limit) {
        global $DB;
        $sort = '';
        $fields = '*';
        return $DB->get_records('auth_antihammer', $params, $sort, $fields, $start, $limit);
    }

    /**
     * Clean IP hammering status records if configured (based on clearing settings)
     *
     * @param \stdClass $config auth_antihammer configuration
     *
     * @return void
     */
    public static function clean_ip_hammering($config = null) {
        global $DB;
        if ($config === null) {
            $config = get_config('auth_antihammer');
        }

        // Clean if attempts are below count taking the time into account.
        $cleantime = time() - $config->ip_attemptcounter;
        $params = array(self::TYPE_IP, $config->ip_attempts, $cleantime);
        $DB->delete_records_select('auth_antihammer', 'type = ? AND count < ? AND firstattempt < ?', $params);

        if (!(bool) $config->autoclear_ipblock) {
            return;
        }
        // Clean all blocked.
        $cleantime = time() - $config->autoclear_ipblock_after;
        $params = array(self::TYPE_IP, $cleantime);
        $DB->delete_records_select('auth_antihammer', 'type = ? AND blocktime < ? AND blocked = 1', $params);
    }

    /**
     * Clean user hammering status records if configured (based on clearing settings)
     *
     * @param \stdClass $config auth_antihammer configuration
     *
     * @return void
     */
    public static function clean_user_hammering($config = null) {
        global $DB;
        if ($config === null) {
            $config = get_config('auth_antihammer');
        }

        // Clean if attempts are below count taking the time into account.
        $cleantime = time() - $config->attemptcounter;
        $params = array(self::TYPE_USER, $config->attempts, $cleantime);
        $DB->delete_records_select('auth_antihammer', 'type = ? AND count < ? AND firstattempt < ?', $params);

        if (!(bool) $config->autoclear_userblock) {
            return;
        }
        // Clean all blocked.
        $cleantime = time() - $config->autoclear_userblock_after;
        $params = array(self::TYPE_USER, $cleantime);
        if ((bool)$config->addcfgipblock) {
            $iplist = $DB->get_fieldset_select('auth_antihammer', 'ip', 'type = ? AND blocktime < ? AND blocked = 1', $params);
            $iplist = array_unique($iplist);
            foreach ($iplist as $ipaddress) {
                self::remove_blocked_ip_from_global($ipaddress);
            }
        }
        $DB->delete_records_select('auth_antihammer', 'type = ? AND blocktime < ? AND blocked = 1', $params);
    }

    /**
     * Clean specified user based on username (static for external usage)
     *
     * @param string $username
     */
    public static function clean_for_user($username) {
        global $DB;
        $params = array($username);
        $DB->delete_records_select('auth_antihammer', 'username = ', $params);
    }

    /**
     * Clean specified user based on userid (static for external usage)
     *
     * @param string $userid
     */
    public static function clean_for_userid($userid) {
        global $DB;
        $params = array($userid);
        $DB->delete_records_select('auth_antihammer', 'userid = ', $params);
    }

    /**
     * Add IP block to global configuration
     *
     * @param string $ipaddress
     */
    public static function add_blocked_ip_to_global($ipaddress) {
        $addresses = explode("\n", get_config('core', 'blockedip'));
        $addresses[] = $ipaddress;
        set_config('blockedip', implode("\n", $addresses));
    }

    /**
     * Remove IP block from global configuration
     *
     * @param string $ipaddress
     */
    public static function remove_blocked_ip_from_global($ipaddress) {
        $addresses = explode("\n", get_config('core', 'blockedip'));
        $idx = array_search($ipaddress, $addresses);
        if ($idx !== false) {
            unset($addresses[$idx]);
            set_config('blockedip', implode("\n", $addresses));
        }
    }

}
