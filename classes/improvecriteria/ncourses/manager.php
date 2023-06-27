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
 * Specialized improve criteria: N finished courses.
 *
 * @package   block_ludifica
 * @copyright 2023 David Herney - https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\improvecriteria\ncourses;

/**
 * Class implement a improve criteria controller.
 *
 * @package   block_ludifica
 * @copyright 2023 David Herney - https://bambuco.co
 */
class manager extends \block_ludifica\improvecriteria\base {

    /**
     * Get the unique improve criteria key.
     *
     * @return string
     */
    public static function key() : string {
        return 'ncourses';
    }

    /**
     * Get the improve criteria title.
     *
     * @return string
     */
    public function title() : string {
        return get_string('improvecriteria_ncourses', 'block_ludifica');
    }

    /**
     * Get the improve criteria label.
     *
     * @param object|string $settings Setting data.
     * @return string
     */
    public function label($settings = null) : string {

        if ($settings) {

            if (is_string($settings)) {
                $settings = @json_decode($settings);
            }

            if ($settings && is_object($settings) && property_exists($settings, 'ncourses')) {
                return get_string('improvecriteria_ncourses_label', 'block_ludifica', $settings->ncourses);
            }
        }

        return get_string('improvecriteria_ncourses', 'block_ludifica');
    }

    /**
     * Set the improve criteria setting parameters.
     *
     * @param \MoodleQuickForm $mform Edit form.
     * @param object $data Form data.
     */
    public function settings(\MoodleQuickForm $mform, object $data) : void {

        // Define control to set the number of courses.
        $mform->addElement('text', 'ncourses', get_string('improvecriteria_ncourses_n', 'block_ludifica'));
        $mform->setType('ncourses', PARAM_INT);
        $mform->addRule('ncourses', null, 'required', null, 'client');
        $mform->addHelpButton('ncourses', 'improvecriteria_ncourses_n', 'block_ludifica');

        if ($data && property_exists($data, 'settings')) {
            $localdata = $this->decode_settings($data->settings);
            if ($localdata && property_exists($localdata, 'ncourses')) {
                $data->ncourses = $localdata->ncourses;
            }
        }
    }

    /**
     * Extract and encode the improve criteria settings.
     *
     * @param object $data Form data.
     * @return string
     */
    public function encode_settings(object $data) : string {
        $settings = new \stdClass();
        $settings->ncourses = property_exists($data, 'ncourses') ? $data->ncourses : 0;
        return json_encode($settings);
    }

    /**
     * Check if the improve criteria is completed.
     *
     * @param \core\event\base $event
     */
    public function event_course_completed(\core\event\base $event) {
        global $DB;

        // Get all the current type improve criteria.
        $improvecriteria = $DB->get_records('block_ludifica_criteria', ['type' => $this->key()]);

        foreach ($improvecriteria as $criterion) {

            try {
                $badge = new \core_badges\badge($criterion->badgeid);
            } catch (\Exception $e) {
                // Maybe the badge was deleted and the delete event not clean the criteria.
                continue;
            }

            if (!isset($badge->criteria[BADGE_CRITERIA_TYPE_COHORT])
                    || !in_array($badge->status, [BADGE_STATUS_ACTIVE, BADGE_STATUS_ACTIVE_LOCKED])) {
                continue;
            }

            $settings = $this->decode_settings($criterion->settings);

            // Check if the improve criteria is enabled. N = 0 courses is equal to disabled.
            if (empty($settings->ncourses)) {
                continue;
            }

            // Check if the improve criteria is completed.
            list($select, $params) = $DB->get_in_or_equal([], SQL_PARAMS_NAMED, 'tc', false, null);
            $select = "userid = :userid AND timecompleted " . $select;
            $params['userid'] = $event->relateduserid;
            $completed = $DB->count_records_select('course_completions', $select, $params);

            // If not completed, continue with the next improve criteria.
            if ($completed < $settings->ncourses) {
                continue;
            }

            $criteria = $badge->criteria[BADGE_CRITERIA_TYPE_COHORT];

            $apply = false;
            foreach ($criteria->params as $param) {
                $cohort = $DB->get_record('cohort', ['id' => $param['cohort']]);

                // Extra check in case a cohort was deleted while badge is still active.
                if (!$cohort) {
                    continue;
                }

                if (cohort_is_member($cohort->id, $event->relateduserid)) {
                    // User is in the cohort, so the improve criteria is applied.
                    continue;
                } else {
                    // Add the user to the cohort.
                    cohort_add_member($cohort->id, $event->relateduserid);
                }
            }
        }
    }
}
