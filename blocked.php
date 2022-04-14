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
 * Default page to display when people have been blocked
 *
 * File         blocked.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// No login check is expected since this is ONLY a notification page.
// @codingStandardsIgnoreLine
require_once("../../config.php");
$pageurl = new moodle_url('/auth/antihammer/blocked.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($pageurl);
$PAGE->set_heading($SITE->fullname);

$renderer = $PAGE->get_renderer('auth_antihammer');
echo $renderer->page_blocked();
