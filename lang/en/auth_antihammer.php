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
 *
 * De-generated language file
 *
 * File         : auth_antihammer.php
 * Encoding     : UTF-8
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 **/
$string['auth_antihammerdescription'] = 'Users can not sign in. This auth module serves as a blocking system for login attempts';
$string['pluginname'] = 'antihammer Anti-hammering / Login blocker';
$string['antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['auth_antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['attemptcounter'] = 'Attempts timespan';
$string['attempts'] = 'Maximum number of attempts';
$string['ip_attemptcounter'] = 'IP attempts timespan';
$string['ip_attempts'] = 'Maximum number of attempts';
$string['autoclear_after'] = 'Autoclear block after (seconds)';
$string['autoclear_blocked'] = 'Autoclear blocked IPs/users?';
$string['blockip'] = 'Block by IP addresses?';
$string['blocklangcode'] = 'Language code for message to display after someone is blocked';
$string['blockpage'] = 'Page to display when someone is blocked';
$string['blockusername'] = 'Block by username?';
$string['notificationemail'] = 'Block notification email address';
$string['notificationemail_fname'] = 'Block notification recipient firstname';
$string['notificationemail_lname'] = 'Block notification recipient lastname';
$string['str:blocked:page'] = 'Your account and/or IP address has been blocked.';
$string['log:info:blocked'] = 'Blocked: {$a}';
$string['mail:blocking:subject'] = 'Login Anti-hammering: user or IP address blocked';
$string['mail:blocking:message'] = '<p>Dear {$a->firstname} {$a->lastname}</p>
<p>A block has been set as a result of too many login attempts</p>
<p>The block is made active for IP address {$a->ip}, username {$a->username}</p>
<p>Kind regards,<br/>Support system</p>';
$string['enabled'] = 'Antihammering plugin enabled?';