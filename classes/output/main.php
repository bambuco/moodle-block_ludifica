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
 * Class containing renderers for the block.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/badgeslib.php');

use context_course;
use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {

    /**
     * @var \block_ludifica\player Info about the player.
     */
    private $player;

    /**
     * @var array List of tabs to print.
     */
    private $tabs;

    /**
     * Constructor.
     *
     * @param array $tabs Tabs list to render.
     * @param \block_ludifica\player $player The player user information.
     */
    public function __construct($tabs, $player) {

        $this->player = $player;
        $this->tabs = $tabs;

    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $COURSE, $USER, $OUTPUT, $DB;

        $icons = \block_ludifica\controller::get_views_icons();

        $showtabs = [];
        $customranking = [];
        foreach ($this->tabs as $tab) {

            $one = new \stdClass();

            // Object means that it's a custom tab.
            if (is_object($tab)) {
                $one->title = $tab->title;
                $one->key = $tab->key;
                $one->icon = $output->image_icon($tab->icon, $one->title);
                $customranking[] = $tab;
            } else {
                $one->title = get_string('tabtitle_' . $tab, 'block_ludifica');
                $one->key = $tab;
                $one->icon = $output->image_icon($icons[$tab], $one->title);
            }
            $showtabs[] = $one;
        }

        if (count($showtabs) > 0) {
            $showtabs[0]->active = 'active';
        }

        $activetab = false;

        $uniqueid = \block_ludifica\controller::get_uniqueid();

        // Load config parameters to use in help mustache.
        $globalconfig = get_config('block_ludifica');

        $helpvars = new \stdClass();

        // Fields not used in help.
        $notusedproperties = ['emailvalidpattern', 'emailinvalidpattern', 'levels', 'networks', 'templatetype', 'tabview'];

        foreach ($globalconfig as $key => $val) {

            if (in_array($key, $notusedproperties)) {
                continue;
            }

            $val = intval($val);

            if (!empty($val)) {
                $helpvars->{$key} = $val;
            }
        }

        $helpvars->levels = \block_ludifica\controller::get_levels();
        // End of load config params.

        $levels = [];
        $getprofile = $this->player->get_profile();
        $currentpoints = (int) $getprofile->points;

        for ($i = 0; $i < count($helpvars->levels) - 1; $i++) {

            if ($helpvars->levels[$i]->maxpoints > $currentpoints) {
                $level = new \stdClass();
                $level->name = $helpvars->levels[$i + 1]->name;
                $level->maxpoints = $helpvars->levels[$i]->maxpoints;
                $level->label = get_string('overcomelevel', 'block_ludifica', $level);
                array_push($levels, $level);
            }
        }

        $coursemodules = \block_ludifica\controller::get_coursemodules();
        $cmconfig = \block_ludifica\controller::get_modulespoints($COURSE->id);
        $pointsbymodules = [];
        $insitecontext = true;
        $pointsbyallmodules = false;
        $hasactivities = false;

        if ($COURSE->id > SITEID && $COURSE->enablecompletion) {

            $allmodules = $globalconfig->pointsbyendallmodules;
            $insitecontext = false;

            if (!$allmodules && count($coursemodules) > 0) {

                foreach ($coursemodules as $cm) {

                    if (isset($cmconfig[$cm->id]) && !empty($cmconfig[$cm->id]->points)) {
                        $pointsbymodules[] = $cm;
                        $cm->points = $cmconfig[$cm->id]->points;
                        $hasactivities = true;
                    }
                }
            }

            if ($allmodules) {

                $pointsbyallmodules = true;
            }
        }

        $hasranking = false;

        if (in_array('topbycourse', $this->tabs) ||
            in_array('topbysite', $this->tabs) ||
            in_array('lastmonth', $this->tabs) ||
            count($customranking) > 0) {

            $hasranking = true;
        }

        $pointsbycomplete = new \stdClass();
        $params = ['fieldid' => $globalconfig->duration, 'instanceid' => $COURSE->id];
        $pointsbycomplete->courseduration = $DB->get_field('customfield_data', 'value', $params);

        // Check if duration is defined and configured.
        if (!empty($globalconfig->duration)) {
            if (!empty($pointsbycomplete->courseduration) && !empty($globalconfig->pointsbyendcourse)) {
                $pointsbycomplete->totalpoints = $globalconfig->pointsbyendcourse * (int)$pointsbycomplete->courseduration;
            } else {
                // Not points for this course.
                $pointsbycomplete = null;
            }
        } else if (!empty($globalconfig->pointsbyendcourse)) {
            $pointsbycomplete->totalpoints = $globalconfig->pointsbyendcourse;
        } else {
            // Not points by ending course.
            $pointsbycomplete = null;
        }

        // Hide buttons when it's not the current public user profile.
        $pubicprofileid = optional_param('id', null, PARAM_INT);

        if ($pubicprofileid === null || $pubicprofileid == $USER->id || $COURSE->id !== SITEID) {
            $myprofile = true;
        } else {
            $myprofile = false;
        }

        // Load icons.
        $ticketicon = $OUTPUT->image_url('ticket', 'block_ludifica')->out();
        $avataricon = $OUTPUT->image_url('avatar', 'block_ludifica')->out();
        $badgeicon = $OUTPUT->image_url('award', 'block_ludifica')->out();

        $homeurl = $globalconfig->homeurl;

        $defaultvariables = [
            'uniqueid' => $uniqueid,
            'hastabs' => count($this->tabs) > 1,
            'tabs' => $showtabs,
            'baseurl' => $CFG->wwwroot,
            'layoutgeneral' => true,
            'helpvars' => $helpvars,
            'pointsbymodules' => $pointsbymodules,
            'insitecontext' => $insitecontext,
            'hasactivities' => $hasactivities,
            'pointsbyallmodules' => $pointsbyallmodules,
            'levels' => $levels,
            'haslevels' => count($levels) > 0,
            'hasranking' => $hasranking,
            'hasduration' => !empty($globalconfig->duration),
            'pointsbycomplete' => $pointsbycomplete,
            'myprofile' => $myprofile,
            'ticketicon' => $ticketicon,
            'avataricon' => $avataricon,
            'badgeicon' => $badgeicon,
            'showtabs' => \block_ludifica\controller::show_tabs(),
            'showicon' => \block_ludifica\controller::show_tabicon(),
            'showtext' => \block_ludifica\controller::show_tabtext(),
            'iconsonly' => \block_ludifica\controller::show_tabicon() && !\block_ludifica\controller::show_tabtext(),
            'incourse' => $COURSE->id !== SITEID ? 'incourse' : '',
            'hashomeurl' => !empty($homeurl),
            'homeurl' => $homeurl,
        ];

        if (in_array('profile', $this->tabs)) {

            // Get user badges only in profile tab.
            $userbadges = badges_get_user_badges($this->player->general->userid, null);
            $badges = [];

            foreach ($userbadges as $badge) {

                if ($badge->status == '3') {

                    if ($badge->courseid) {
                        $instancecontext = \context_course::instance($badge->courseid);
                        $source = $instancecontext->id;
                    } else {
                        $source = SITEID;
                    }

                    $badge->url = urldecode((string)(new \moodle_url('/badges/badge.php', ['hash' => $badge->uniquehash])));
                    $badge->thumbnail = \moodle_url::make_pluginfile_url($source,
                                                                            'badges', 'badgeimage', $badge->id, '/', 'f3', false);

                    $badges[] = $badge;
                }
            }

            // Only show the first 4 badges.
            $showbadges = array_slice($badges, 0, 4);
            $morebadges = false;

            if (count($badges) > 4) {
                $morebadges = true;
            }

            $defaultvariables['badges'] = $showbadges;
            $defaultvariables['morebadges'] = $morebadges;
            // End Get user badges.

            $nickname = $this->player->get_nickname();
            $ownprofile = $this->player->general->userid == $USER->id;

            if ($ownprofile) {
                $tmpl = new \core\output\inplace_editable('block_ludifica', 'nickname', $this->player->general->id,
                    has_capability('moodle/user:editownprofile', \context_system::instance()),
                    format_string($nickname), $nickname, get_string('editnickname', 'block_ludifica'),
                    get_string('newnickname', 'block_ludifica', format_string($nickname)));
                    $nickcontent = $OUTPUT->render($tmpl);
                    $defaultvariables['tickets'] = array_values($this->player->get_tickets());
            } else {
                $nickcontent = $nickname;
            }

            $defaultvariables['nickcontent'] = $nickcontent;
            $defaultvariables['player'] = $getprofile;
            $defaultvariables['profilestate'] = 'active';
            $defaultvariables['ownprofile'] = $ownprofile;
            $activetab = true;
        }

        if (in_array('topbycourse', $this->tabs)) {
            $defaultvariables['hastopbycourse'] = true;
            $defaultvariables['topbycourse'] = array_values(\block_ludifica\controller::get_topbycourse($COURSE->id));
            $defaultvariables['hasrowstopbycourse'] = count($defaultvariables['topbycourse']) > 0;
            $defaultvariables['topbycoursestate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        if (in_array('topbysite', $this->tabs)) {
            $defaultvariables['hastopbysite'] = true;
            $defaultvariables['topbysite'] = array_values(\block_ludifica\controller::get_topbysite());
            $defaultvariables['hasrowstopbysite'] = count($defaultvariables['topbysite']) > 0;
            $defaultvariables['topbysitestate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        if (in_array('lastmonth', $this->tabs)) {
            $defaultvariables['haslastmonth'] = true;
            $defaultvariables['lastmonth'] = array_values(\block_ludifica\controller::get_lastmonth($COURSE->id));
            $defaultvariables['hasrowslastmonth'] = count($defaultvariables['lastmonth']) > 0;
            $defaultvariables['lastmonthstate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        if (count($customranking) > 0) {
            $defaultvariables['hascustomranking'] = true;

            $defaultvariables['customranking'] = [];
            foreach ($customranking as $custom) {
                $rankingrows = array_values(\block_ludifica\controller::get_customranking($custom, $COURSE->id));
                $defaultvariables['customranking'][] = (object) [
                    'title' => empty($custom->value) ? get_string('ranking_custom_empty', 'block_ludifica', $custom->title) :
                                                    get_string('ranking_custom_value', 'block_ludifica', (object)[
                                                        'type' => $custom->title,
                                                        'value' => $custom->labeledvalue,
                                                    ]),
                    'key' => $custom->key,
                    'rows' => $rankingrows,
                    'hasrows' => count($rankingrows),
                    'state' => !$activetab ? 'active' : '',
                ];
                $activetab = true;
            }

        }

        if (in_array('dynamichelps', $this->tabs)) {
            $defaultvariables['hasdynamichelps'] = true;
            $defaultvariables['dynamichelpsstate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        return $defaultvariables;
    }
}
