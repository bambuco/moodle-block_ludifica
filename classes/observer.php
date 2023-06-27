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
 * Event observer.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica;

/**
 * Events observer.
 *
 * Manage all events related to points and others block elements.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Calculate points when a user complete a course.
     *
     * @param \core\event\base $event
     */
    public static function course_completed(\core\event\base $event) {

        controller::points_completecourse($event->relateduserid, $event->courseid, $event->objectid);

        controller::trigger('course_completed', $event);
    }

    /**
     * Calculate points when a user logged in.
     *
     * @param \core\event\base $event
     */
    public static function user_loggedin(\core\event\base $event) {

        controller::points_recurrentlogin($event->userid);

        controller::trigger('user_loggedin', $event);

    }

    /**
     * Calculate points when a new user is created.
     *
     * @param \core\event\base $event
     */
    public static function user_created(\core\event\base $event) {

        controller::points_usercreated($event->relateduserid);

        controller::trigger('user_created', $event);

    }

    /**
     * Calculate points when a course module is completed.
     *
     * @param \core\event\course_module_completion_updated $event
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {

        $eventdata = $event->get_record_snapshot('course_modules_completion', $event->objectid);

        if ($eventdata->completionstate == COMPLETION_COMPLETE || $eventdata->completionstate == COMPLETION_COMPLETE_PASS) {
            controller::points_completemodule($event->relateduserid,
                                                $event->courseid,
                                                $event->objectid,
                                                $eventdata->coursemoduleid);
        }

        controller::trigger('course_module_completion_updated', $event);
    }

    /**
     * Calculate points when changes his profile with a valid email.
     *
     * @param \core\event\user_updated $event
     */
    public static function user_updated(\core\event\user_updated $event) {

        // Only if a user changes his own profile.
        if ($event->userid == $event->relateduserid) {
            controller::points_userupdated($event->userid);
        }

        controller::trigger('user_updated', $event);
    }

    /**
     * Delete the badge criteria when a badge is deleted.
     *
     * @param \core\event\badge_deleted $event
     * @return void
     */
    public static function badge_deleted(\core\event\badge_deleted $event) {
        global $DB;

        $DB->delete_records('block_ludifica_criteria', ['badgeid' => $event->objectid]);
    }
}
