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
 * Language file for auth_antihammer, EN
 *
 * File         auth_antihammer.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
$string['auth_antihammerdescription'] = 'Users can not sign in. This auth module serves as a blocking system for login attempts';
$string['pluginname'] = 'Anti-hammering / Login blocker';
$string['promo'] = 'Anti-hammering / loginblocker authentication plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['auth_antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['ap:log'] = 'Antihammer logs';
$string['title:report:logs'] = 'Antihammer logs';
$string['ap:report'] = 'Antihammer reports';
$string['ap:logdetails'] = 'Antihammer log detail';
$string['title:report:hammer'] = 'Antihammer reports';
$string['mail:blocking:subject'] = 'Login Anti-hammering: user or IP address blocked';
$string['mail:blocking:message'] = '<p>Dear {$a->firstname} {$a->lastname}</p>
<p>A block has been set as a result of too many login attempts</p>
<p>The block is made active for IP address {$a->ip}, username {$a->username}</p>
<p>Kind regards,<br/>Support system</p>';
$string['thead:action'] = 'action';
$string['thead:userid'] = 'userid';
$string['thead:module'] = 'module';
$string['thead:type'] = 'type';
$string['thead:msg'] = 'message';
$string['thead:code'] = 'code';
$string['thead:datecreated'] = 'date created';
$string['type:ip'] = 'IP block';
$string['type:user'] = 'User block';
$string['type:info'] = 'Information';
$string['action:delete:logitem'] = 'Delete log item';
$string['action:delete:hammeritem'] = 'Delete hammering item';
$string['action:view:logitem'] = 'View log details';
$string['action:confirm-delete-log'] = 'Delete log item';
$string['action:confirm-delete-hammer'] = 'Delete hammering item';
$string['thead:username'] = 'username';
$string['thead:ip'] = 'IP';
$string['thead:count'] = 'detection count';
$string['thead:firstattempt'] = 'first attempt';
$string['thead:blocked'] = 'blocked';
$string['thead:blocktime'] = 'time blockage';
$string['antihammer:delete'] = 'Remove records';
$string['antihammer:administration'] = 'Administer auth antihammer';
$string['err:sqltable:set_sql'] = 'set_sql() is disabled. This table defines it\'s own and is not customomizable';
$string['auth_antihammer_ipsettings'] = 'IP blocking settings';
$string['auth_antihammer_ipsettings_desc'] = '';
$string['blockip'] = 'Block by IP addresses?';
$string['ip_attemptcounter'] = 'IP attempts timespan';
$string['ip_attempts'] = 'Maximum number of attempts';
$string['autoclear_ipblock'] = 'Autoclear blocked IPs?';
$string['autoclear_ipblock_after'] = 'Autoclear IP block after';
$string['auth_antihammer_usersettings'] = 'User blocking settings';
$string['auth_antihammer_usersettings_desc'] = '';
$string['blockusername'] = 'Block by username?';
$string['attempts'] = 'Maximum number of attempts';
$string['attemptcounter'] = 'Attempts timespan';
$string['autoclear_userblock'] = 'Autoclear blocked users?';
$string['autoclear_userblock_after'] = 'Autoclear blocked users after';
$string['auth_antihammer_messagesettings'] = 'Messaging / notification settings';
$string['auth_antihammer_messagesettings_desc'] = '';
$string['usemessaging'] = 'use messaging API?';
$string['usemessaging_desc'] = 'Check this option if you want the messaging API to be used to inform applicable recipients';
$string['notifymainadmin'] = 'Always notify main administrator?';
$string['notifymainadmin_desc'] = 'Check this option if you always want to notify the main Moodle administrator by e-mail.<br/>
NOTE: The main administrator <i>might</i> receive two notifications if the above option is enabled and they also have
the option to receive messages through email enabled!';
$string['auth_antihammer_miscsettings'] = 'Miscelaneous settings';
$string['auth_antihammer_miscsettings_desc'] = '';
$string['blockpage'] = 'Page to display when someone is blocked';
$string['autocleanlog'] = 'Automatically clean logs?';
$string['autocleanlog_after'] = 'Automatically clear logs older than';
$string['str:blocked:page'] = 'Your account and/or IP address has been blocked.';
$string['log:info:blocked'] = 'Blocked: {$a}';
$string['messageprovider:antihammerblocking'] = 'Notification of IP/account blocking';
$string['auth_antihammer_ipblocksettings'] = 'Add IP blocking to site configuration?';
$string['auth_antihammer_ipblocksettings_desc'] = 'WARNING! You almost never ever want to enable the option below.<br/>
Chances are VERY real you will exclude yourself on this site, especially if you run a network where a lot of people share the same main IP address.<br/>
If, for ANY reason, you really want to enable this option, make sure there\'s at least one IP address specifically allowed, and enable the global option
to allow the allow list to be processed BEFORE the blocking list. Failing to do so can truly lock administrators out.';
$string['addcfgipblock'] = 'Add blocked IP to site configuration';
$string['addcfgipblock_desc'] = 'Check this option if you wish to add a blocked IP to the global list of <a href="{$a}">blocked IP addresses</a>.';
$string['task:logcleaner'] = 'Antihammer log cleaner';
$string['config:autocleanlog:disabled'] = 'Automatic cleaning of antihammer logs is disabled in the global configuration';
$string['err:blocked:ip'] = 'Hammering detected: IP address = {$a->ip} (IP is blocked)';
$string['err:blocked:user'] = 'Hammering detected: Username= {$a->username} IP address = {$a->ip} (IP and/or username is blocked)';

$string['privacy:metadata:auth_antihammer:userid'] = 'The primary key of the Moodle user for which hammering is temporarily logged.';
$string['privacy:metadata:auth_antihammer:username'] = 'The attempted username in Moodle for which hammering is temporarily logged.';
$string['privacy:metadata:auth_antihammer:ip'] = 'IP address of the detected user for which hammering is temporarily logged';
$string['privacy:metadata:auth_antihammer:blocked'] = 'Indication whether or not the user is temporarily blocked.';
$string['privacy:metadata:auth_antihammer:firstattempt'] = 'Time the record was first created.';
$string['privacy:metadata:auth_antihammer:blocktime'] = 'Time until the user is blocked.';
$string['privacy:metadata:auth_antihammer_log:userid'] = 'The primary key of the Moodle user for which hammering is temporarily logged.';
$string['privacy:metadata:auth_antihammer_log:data'] = 'Serialized information stored for this user, which is a serialized version of the antihammer data including the username and IP address.';
$string['privacy:metadata:auth_antihammer_log:datecreated'] = 'Time the record was created.';
$string['privacy:metadata:auth_antihammer'] = 'Auth method antihammer stores (temporary) information related to (hammering) login attempts for user';
$string['privacy:metadata:auth_antihammer_log'] = 'Auth method antihammer stores historical/logging information related to (hammering) login attempts for user';

$string['delete:all'] = 'Remove all records';
$string['plugin:notenabled'] = 'Antihammering plugin is installed but not yet enabled.<br/>
This means none of the functionality of this plugin works at the moment.<br/>
If you want to make use of the blocking capabilities of this plugin, please enable the plugin in the list of authentication plugins in the site administration.
';
$string['action:delete:ipblock'] = 'Remove IP block';
$string['lookup:whatismyip'] = 'Lookup on whatismyipaddress.com';
$string['lookup:iplookup'] = 'IP Lookup (moodle)';
$string['auth_antihammer_repeatoffendersettings'] = 'Repeat offender settings';
$string['auth_antihammer_repeatoffendersettings_desc'] = 'Please note the repeat offender settings is in BETA status.<br/>
When used, this attempts to use a method similar to the OSSEC feature.
';
$string['enablerepeatoffenders'] = 'Enable repeat offenders feature? [BETA]';
$string['enablerepeatoffenders_desc'] = 'This turns the repeat offenders functionality on or off';
$string['title:report:repeatoffenders'] = 'Antihammer repeat offenders';
$string['ap:ro'] = 'Repeat offenders';
$string['action:delete:roitem'] = 'Remove this repeat offenders IP address item';
$string['thead:blockcounter'] = 'Blocked counter';
$string['thead:nextblockduration'] = 'Duration if blocked';
$string['plugin:getremoteaddrconf:notification'] = 'The core configuration indicating how remote IP addresses are detected might be problematic.<br/>
The HTTP Header configuration is lacking HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR or both.<br/>
If your server is behind a reverse proxy this might cause the wrong IP address to be logged.<br/>
To change these settings, please adjust the settings for "getremoteaddrconfig" in the <a href="/admin/settings.php?section=http" target="_new">HTTP settings</a>';
$string['ipwhitelist'] = 'IP Whitelist';
$string['ipwhitelist_desc'] = 'You can configure a list of IP addresses that will <i>not</i> be blocked ever.<br/>
Enter each IP address on a <i>new line</i>.<br/>
These IP addresses will be excluded from antihammer blocking.<br/>For now, we do <i>not</i> support wildcards.<br/>
This is specifically handy in the case where internal networks are in play, for example in schools, universities, etc.
';
$string['ip:whitelist'] = 'Whitelist IP address';
$string['thead:whitelisted'] = 'Whitelisted?';
