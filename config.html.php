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
 * Plugin configuration for auth antihammer
 *
 * File         : config.html.php
 * Encoding     : UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
require_once($CFG->dirroot . '/auth/antihammer/auth.php');
$config = auth_plugin_antihammer::config_get_default();
global $OUTPUT;
$image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
        $OUTPUT->pix_url('logo', 'auth_antihammer') . '" /></a>&nbsp;&nbsp;&nbsp;';
$donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
        $OUTPUT->pix_url('donate', 'auth_antihammer') . '" /></a>';
$header = '<div class="block-userrestore-logopromo">' . $image . $donate . '</div>';
?>
<div style="text-align: center"><?php print_string('antihammer', 'auth_antihammer'); ?></div>
<?php echo get_string('promodesc', 'auth_antihammer', $header); ?>
<table cellspacing="0" cellpadding="5" border="0">
    <tr valign="top">
        <td align="right"><?php print_string('auth_antihammer', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="attempts" class="antihammer">attempts</label>
                    </td><td>
                        <select name="attempts" id="attempts">
<?php
for ($i = 0; $i <= 10; $i++) {
    $selected = (($config->attempts == $i) ? ' selected="selected"' : '');
    echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}
?>
                        </select>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('attemptcounter', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="attemptcounter" class="antihammer">attemptcounter</label>
                    </td><td>
                        <input name="attemptcounter" id="attemptcounter" type="text" size="40" value="<?php
echo $config->attemptcounter ?>" />
<?php
if (isset($err['attemptcounter'])) {
    echo $OUTPUT->error_text($err['attemptcounter']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('autoclear_blocked', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="autoclear_blocked" class="antihammer">autoclear_blocked</label>
                    </td><td>
                        <input type="hidden" name="autoclear_blocked" value="0"/>
                        <input name="autoclear_blocked" id="autoclear_blocked" type="checkbox" value="1" <?php
echo ($config->autoclear_blocked ? 'checked="checked"' : ''); ?>/>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('autoclear_after', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="autoclear_after" class="antihammer">autoclear_after</label>
                    </td><td>
                        <input name="autoclear_after" id="autoclear_after" type="text" size="40" value="<?php
echo $config->autoclear_after ?>" />
<?php
if (isset($err['autoclear_after'])) {
    echo $OUTPUT->error_text($err['autoclear_after']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('blockusername', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="blockusername" class="antihammer">blockusername</label>
                    </td><td>
                        <input type="hidden" name="blockusername" value="0"/>
                        <input name="blockusername" id="blockusername" type="checkbox" value="1" <?php
echo ($config->blockusername ? 'checked="checked"' : ''); ?>/>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('blockip', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="blockip" class="antihammer">blockip</label>
                    </td><td>
                        <input type="hidden" name="blockip" value="0"/>
                        <input name="blockip" id="blockip" type="checkbox" value="1" <?php
echo ($config->blockip ? 'checked="checked"' : ''); ?>/>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('blockpage', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="blockpage" class="antihammer">blockpage</label>
                    </td><td>
                        <input name="blockpage" id="blockpage" type="text" size="40" value="<?php
echo $config->blockpage ?>" />
<?php
if (isset($err['blockpage'])) {
    echo $OUTPUT->error_text($err['blockpage']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('blocklangcode', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="blocklangcode" class="antihammer">blocklangcode</label>
                    </td><td>
                        <input name="blocklangcode" id="blocklangcode" type="text" size="40" value="<?php
echo $config->blocklangcode ?>" />
<?php
if (isset($err['blocklangcode'])) {
    echo $OUTPUT->error_text($err['blocklangcode']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('notificationemail', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="notificationemail" class="antihammer">notificationemail</label>
                    </td><td>
                        <input name="notificationemail" id="notificationemail" type="text" size="40" value="<?php
echo $config->notificationemail ?>" />
<?php
if (isset($err['notificationemail'])) {
    echo $OUTPUT->error_text($err['notificationemail']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('notificationemail_fname', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="notificationemail_fname" class="antihammer">notificationemail_fname</label>
                    </td><td>
                        <input name="notificationemail_fname" id="notificationemail_fname" type="text" size="40" value="<?php
echo $config->notificationemail_fname ?>" />
<?php
if (isset($err['notificationemail_fname'])) {
    echo $OUTPUT->error_text($err['notificationemail_fname']);
}
?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

    <tr valign="top">
        <td align="right"><?php print_string('notificationemail_lname', 'auth_antihammer') ?>: </td>
    </tr><tr valign="top">
        <td>
            <table><tr><td style="width:200px">
                        <label for="notificationemail_lname" class="antihammer">notificationemail_lname</label>
                    </td><td>
                        <input name="notificationemail_lname" id="notificationemail_lname" type="text" size="40" value="<?php
echo $config->notificationemail_lname ?>" />
<?php
if (isset($err['notificationemail_lname'])) {
    echo $OUTPUT->error_text($err['notificationemail_lname']);
}
                        ?>
                    </td></tr></table>
        </td>
    </tr>
    <tr valign="top"><td>&nbsp;</td></tr>

</table>