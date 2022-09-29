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
 * File         auth.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

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
     * @var string
     */
    protected $currentip;

    /**
     * @var auth_antihammer\antihammer
     */
    protected $iphammer;

    /**
     * @var auth_antihammer\repeatoffender
     */
    protected $repeatoffender;

    /**
     * @var auth_antihammer\antihammer
     */
    protected $userhammer;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'antihammer';
        $this->config = get_config('auth_antihammer');
        $this->currentip = getremoteaddr();
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
     * Hook for overriding behaviour of login page.
     * This method is called from login/index.php page for all enabled auth plugins.
     *
     */
    public function loginpage_hook() {
        global $frm;

        // First, cleanup old crap.
        \auth_antihammer\antihammer::clean_ip_hammering($this->config);
        \auth_antihammer\antihammer::clean_user_hammering($this->config);

        // If IP is in whitelist, don't do anything.
        if (auth_antihammer\util::in_whitelist($this->currentip)) {
            return;
        }

        // Pre load IP hammer. If we're already blocked, redirect.
        $this->load_ip_hammer();
        if ($this->iphammer->blocked) {
            // Redirect to error page, do not notify or log.
            if (!empty($this->config->blockpage)) {
                redirect(new moodle_url($this->config->blockpage));
            } else {
                redirect(new moodle_url('/auth/antihammer/blocked.php'));
            }
        }

        // Detect hammering and/or blocks.
        try {
            // Do we already have submitted data?
            $frm = data_submitted();
            if (isset($frm->username) && isset($frm->password)) {
                if (strlen($frm->username) > 0 && strlen($frm->password) > 0) {
                    // IP hammering block BEFORE username blocks.
                    $this->detect_ip_hammering();
                    $this->detect_user_hammering($frm->username);
                }
            }
        } catch (\auth_antihammer\exception $lex) {
            // Log object and message.
            $error = get_string('log:info:blocked', 'auth_antihammer', $lex->getMessage());
            $data = '';
            if ((bool) $this->iphammer->blocked) {
                $data = serialize($this->iphammer);
            } else if ((bool) $this->userhammer->blocked) {
                $data = serialize($this->userhammer);
            }
            \auth_antihammer\logmessage::log_info($error, 'auth/antihammer', 'blocked', $data, $lex->getCode());

            // Process messages if needed.
            $a = new stdClass();
            $a->ip = $this->currentip;
            $a->username = '-';
            if (!empty($this->userhammer) && (bool) $this->userhammer->blocked) {
                $a->username = $this->userhammer->username;
            }
            \auth_antihammer\messaging::message_notifyblocking($a, $this->config);

            // Redirect or display error.
            if (!empty($this->config->blockpage)) {
                redirect(new moodle_url($this->config->blockpage));
            } else {
                redirect(new moodle_url('/auth/antihammer/blocked.php'));
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
     * Load IP hammer
     */
    protected function load_ip_hammer() {
        $params = array();
        $params['type'] = \auth_antihammer\antihammer::TYPE_IP;
        $params['ip'] = $this->currentip;
        $this->iphammer = \auth_antihammer\antihammer::find($params);
        // Add to repeat offenders if enabled.
        if ($this->config->enablerepeatoffenders) {
            $this->repeatoffender = \auth_antihammer\repeatoffender::append_to_ip_record($this->currentip);
        }
    }

    /**
     * Detect/insert hammering records (this is done by IP address and hence will
     * only take IP address based hammering into account if configured)
     *
     * @return boolean
     * @throws \auth_antihammer\exception
     */
    protected function detect_ip_hammering() {
        if (!(bool) $this->config->blockip) {
            return false;
        }

        // Check if already blocked.
        if ($this->iphammer->blocked) {
            throw new \auth_antihammer\exception('err:blocked:ip', '', $this->iphammer);
        }

        $this->iphammer->ip = $this->currentip;
        $this->iphammer->count++;

        // Now check if to be blocked.
        if ($this->config->enablerepeatoffenders) {
            $blockduration = $this->repeatoffender->get_block_duration($this->config->ip_attemptcounter);
        } else {
            $blockduration = $this->config->ip_attemptcounter;
        }

        $timecheck = $this->iphammer->firstattempt + $blockduration;
        if ((time() <= $timecheck) && ($this->iphammer->count >= $this->config->ip_attempts)) {
            // Set blocked.
            $this->iphammer->blocked = 1;
            $this->iphammer->blocktime = time();
        } else if ((time() > $timecheck)) {
            // Reset firstattempt (to prevent messing up in case cleanup is disabled).
            $this->iphammer->firstattempt = time();
        }
        $this->iphammer->save();
        if ($this->iphammer->blocked) {
            // Add to repeat offender?
            if ($this->config->enablerepeatoffenders) {
                $this->repeatoffender->blockcounter++;
                $this->repeatoffender->save();
            }

            // Add to globals?
            if ((bool)$this->config->addcfgipblock) {
                auth_antihammer\antihammer::add_blocked_ip_to_global($this->iphammer->ip);
            }

            throw new \auth_antihammer\exception('err:blocked:ip', '', $this->iphammer);
        }
    }

    /**
     * Detect hammering status for a moodle user and insert/update database record
     *
     * @param string $username moodle username
     * @return boolean
     * @throws \auth_antihammer\exception
     */
    protected function detect_user_hammering($username) {
        if (!(bool) $this->config->blockusername) {
            return false;
        }
        $params = array();
        $params['type'] = \auth_antihammer\antihammer::TYPE_USER;
        $params['ip'] = $this->currentip;
        $params['username'] = $username;
        $this->userhammer = \auth_antihammer\antihammer::find($params);
        // Check if already blocked.
        if ($this->userhammer->blocked) {
            throw new \auth_antihammer\exception('err:blocked:user', '', $this->userhammer);
        }

        $this->userhammer->username = $username;
        $this->userhammer->ip = $this->currentip;
        $this->userhammer->count++;

        // Now check if to be blocked.
        $timecheck = $this->userhammer->firstattempt + $this->config->attemptcounter;
        if ((time() <= $timecheck) && ($this->userhammer->count >= $this->config->attempts)) {
            // Set blocked.
            $this->userhammer->blocked = 1;
            $this->userhammer->blocktime = time();
        } else if ((time() > $timecheck)) {
            // Reset firstattempt (to prevent messing up in case cleanup is disabled).
            $this->userhammer->firstattempt = time();
        }
        $this->userhammer->save();

        if ($this->userhammer->blocked) {
            throw new \auth_antihammer\exception('err:blocked:user', '', $this->userhammer);
        }
    }

}
