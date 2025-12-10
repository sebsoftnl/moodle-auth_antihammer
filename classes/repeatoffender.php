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
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer;

/**
 * auth_antihammer\antihammer
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repeatoffender {
    /**
     * primary key identifier
     * @var int
     */
    public $id = 0;
    /**
     * RO Type
     * @var string
     */
    public $typ = 'ip';
    /**
     * userid
     * @var int
     */
    public $userid = 0;
    /**
     * IP address
     * @var string
     */
    public $ip = '';
    /**
     * Repeat counter
     * @var int
     */
    public $counter = 0;
    /**
     * Block counter
     * @var int
     */
    public $blockcounter = 0;
    /**
     * time of creation for this record
     * @var int
     */
    public $timecreated;
    /**
     * time of last modification to record
     * @var int
     */
    public $timemodified;

    /**
     * Get primary key
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Set primary key
     *
     * @param int $id
     * @return $this
     */
    public function set_id($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get RO type
     *
     * @return string
     */
    public function get_typ(): string {
        return $this->typ;
    }

    /**
     * Set RO type
     *
     * @param string $typ
     * @return $this
     */
    public function set_typ(string $typ) {
        $this->typ = $typ;
        return $this;
    }

    /**
     * Get userid
     *
     * @return int
     */
    public function get_userid(): int {
        return $this->userid;
    }

    /**
     * Set userid
     *
     * @param int $userid
     * @return $this
     */
    public function set_userid(int $userid) {
        $this->userid = $userid;
        return $this;
    }

    /**
     * Get IP
     *
     * @return string
     */
    public function get_ip() {
        return $this->ip;
    }

    /**
     * Set IP
     *
     * @param string $ip
     * @return static
     */
    public function set_ip($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get counter
     *
     * @return string
     */
    public function get_counter() {
        return $this->counter;
    }

    /**
     * Set counter
     *
     * @param string $counter
     * @return static
     */
    public function set_counter($counter) {
        $this->counter = $counter;
        return $this;
    }

    /**
     * Get block counter
     *
     * @return string
     */
    public function get_blockcounter() {
        return $this->blockcounter;
    }

    /**
     * Set block counter
     *
     * @param string $blockcounter
     * @return static
     */
    public function set_blockcounter($blockcounter) {
        $this->blockcounter = $blockcounter;
        return $this;
    }

    /**
     * Get creation timestamp of record
     */
    public function get_timecreated() {
        return $this->timecreated;
    }

    /**
     * Set creation timestamp of record
     *
     * @param int $timecreated
     * @return $this
     */
    public function set_timecreated($timecreated) {
        $this->timecreated = $timecreated;
        return $this;
    }

    /**
     * Get last modification timestamp of record
     */
    public function get_timemodified() {
        return $this->timemodified;
    }

    /**
     * Set last modification timestamp of record
     * @param int $timemodified
     * @return $this
     */
    public function set_timemodified($timemodified) {
        $this->timemodified = $timemodified;
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
            $this->timemodified = time();
            return $DB->update_record('auth_antihammer_ro', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = $this->timecreated;
            $this->id = $DB->insert_record('auth_antihammer_ro', $this);
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
            $config = get_config('auth_antihammer_ro');
            if ((bool)$config->addcfgipblock && $this->type === self::TYPE_IP && $this->blocked) {
                self::remove_blocked_ip_from_global($this->ip);
            }
            return $DB->delete_records('auth_antihammer_ro', ['id' => $this->id]);
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
     * Find an instance in the database based on the given IP.
     *
     * @param string $ip
     * @return \auth_hammer\repeatoffender
     */
    final public static function get_ip_record($ip) {
        global $DB;
        $record = $DB->get_record('auth_antihammer_ro', ['typ' => 'ip', 'ip' => $ip]);
        if ($record !== false) {
            return self::create_from_object($record);
        } else {
            return static::create_from_object(['typ' => 'ip', 'ip' => $ip]);
        }
    }

    /**
     * Update repeat offender record in the database based on the given IP.
     *
     * @param string $ip
     * @return \auth_hammer\repeatoffender
     */
    final public static function append_to_ip_record($ip) {
        $record = static::get_ip_record($ip);
        $record->counter++;
        $record->save();
        return $record;
    }

    /**
     * Find an instance in the database based on the given userid.
     *
     * @param int $userid
     * @return \auth_hammer\repeatoffender
     */
    final public static function get_user_record($userid) {
        global $DB;
        $record = $DB->get_record('auth_antihammer_ro', ['typ' => 'user', 'userid' => $userid]);
        if ($record !== false) {
            return self::create_from_object($record);
        } else {
            return static::create_from_object(['typ' => 'user', 'userid' => $userid]);
        }
    }

    /**
     * Update repeat offender record in the database based on the given userid.
     *
     * @param int $userid
     * @return \auth_hammer\repeatoffender
     */
    final public static function append_to_user_record($userid) {
        $record = static::get_user_record($userid);
        $record->counter++;
        $record->save();
        return $record;
    }

    /**
     * Calculate new blocking duration.
     * Every time an IP's been blocked we will double up.
     * Please do note the MAX duration is a year.
     *
     * @param int $basetimespan
     * @return int new block duration
     */
    public function get_block_duration($basetimespan) {
        // For now, double up every time by default.
        // We might change this in the future.
        if ($this->blockcounter <= 1) {
            return $basetimespan;
        }
        $span = $basetimespan * pow(2, $this->blockcounter);
        if ($span >= YEARSECS) {
            $span = YEARSECS;
        }
        return $span;
    }
}
