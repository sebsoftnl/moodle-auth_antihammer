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
 * Renderer class.
 *
 * File         renderer.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_antihammer\output;

use plugin_renderer_base;
use html_writer;
use moodle_url;

/**
 * auth_antihammer_renderer
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Display logs page for administrators
     */
    public function admin_page_logs_overview() {
        $table = new \auth_antihammer\table(\auth_antihammer\table::LOG);
        $table->baseurl = $this->page->url;
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('auth-antihammer-container');
        $out .= html_writer::start_div('auth-antihammer-tabs');
        $out .= $this->admin_tabs('aplog');
        $out .= html_writer::end_div();
        $out .= $this->get_lockoutthreshold_warning();
        ob_start();
        $table->render(25);
        $out .= ob_get_clean();
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display logs page for administrators
     */
    public function admin_page_report_repeatoffenders() {
        $table = new \auth_antihammer\rotable();
        $table->baseurl = $this->page->url;
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('auth-antihammer-container');
        $out .= html_writer::start_div('auth-antihammer-tabs');
        $out .= $this->admin_tabs('roreport');
        $out .= html_writer::end_div();
        $out .= $this->get_lockoutthreshold_warning();
        ob_start();
        $table->render(25);
        $out .= ob_get_clean();
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display detail logs page for administrators
     *
     * @param int $recordid the logmessage record id
     */
    public function admin_page_logs_details($recordid) {
        $record = \auth_antihammer\logmessage::get_by_id($recordid);
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('auth-antihammer-container');
        $out .= html_writer::start_div('auth-antihammer-tabs');
        $out .= $this->admin_tabs('logdetails');
        $out .= html_writer::end_div();
        $out .= $this->get_lockoutthreshold_warning();
        $record->data = unserialize($record->data);
        $datefields = array('datecreated', 'blocktime', 'firstattempt');
        $out .= $this->obj_to_table($record, 1, 5, array('id'), $datefields);
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display report page for administrators
     */
    public function admin_page_report_overview() {
        $table = new \auth_antihammer\table(\auth_antihammer\table::HAMMER);
        $table->baseurl = $this->page->url;
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('auth-antihammer-container');
        $out .= html_writer::start_div('auth-antihammer-tabs');
        $out .= $this->admin_tabs('apreport');
        $out .= html_writer::end_div();
        $out .= $this->get_lockoutthreshold_warning();
        $url = new moodle_url($this->page->url, ['action' => 'deleteall', 'sesskey' => sesskey()]);
        $out .= html_writer::link($url, get_string('delete:all', 'auth_antihammer'), ['class' => 'btn btn-primary']);
        ob_start();
        $table->render(25);
        $out .= ob_get_clean();
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display standard blocked page
     * @return string default blocked page output.
     */
    public function page_blocked() {
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('auth-antihammer-container');
        $out .= get_string('str:blocked:page', 'auth_antihammer');
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Generate navigation tabs
     *
     * @param string $selected selected tab
     * @param array $params any paramaters needed for the base url
     */
    protected function admin_tabs($selected, $params = array()) {
        $config = get_config('auth_antihammer');
        $tabs = array();
        $tabs[] = $this->create_pictab('apreport', 'hammer', 'auth_antihammer',
                new \moodle_url('/auth/antihammer/admin.php', array_merge($params, array('page' => 'apreport'))),
                get_string('ap:report', 'auth_antihammer'));
        $tabs[] = $this->create_pictab('aplog', 'i/report', 'moodle',
                new \moodle_url('/auth/antihammer/admin.php', array_merge($params, array('page' => 'aplog'))),
                get_string('ap:log', 'auth_antihammer'));
        if ($config->enablerepeatoffenders) {
            $tabs[] = $this->create_pictab('roreport', 'i/report', 'moodle',
                    new \moodle_url('/auth/antihammer/admin.php', array_merge($params, array('page' => 'roreport'))),
                    get_string('ap:ro', 'auth_antihammer'));
        }
        if ($selected === 'logdetails') {
            $tabs[] = $this->create_pictab('logdetails', 'details', 'auth_antihammer',
                    new \moodle_url('/auth/antihammer/admin.php',
                    array_merge($params, array('page' => 'aplog', 'action' => 'details'))),
                    get_string('ap:logdetails', 'auth_antihammer'));
        }
        $tabs[] = $this->create_pictab('ahsettings', 'i/settings', 'moodle',
                new \moodle_url('/admin/settings.php', array('section' => 'authsettingantihammer')),
                get_string('pluginname', 'auth_antihammer'));
        return $this->tabtree($tabs, $selected);
    }

    /**
     * Create a tab object with a nice image view, instead of just a regular tabobject
     *
     * @param string $id unique id of the tab in this tree, it is used to find selected and/or inactive tabs
     * @param string $pix image name
     * @param string $component component where the image will be looked for
     * @param string|moodle_url $link
     * @param string $text text on the tab
     * @param string $title title under the link, by defaul equals to text
     * @param bool $linkedwhenselected whether to display a link under the tab name when it's selected
     * @return \tabobject
     */
    protected function create_pictab($id, $pix = null, $component = null, $link = null,
            $text = '', $title = '', $linkedwhenselected = false) {
        $img = '';
        if ($pix !== null) {
            $img = $this->image_url($pix, $component) . ' ';
            $img = '<img src="' . $img . '"';
            if (!empty($title)) {
                $img .= ' alt="' . $title . '"';
            }
            $img .= '/> ';
        }
        return new \tabobject($id, $link, $img . $text, empty($title) ? $text : $title, $linkedwhenselected);
    }

    /**
     * Output a (vertical) table from an object.
     *
     * @param \stdClass $obj the object
     * @param int $depth
     * @param int $maxdepth
     * @param array $skipfields
     * @param array $datefields
     */
    protected function obj_to_table($obj, $depth = 1, $maxdepth = 5, $skipfields = array('id'), $datefields = array()) {
        if ($depth > $maxdepth) {
            return '**RECURSION**';
        }
        $class = (($depth === 1) ? 'class="generaltable"' : '');
        $str = '<table ' . $class . '>';
        foreach ($obj as $k => $v) {
            if (in_array($k, $skipfields)) {
                continue;
            }
            $head = $k;
            if (get_string_manager()->string_exists('thead:' . $k, 'auth_antihammer')) {
                    $head = get_string('thead:' . $k, 'auth_antihammer');
            }
            $str .= '<tr><td>' . $head . '</td>';
            if (is_object($v) || is_array($v)) {
                $str .= '<td>' . $this->obj_to_table($v, $depth + 1, $maxdepth, $skipfields, $datefields) . '</td>';
            } else {
                if (in_array($k, $datefields)) {
                    $v = userdate($v);
                }
                $str .= '<td>' . $v . '</td>';
            }
            $str .= '</tr>';
        }
        $str .= '</table>';
        return $str;
    }

    /**
     * Add waring if Moodle's core functionality and our own user lockout are both set.
     *
     * @return string
     */
    public function get_lockoutthreshold_warning() {
        global $CFG;
        if (((int)$CFG->lockoutthreshold > 0) && (bool)\auth_antihammer\config::get('blockusername')) {
            $link = (new moodle_url('/admin/settings.php', ['section' => 'sitepolicies']))->out(false);
            return \html_writer::div(get_string('warn:moodlelockoutactive', 'auth_antihammer', $link), 'alert alert-warning');
        }
        return '';
    }

}
