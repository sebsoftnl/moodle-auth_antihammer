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
 * utility class for auth antihammer
 *
 * File         util.php
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
 * auth_antihammer\util
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {

    /**
     * Notify enduser about things with this plugin.
     * For now this checks whether or not the plugin is enabled.
     */
    public static function check_notifications() {
        $enabled = \core_plugin_manager::instance()->get_enabled_plugins('auth');
        if (!isset($enabled['antihammer'])) {
            \core\notification::warning(get_string('plugin:notenabled', 'auth_antihammer'));
        }

        $getremoteaddrconf = get_config('moodle', 'getremoteaddrconf');
        if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'] &&
                ($getremoteaddrconf & GETREMOTEADDR_SKIP_HTTP_CLIENT_IP ||
                $getremoteaddrconf & GETREMOTEADDR_SKIP_HTTP_X_FORWARDED_FOR)) {
            \core\notification::warning(get_string('plugin:getremoteaddrconf:notification', 'auth_antihammer'));
        }
    }

    /**
     * Check if given IP address is in the whitelist.
     *
     * @param string $ip
     * @return boolean
     */
    public static function in_whitelist($ip) {
        $config = get_config('auth_antihammer', 'ipwhitelist');
        if (is_null($config)) {
            return false;
        }
        $iplist = array_map('trim', explode("\n", $config));
        return in_array($ip, $iplist);
    }

    /**
     * Add or remove given IP address to the whitelist.
     *
     * @param string $ip
     * @param bool $remove
     */
    public static function add_to_whitelist($ip, $remove = false) {
        global $DB;
        $config = get_config('auth_antihammer', 'ipwhitelist');
        if (is_null($config)) {
            $config = '';
        }
        $iplist = array_unique(array_map('trim', explode("\n", $config)));
        if ($remove && in_array($ip, $iplist)) {
            $iplist = array_diff($iplist, [$ip]);
            set_config('ipwhitelist', implode("\n", $iplist), 'auth_antihammer');
            return;
        }
        if (!in_array($ip, $iplist)) {
            $iplist[] = $ip;
            set_config('ipwhitelist', implode("\n", $iplist), 'auth_antihammer');
            // Remove from hammering records (we DO NOT remove the repeat offenders record!).
            $DB->delete_records('auth_antihammer', ['ip' => $ip]);
        }
    }

}
