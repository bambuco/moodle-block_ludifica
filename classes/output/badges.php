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

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badges implements renderable, templatable {

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $USER, $DB;

        $syscontext = \context_system::instance();
        $hasmanage = has_capability('block/ludifica:manage', $syscontext);

        $player = new \block_ludifica\player($USER->id);

        $uniqueid = \block_ludifica\controller::get_uniqueid();

        // Get user badges only in profile tab.

        $sitebadges = badges_get_badges(BADGE_TYPE_SITE);
        $userbadges = badges_get_user_badges($player->general->userid, null);
        $badges = [];
        $userbadgesids = [];

        foreach ($userbadges as $badge) {
            if ($badge->status == BADGE_STATUS_ACTIVE_LOCKED || $badge->status == BADGE_STATUS_INACTIVE_LOCKED) {

                // The badge object from badges_get_user_badges is a stdClass instance.
                $badgeinstance = new \badge($badge->id);

                // Equal symbol encode so it can work in LinkedIn URL.
                $badge->url = (string)(new \moodle_url('/badges/badge.php', ['hash' => $badge->uniquehash]));
                $badge->expire = date('F Y', $badge->dateexpire);
                $badge->year = date('Y', $badge->timecreated);
                $badge->month = date('m', $badge->timecreated);
                $badge->thumbnail = print_badge_image($badgeinstance, $badgeinstance->get_context(), 'normal');

                if ((int)$badge->type == BADGE_TYPE_COURSE) {
                    $badgecourse = $DB->get_record('course', ['id' => $badge->courseid]);
                    $badge->fromcourse = true;
                    $badge->coursename = $badgecourse->fullname;

                    if ($badgecourse->visible) {
                        $badge->courseurl = (string)(new \moodle_url('/badges/view.php', ['id' => $badge->courseid, 'type' => 2]));
                    }
                }

                $badges[] = $badge;
                $userbadgesids[] = $badge->id;
            }
        }
        // End Get user badges.

        // Get unavialable badges.
        foreach ($sitebadges as $badge) {

            $badge->thumbnail = $badge->thumbnail = print_badge_image($badge, $badge->get_context(), 'normal');

            if (in_array($badge->status, [BADGE_STATUS_ACTIVE_LOCKED, BADGE_STATUS_ACTIVE]) &&
                    !in_array($badge->id, $userbadgesids)
                ) {

                $badge->unavailable = 'unavailable';
                $badge->unavailablewarning = get_string('unavailablewarning', 'block_ludifica');
                $badges[] = $badge;

            } else if ($hasmanage && $badge->status == BADGE_STATUS_INACTIVE) {

                // Only can improve the criteria of cohort type.
                $improvecriteria = false;
                foreach ($badge->criteria as $criteria) {
                    if ($criteria->criteriatype == BADGE_CRITERIA_TYPE_COHORT) {
                        $improvecriteria = true;
                    }
                }

                if (!$improvecriteria) {
                    continue;
                }

                $badge->inactive = true;
                $badge->url = (string)(new \moodle_url('/badges/overview.php', ['id' => $badge->id]));
                $badges[] = $badge;

            } else {
                continue;
            }

            if ($hasmanage) {
                // Load current criteria.
                $currentcriteria = $DB->get_records('block_ludifica_criteria', ['badgeid' => $badge->id]);

                if (count($currentcriteria) > 0) {
                    $badge->currentcriteria = array_values($currentcriteria);
                    $badge->hascriteria = true;

                    foreach ($badge->currentcriteria as $criteria) {
                        $criteriacontroller = \block_ludifica\controller::get_badges_improvecriteria($criteria->type);
                        $criteria->label = $criteriacontroller->label($criteria->settings);
                    }
                }
            }
        }

        $homeurl = get_config('block_ludifica', 'homeurl');

        $defaultvariables = [
            'uniqueid' => $uniqueid,
            'baseurl' => $CFG->wwwroot,
            'canedit' => $hasmanage,
            'storetabs' => \block_ludifica\controller::get_storetabs('badges'),
            'sesskey' => sesskey(),
            'player' => $player->get_profile(),
            'layoutbadges' => true,
            'badges' => $badges,
            'myprofile' => true,
            'hashomeurl' => !empty($homeurl),
            'homeurl' => $homeurl,
        ];

        return $defaultvariables;
    }
}
