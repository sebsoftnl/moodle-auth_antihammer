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
 * Plugin class for auth antihammer
 *
 * File         : auth.php
 * Encoding     : UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page.
}

require_once($CFG->libdir . '/authlib.php');

/**
 * Plugin for no authentication.
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_antihammer extends auth_plugin_base {

    /**
     * 
     * @var string
     */
    protected $currentip;

    /**
     *
     * @var auth_antihammer
     */
    protected $currenthammer;

    /**
     *
     * @var auth_antihammer_status
     */
    protected $currentstatus;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'antihammer';
        $this->config = get_config('auth/antihammer');
        $this->currentip = $this->get_ip();
    }

    /**
     * Returns true if the username and password work or don't exist and false
     * if the user exists and the password is wrong.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     *
     */
    public function user_update_password($user, $newpassword) {
        return false;
    }

    /**
     * Indicates if password hashes should be stored in local moodle database.
     * @return bool true means md5 password hash stored in user table, false means flag 'not_cached' stored there instead
     */
    public function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    public function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    public function can_reset_password() {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param \stdClass $config block global configuration
     * @param array $err errors
     * @param array $userfields
     */
    public function config_form($config, $err, $userfields) {
        global $CFG;
        include($CFG->dirroot . '/auth/antihammer/config.html.php');
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     *
     * @param \stdClass $config
     * @return bool
     */
    public function process_config($config) {
        self::config_load_default($config);
        // Save settings.
        set_config('enabled', $config->enabled, 'auth/antihammer');
        set_config('attempts', $config->attempts, 'auth/antihammer');
        set_config('attemptcounter', $config->attemptcounter, 'auth/antihammer');
        set_config('ip_attempts', $config->ip_attempts, 'auth/antihammer');
        set_config('ip_attemptcounter', $config->ip_attemptcounter, 'auth/antihammer');
        set_config('autoclear_blocked', $config->autoclear_blocked, 'auth/antihammer');
        set_config('autoclear_after', $config->autoclear_after, 'auth/antihammer');
        set_config('blockusername', $config->blockusername, 'auth/antihammer');
        set_config('blockip', $config->blockip, 'auth/antihammer');
        set_config('blockpage', $config->blockpage, 'auth/antihammer');
        set_config('blocklangcode', $config->blocklangcode, 'auth/antihammer');
        set_config('notificationemail', $config->notificationemail, 'auth/antihammer');
        set_config('notificationemail_fname', $config->notificationemail_fname, 'auth/antihammer');
        set_config('notificationemail_lname', $config->notificationemail_lname, 'auth/antihammer');

        return true;
    }

    /**
     * Hook for overriding behaviour of login page.
     * This method is called from login/index.php page for all enabled auth plugins.
     *
     */
    public function loginpage_hook() {
        global $frm, $user;
        global $CFG;
        global $SESSION, $OUTPUT, $PAGE, $DB;

        // Don't do anything if we're disabled.
        if (!(bool)$this->config->enabled) {
            return;
        }

        // First, cleanup old crap.
        $this->clean_hammering();
        $this->clean_user_status();

        // Detect hammering and/or blocks.
        try {
            // Do we already have submitted data?
            $frm = data_submitted();
            if (isset($frm->username) && isset($frm->password)) {
                if (strlen($frm->username) > 0 && strlen($frm->password) > 0) {
                    // PI hammering block BEFORE username blocks.
                    $this->detect_hammering();
                    $this->detect_user_status($frm->username);
                }
            }
        } catch (\auth_antihammer\exception $lex) {
            // Log object and message.
            $error = get_string('log:info:blocked', 'auth_antihammer', $lex->getMessage());
            $data = '';
            if ((bool) $this->currenthammer->blocked) {
                $data = serialize($this->currenthammer);
            } else if ((bool) $this->currentstatus->blocked) {
                $data = serialize($this->currentstatus);
            }
            \auth_antihammer\logmessage::log_info($error, 'auth/antihammer', 'blocked', $data, $lex->getCode());
            // Process email if needed.
            $this->process_blocking_email();
            // Redirect or display error.
            if (!empty($this->config->blockpage)) {
                redirect(new moodle_url($this->config->blockpage));
            } else {
                print_error($this->config->blocklangcode, 'auth_antihammer');
            }
        }
    }

    /**
     * Hook for overriding behaviour of logout page.
     * This method is called from login/logout.php page for all enabled auth plugins.
     *
     */
    public function logoutpage_hook() {
        global $USER;     // Use $USER->auth to find the plugin used for login.
        global $redirect; // Can be used to override redirect after logout.
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    public function can_signup() {
        return false;
    }

    /**
     * Returns the URL for editing the users' profile, or empty if the default
     * URL can be used.
     *
     * This method is used if can_edit_profile() returns true.
     * This method is called only when user is logged in, it may use global $USER.
     *
     * @return moodle_url url of the profile page or null if standard used
     */
    public function edit_profile_url() {
        return null;
    }

    /**
     * get global block configuration, appending defaults where not yet set
     *
     * @return \stdClass
     */
    static final public function config_get_default() {
        $config = get_config('auth/antihammer');
        if (!is_object($config)) {
            $config = new stdClass();
        }
        // Set to defaults if undefined.
        self::config_load_default($config);
        return $config;
    }

    /**
     * append default configuration values.
     * This will only set defaults if keys are not present on the given configuration
     *
     * @param \stdClass $config
     */
    static final public function config_load_default(&$config) {
        // Set to defaults if undefined.
        if (!isset($config->enabled)) {
            $config->enabled = 1;
        }
        if (!isset($config->blockip)) {
            $config->blockip = 1;
        }
        if (!isset($config->ip_attempts)) {
            $config->ip_attempts = 10;
        }
        if (!isset($config->ip_attemptcounter)) {
            $config->ip_attemptcounter = 300;
        }
        if (!isset($config->attempts)) {
            $config->attempts = 5;
        }
        if (!isset($config->attemptcounter)) {
            $config->attemptcounter = 300;
        }
        if (!isset($config->autoclear_blocked)) {
            $config->autoclear_blocked = 1;
        }
        if (!isset($config->autoclear_after)) {
            $config->autoclear_after = 86400;
        }
        if (!isset($config->blockusername)) {
            $config->blockusername = 1;
        }
        if (!isset($config->blockpage)) {
            $config->blockpage = '';
        }
        if (!isset($config->blocklangcode)) {
            $config->blocklangcode = 'str:blocked:page';
        }
        if (!isset($config->notificationemail)) {
            $config->notificationemail = '';
        }
        if (!isset($config->notificationemail_fname)) {
            $config->notificationemail_fname = '';
        }
        if (!isset($config->notificationemail_lname)) {
            $config->notificationemail_lname = '';
        }
    }

    /**
     * Clean IP hammering records if configured (based on clearing settings)
     *
     * @return void 
     */
    protected function clean_hammering() {
        global $DB;
        if (!(bool) $this->config->autoclear_blocked) {
            return;
        }
        // Clean all blocked.
        $cleantime = time() - $this->config->autoclear_after;
        $params = array($cleantime);
        $DB->delete_records_select('auth_antihammer', 'blocktime < ? AND blocked = 1', $params);

        // Also clean if attempts are below count taking the time into account.
        $cleantime = time() - $this->config->ip_attemptcounter;
        $params = array($this->config->ip_attempts, $cleantime);
        $DB->delete_records_select('auth_antihammer', 'count < ? AND firstattempt < ?', $params);
    }

    /**
     * Detect/insert hammering records (this is done by IP address and hence will 
     * only take IP address based hammering into account if configured)
     *
     * @return boolean
     * @throws \auth_antihammer\exception
     */
    protected function detect_hammering() {
        if (!(bool) $this->config->blockip) {
            $this->currenthammer = new \auth_antihammer\hammer();
            return false;
        }
        $params = array();
        $params['ip'] = $this->currentip;
        $this->currenthammer = \auth_antihammer\hammer::find($params);
        // Check if already blocked.
        if ($this->currenthammer->blocked) {
            throw new \auth_antihammer\exception('Hammering detected: IP address = ' .
                    $this->currenthammer->ip . ' (IP is blocked)');
        }

        if ($this->currenthammer->id == 0) {
            $this->currenthammer->ip = $this->currentip;
        }
        $this->currenthammer->count++;

        // Now check if to be blocked.
        $timecheck = $this->currenthammer->firstattempt + $this->config->ip_attemptcounter;
        if ((time() <= $timecheck) && ($this->currenthammer->count >= $this->config->ip_attempts)) {
            // Set blocked.
            $this->currenthammer->blocked = 1;
            $this->currenthammer->blocktime = time();
        } else if ((time() > $timecheck)) {
            // Reset firstattempt (to prevent messing up in case cleanup is disabled).
            $this->currenthammer->firstattempt = time();
        }
        $this->currenthammer->save();
        if ($this->currenthammer->blocked) {
            throw new \auth_antihammer\exception('Hammering detected: IP address = ' .
                    $this->currenthammer->ip . ' (IP is blocked)');
        }
    }

    /**
     * Clean hammering status records if configured (based on clearing settings)
     *
     * @return void 
     */
    protected function clean_user_status() {
        global $DB;
        if (!(bool) $this->config->autoclear_blocked) {
            $this->currentstatus = new \auth_antihammer\status();
            return;
        }
        // Clean all blocked.
        $cleantime = time() - $this->config->autoclear_after;
        $params = array($cleantime);
        $DB->delete_records_select('auth_antihammer_status', 'blocktime < ? AND blocked = 1', $params);

        // Now also clean if attempts are below count taking the time into account.
        $cleantime = time() - $this->config->attemptcounter;
        $params = array($this->config->attempts, $cleantime);
        $DB->delete_records_select('auth_antihammer_status', 'count < ? AND firstattempt < ?', $params);
    }

    /**
     * Detect hammering status for a moodle user and insert/update database record
     *
     * @param string $username moodle username
     * @return boolean
     * @throws \auth_antihammer\exception
     */
    protected function detect_user_status($username) {
        if (!(bool) $this->config->blockusername) {
            return false;
        }
        $params = array();
        $params['ip'] = $this->currentip;
        $params['username'] = $username;
        $this->currentstatus = \auth_antihammer\status::find($params);
        // Check if already blocked.
        if ($this->currentstatus->blocked) {
            throw new \auth_antihammer\exception('Hammering detected: Username=' . $username .
                    '; IP address = ' . $this->currentstatus->ip . ' (IP and/or username is blocked)');
        }

        if ($this->currentstatus->id == 0) {
            $this->currentstatus->ip = $this->currentip;
            $this->currentstatus->username = $username;
        }
        $this->currentstatus->count++;

        // Now check if to be blocked.
        $timecheck = $this->currentstatus->firstattempt + $this->config->attemptcounter;
        if ((time() <= $timecheck) && ($this->currentstatus->count >= $this->config->attempts)) {
            // Set blocked.
            $this->currentstatus->blocked = 1;
            $this->currentstatus->blocktime = time();
        } else if ((time() > $timecheck)) {
            // Reset firstattempt (to prevent messing up in case cleanup is disabled).
            $this->currentstatus->firstattempt = time();
        }
        $this->currentstatus->save();

        if ($this->currentstatus->blocked) {
            throw new \auth_antihammer\exception('Hammering detected: Username=' . $username .
                    '; IP address = ' . $this->currentstatus->ip . ' (IP and/or username is blocked)');
        }
    }

    /**
     * Process/send the email to a moodle user when his account has been blocked
     *
     * @return boolean
     */
    protected function process_blocking_email() {
        if (empty($this->config->notificationemail)) {
            return false;
        }

        $user = $this->generate_notification_user();

        $a = new stdClass();
        $a->firstname = $user->firstname;
        $a->lastname = $user->lastname;
        $a->ip = $this->currentip;
        $a->username = '-';
        if (($this->currentstatus instanceof \auth_antihammer\status) && (bool) $this->currentstatus->blocked) {
            $a->username = $this->currentstatus->username;
        }

        if (method_exists('core_user', 'get_support_user')) {
            $from = core_user::get_support_user();
        } else {
            $from = generate_email_supportuser();
        }
        $subject = get_string('mail:blocking:subject', 'auth_antihammer');
        $messagehtml = get_string('mail:blocking:message', 'auth_antihammer', $a);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        email_to_user($user, $from, $subject, $messagetext, $messagehtml);

        return true;
    }

    /**
     * Generate a notification user usable for the email_to_user function
     *
     * @return \stdClass
     */
    protected function generate_notification_user() {
        $user = new stdClass();
        $user->email = $this->config->notificationemail;
        $user->deleted = 0;
        $user->firstname = $this->config->notificationemail_fname;
        $user->lastname = $this->config->notificationemail_lname;
        $user->mailformat = 1;
        $user->id = 1;
        // OMG. New moodle UGHHHH.
        $user->lastnamephonetic = null;
        $user->firstnamephonetic = null;
        $user->middlename = null;
        $user->alternatename = null;

        return $user;
    }

    /**
     * Detect the IP address of current request
     *
     * @return string IP address
     */
    protected function get_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Clean specified user based on username (static for external usage)
     *
     * @param string $username
     */
    static public function clean_for_user($username) {
        global $DB;
        $params = array($username);
        $DB->delete_records_select('auth_antihammer_status', 'username = ', $params);
    }

}