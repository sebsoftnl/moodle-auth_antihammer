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
 * Settings for auth antihammer
 *
 * File         settings.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $config = get_config('auth_antihammer');
    // Logo.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->image_url('logo', 'auth_antihammer') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->image_url('donate', 'auth_antihammer') . '" /></a>';
    $header = '<div class="auth-antihammer-logopromo">' . $image . $donate . '</div>';
    $settings->add(new admin_setting_heading('auth_antihammer_logopromo',
            get_string('promo', 'auth_antihammer'),
            get_string('promodesc', 'auth_antihammer', $header)));

    // Settings.
    $settings->add(new admin_setting_heading('auth_antihammer_settings', '',
            get_string('auth_antihammerdescription', 'auth_antihammer')));

    $attemptchoices = array();
    for ($i = 1; $i <= 50; $i++) {
        $attemptchoices[$i] = $i;
    }
    // IP blocking.
    $settings->add(new admin_setting_heading('auth_antihammer_ipsettings',
            get_string('auth_antihammer_ipsettings', 'auth_antihammer'),
            get_string('auth_antihammer_ipsettings_desc', 'auth_antihammer')));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/blockip',
            get_string('blockip', 'auth_antihammer'), '', 1, 1, 0));
    $settings->add(new admin_setting_configselect('auth_antihammer/ip_attempts',
            get_string('ip_attempts', 'auth_antihammer'), '', 5, $attemptchoices));
    $settings->add(new admin_setting_configduration('auth_antihammer/ip_attemptcounter',
            get_string('ip_attemptcounter', 'auth_antihammer'), '', 300, 60));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/autoclear_ipblock',
            get_string('autoclear_ipblock', 'auth_antihammer'), '', 1, 1, 0));
    $settings->add(new admin_setting_configduration('auth_antihammer/autoclear_ipblock_after',
            get_string('autoclear_ipblock_after', 'auth_antihammer'), '', 86400, 86400));
    $numentries = isset($config->ipwhitelist) ? count(explode("\n", $config->ipwhitelist)) : 0;
    $settings->add(new admin_setting_configtextarea('auth_antihammer/ipwhitelist',
            get_string('ipwhitelist', 'auth_antihammer'),
            get_string('ipwhitelist_desc', 'auth_antihammer'),
            '',
            PARAM_RAW,
            60, max(10, $numentries + 3)));
    // User blocking.
    $settings->add(new admin_setting_heading('auth_antihammer_usersettings',
            get_string('auth_antihammer_usersettings', 'auth_antihammer'),
            get_string('auth_antihammer_usersettings_desc', 'auth_antihammer')));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/blockusername',
            get_string('blockusername', 'auth_antihammer'), '', 1, 1, 0));
    $settings->add(new admin_setting_configselect('auth_antihammer/attempts',
            get_string('attempts', 'auth_antihammer'), '', 5, $attemptchoices));
    $settings->add(new admin_setting_configduration('auth_antihammer/attemptcounter',
            get_string('attemptcounter', 'auth_antihammer'), '', 300, 60));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/autoclear_userblock',
            get_string('autoclear_userblock', 'auth_antihammer'), '', 1, 1, 0));
    $settings->add(new admin_setting_configduration('auth_antihammer/autoclear_userblock_after',
            get_string('autoclear_userblock_after', 'auth_antihammer'), '', 86400, 86400));
    // Messaging.
    $settings->add(new admin_setting_heading('auth_antihammer_messagesettings',
            get_string('auth_antihammer_messagesettings', 'auth_antihammer'),
            get_string('auth_antihammer_messagesettings_desc', 'auth_antihammer')));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/usemessaging',
            get_string('usemessaging', 'auth_antihammer'),
            get_string('usemessaging_desc', 'auth_antihammer'),
            1));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/notifymainadmin',
            get_string('notifymainadmin', 'auth_antihammer'),
            get_string('notifymainadmin_desc', 'auth_antihammer'),
            1));
    // Misc.
    $settings->add(new admin_setting_heading('auth_antihammer_miscsettings',
            get_string('auth_antihammer_miscsettings', 'auth_antihammer'),
            get_string('auth_antihammer_miscsettings_desc', 'auth_antihammer')));
    $settings->add(new admin_setting_configtext('auth_antihammer/blockpage',
            get_string('blockpage', 'auth_antihammer'), '', '', PARAM_RAW));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/autocleanlog',
            get_string('autocleanlog', 'auth_antihammer'), '', 1, 1, 0));
    $settings->add(new admin_setting_configduration('auth_antihammer/autocleanlog_after',
            get_string('autocleanlog_after', 'auth_antihammer'), '', 30 * 86400, 86400));

    $settings->add(new admin_setting_heading('auth_antihammer_ipblocksettings',
            get_string('auth_antihammer_ipblocksettings', 'auth_antihammer'),
            get_string('auth_antihammer_ipblocksettings_desc', 'auth_antihammer')));
    $blockipsettings = new moodle_url('/admin/settings.php', array('section' => 'ipblocker'));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/addcfgipblock',
            get_string('addcfgipblock', 'auth_antihammer'),
            get_string('addcfgipblock_desc', 'auth_antihammer', $blockipsettings->out()),
            0));

    $settings->add(new admin_setting_heading('auth_antihammer_repeatoffendersettings',
            get_string('auth_antihammer_repeatoffendersettings', 'auth_antihammer'),
            get_string('auth_antihammer_repeatoffendersettings_desc', 'auth_antihammer')));
    $settings->add(new admin_setting_configcheckbox('auth_antihammer/enablerepeatoffenders',
            get_string('enablerepeatoffenders', 'auth_antihammer'),
            get_string('enablerepeatoffenders_desc', 'auth_antihammer'),
            0));

}

if ($hassiteconfig) {
    // Create main category node.
    $node = new admin_category('apantihammer', get_string('pluginname', 'auth_antihammer'));
    // Add category to the admin root (level-1).
    $ADMIN->add('root', $node);
    // Add navigation node(s).
    $ADMIN->add('apantihammer', new admin_externalpage('apreport', get_string('ap:report', 'auth_antihammer'),
            new moodle_url('/auth/antihammer/admin.php', array('page' => 'apreport'))));
    $ADMIN->add('apantihammer', new admin_externalpage('aplog', get_string('ap:log', 'auth_antihammer'),
            new moodle_url('/auth/antihammer/admin.php', array('page' => 'aplog'))));
    $ADMIN->add('apantihammer', new admin_externalpage('roreport', get_string('ap:ro', 'auth_antihammer'),
            new moodle_url('/auth/antihammer/admin.php', array('page' => 'rereport'))));
}
