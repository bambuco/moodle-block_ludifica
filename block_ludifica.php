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
 * Form for editing ludifica block instances.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class containing block base implementation for Ludifica.
 *
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica extends block_base {

    /**
     * Initialice the block.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_ludifica');
    }

    /**
     * Subclasses should override this and return true if the
     * subclass block has a settings.php file.
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Which page types this block may appear on.
     *
     * The information returned here is processed by the
     * {@see blocks_name_allowed_in_format()} function. Look there if you need
     * to know exactly how this works.
     *
     * Default case: everything except mod and tag.
     *
     * @return array page-type prefix => true/false.
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * This function is called on your subclass right after an instance is loaded
     * Use this function to act on instance data just after it's loaded and before anything else is done
     * For instance: if your block will have different title's depending on location (site, course, blog, etc)
     */
    public function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newblocktitle', 'block_ludifica');
        }
    }

    /**
     * Are you going to allow multiple instances of each block?
     * If yes, then it is assumed that the block WILL USE per-instance configuration
     * @return boolean
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Implemented to return the content object.
     *
     * @return stdObject
     */
    public function get_content() {
        global $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        $tabs = [];

        if (isset($this->config) && is_object($this->config)) {

            $tabstoadd = ['tabprofile' => ['profile', 'topbysite', 'topbycourse', 'lastmonth', 'dynamichelps'],
                          'tabtopbycourse' => ['topbycourse'],
                          'tabtopbysite' => ['topbysite'],
                          'tablastmonth' => ['lastmonth'],
                          'dynamichelps' => ['dynamichelps']];

            foreach ($tabstoadd as $property => $newtabs) {

                if (property_exists($this->config, $property) && $this->config->$property) {

                    foreach ($newtabs as $newtab) {
                        if (!in_array($newtab, $tabs)) {
                            $tabs[] = $newtab;
                        }
                    }
                }
            }
        } else {
            $tabs = ['profile', 'topbysite', 'topbycourse', 'lastmonth', 'dynamichelps'];
        }

        if (!empty($this->config->userfieldsranking)) {
            $userfieldsranking = explode(',', $this->config->userfieldsranking);
            foreach ($userfieldsranking as $userfieldranking) {
                $userfieldranking = trim($userfieldranking);
                $inforanking = \block_ludifica\controller::customranking_info($userfieldranking);

                if ($inforanking) {
                    $tabs[] = $inforanking;
                }
            }
        }

        if (in_array('dynamichelps', $tabs)) {
            // Remove dynamichelps in array and add it in the end.
            $key = array_search('dynamichelps', $tabs);
            unset($tabs[$key]);
            $tabs[] = 'dynamichelps';
        }

        if ($this->page->course->id == SITEID && ($key = array_search('topbycourse', $tabs)) !== false) {
            unset($tabs[$key]);
        }

        $html = '';

        // Check we are not trying to load guest's player profile.
        if (isguestuser($USER)) {
            // Can not view info of guest - thre is nothing to see there.
            $html = '';
        } else if (count($tabs) == 0) {
            // Not tabs selected to print.
            $html = '';
        } else {

            $info = null;
            if ($this->page->pagetype == 'user-profile') {
                $userid = optional_param('id', 0, PARAM_INT);
                if ($userid) {
                    $info = new \block_ludifica\player($userid);
                }
            } else {
                $info = new \block_ludifica\player();
            }

            if ($info) {
                // Load templates to display the block content.
                $renderable = new \block_ludifica\output\main($tabs, $info);
                $renderer = $this->page->get_renderer('block_ludifica');
                $html .= $renderer->render($renderable);
                $this->page->requires->js_call_amd('block_ludifica/main', 'init');
            }

        }

        $this->content->text = $html;

        \block_ludifica\controller::include_templatecss();

        return $this->content;
    }

    /**
     * Overridden by the block to prevent the block from being dockable.
     *
     * @return bool
     *
     * Return false as per MDL-64506
     */
    public function instance_can_be_docked() {
        return false;
    }

}
