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
 * Class containing the general controls.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;

/**
 * Component controller.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controller {

    /**
     * @var array Available criteria list.
     */
    private static $improvecriteria;

    /**
     * @var int Instances includes in page request.
     */
    private static $instancescounter = 0;

    /**
     * @var array Available levels list.
     */
    private static $levels = null;

    /**
     * @var bool True if show icons in tabs views.
     */
    private static $showicons = null;

    /**
     * @var bool True if show text in tabs views.
     */
    private static $showtext = null;

    /**
     * @var bool True if show tabs.
     */
    private static $showtabs = null;

    /**
     * Add points to player profile when complete a course.
     *
     * @param int $userid
     * @param int $courseid
     * @param int $completionid
     * @return bool True if points was assigned, false in other case.
     */
    public static function points_completecourse($userid, $courseid, $completionid = null) {
        global $DB;

        $conditions = [
            'userid' => $userid,
            'courseid' => $courseid,
            'type' => player::POINTS_TYPE_COURSECOMPLETED
        ];

        $record = $DB->get_record('block_ludifica_userpoints', $conditions);

        // If exists not add more points.
        if ($record) {
            return false;
        }

        $coursedurationfield = get_config('block_ludifica', 'duration');
        $pointsbycourse = intval(get_config('block_ludifica', 'pointsbyendcourse'));

        if (empty($pointsbycourse)) {
            return false;
        }

        // Default course term.
        $duration = intval(get_config('block_ludifica', 'endcoursedefaulttime'));

        // Check if duration is defined and configured.
        if (!empty($coursedurationfield)) {
            $courseduration = $DB->get_field('customfield_data', 'value', ['fieldid' => $coursedurationfield,
                                                                            'instanceid' => $courseid]);
            $courseduration = intval($courseduration);

            if (!empty($courseduration)) {
                $duration = $courseduration;
            }
        }

        $pointsbycomplete = $pointsbycourse * $duration;

        // Save specific course points.
        $infodata = new \stdClass();
        $infodata->completionid = $completionid;

        $player = new player($userid);
        $player->add_points($pointsbycomplete, $courseid, player::POINTS_TYPE_COURSECOMPLETED, $infodata);

        return true;
    }

    /**
     * Add points to user according her recurrent login.
     *
     * @param int $userid
     * @return bool True if points was assigned, false in other case.
     *
     */
    public static function points_recurrentlogin($userid) {
        global $DB;

        if (isguestuser()) {
            // No points to guest user.
            return false;
        }

        $conflogindays = intval(get_config('block_ludifica', 'recurrentlogindays'));

        if (empty($conflogindays)) {
            return false;
        }

        $conditions = [
            'userid' => $userid,
            'type' => player::POINTS_TYPE_RECURRENTLOGIN
        ];

        // Only get the newest.
        $userpoints = $DB->get_records('block_ludifica_userpoints', $conditions, 'timecreated DESC', '*', 0, 1);

        $recurrentdays = 0;
        $step = 0;
        if (count($userpoints) > 0) {
            $userpoints = reset($userpoints);
            $userpoints->infodata = json_decode($userpoints->infodata);

            $todaytime = strtotime("today");

            if (property_exists($userpoints->infodata, 'lastday')) {

                // Current day processed previously.
                if ($userpoints->infodata->lastday == date('Y-m-d')) {
                    return false;
                }

                // Restart counter if not logged in the last day.
                $recurrentdays = strtotime($userpoints->infodata->lastday) + (24 * 60 * 60) >= strtotime("today") ?
                                    $userpoints->infodata->days : 0;

                $step = $userpoints->infodata->steps;
            }

            // Only create a new record if a new days counter is required (lost consecutive days).
            $newcounter = $recurrentdays == 0 && $userpoints->points > 0;
        } else {
            // Not userpoints record found.
            $newcounter = true;
        }

        if ($newcounter) {
            $infodata = new \stdClass();
            $infodata->lastday = date('Y-m-d');
            $infodata->days = 0;
            $infodata->steps = $step;

            $userpoints = new \stdClass();
            $userpoints->courseid = SITEID;
            $userpoints->userid = $userid;
            $userpoints->type = player::POINTS_TYPE_RECURRENTLOGIN;
            $userpoints->points = 0;
            $userpoints->timecreated = time();
            $userpoints->infodata = json_encode($infodata);

            $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
            $userpoints->infodata = $infodata;
        }

        $points = 0;

        $userpoints->infodata->lastday = date('Y-m-d');
        $userpoints->infodata->days = $recurrentdays + 1;
        $updateuserpoints = false;

        // The minimum required days.
        if ($conflogindays <= $recurrentdays + 1) {

            $player = new player($userid);

            // Points when login a minimum numbers of days.
            if ($conflogindays == $recurrentdays + 1) {

                $points = intval(get_config('block_ludifica', 'pointsbyrecurrentlogin1'));
                $userpoints->infodata->steps++;

                // Save specific points.
                $infodata = new \stdClass();
                $infodata->days = $recurrentdays + 1;
                $infodata->step = $userpoints->infodata->steps;

                $player->add_points($points, SITEID, player::POINTS_TYPE_RECURRENTLOGINBASIC, $infodata);
                $updateuserpoints = true;
            } else {
                // Points after the minimum login days recurrently.
                $points = intval(get_config('block_ludifica', 'pointsbyrecurrentlogin2'));

                $player->add_points($points, SITEID, player::POINTS_TYPE_RECURRENTLOGIN, $userpoints->infodata);
            }
        } else {
            $updateuserpoints = true;
        }

        // Update the current info data of userpoints because not points was assigned for this concept.
        if ($updateuserpoints) {
            $DB->update_record('block_ludifica_userpoints', ['id' => $userpoints->id,
                                                            'infodata' => json_encode($userpoints->infodata)]);
        }

        return true;
    }

    /**
     * Add points to a new user.
     *
     * @param int $userid
     * @return bool True if points was assigned, false in other case.
     */
    public static function points_usercreated($userid) {
        $points = intval(get_config('block_ludifica', 'pointsbynewuser'));

        // Not points for this concept.
        if (empty($points)) {
            return false;
        }

        $player = new player($userid);
        $player->add_points($points, SITEID, player::POINTS_TYPE_USERCREATED);

        return true;
    }

    /**
     * Add points when a user complete a course module.
     *
     * @param int $userid
     * @param int $courseid
     * @param int $completionid Id from course_modules_completion.
     * @param int $cmid Course module completed.
     * @return bool True if points was assigned, false in other case.
     */
    public static function points_completemodule($userid, $courseid, $completionid, $cmid) {
        global $DB;

        $conditions = [
                    'userid' => $userid,
                    'courseid' => $courseid,
                    'objectid' => $cmid,
                    'type' => player::POINTS_TYPE_MODULECOMPLETED
        ];

        $record = $DB->get_record('block_ludifica_userpoints', $conditions);

        // If exists not add points again.
        if ($record) {
            return false;
        }

        $pointsbycoursemodule = intval(get_config('block_ludifica', 'pointsbyendcoursemodule'));
        $allmodules = get_config('block_ludifica', 'pointsbyendallmodules');

        // If is empty this points type are disabled.
        if (!empty($pointsbycoursemodule)) {

            $points = 0;

            $infodata = new \stdClass();
            $infodata->completionid = $completionid;

            if ($allmodules) {
                // Same points for all modules instances.
                $points = $pointsbycoursemodule;
                $infodata->origin = 'allmodules';

            } else {

                $infodata->origin = 'instance';

                $cmconfig = self::get_modulespoints($courseid, $cmid);

                if (isset($cmconfig[$cmid])) {
                    $infodata->instance = $cmconfig[$cmid]->instanceid;
                    $points = $cmconfig[$cmid]->points;
                }
            }

            if (empty($points)) {
                return false;
            }

            $player = new player($userid);
            $player->add_points($points, $courseid, player::POINTS_TYPE_MODULECOMPLETED, $infodata, $cmid);
        }

        return true;
    }

    /**
     * Add points when a user changes the initial asigned email and sets a valid email address.
     *
     * @param int $userid
     * @return bool True if points was assigned, false in other case.
     */
    public static function points_userupdated($userid) {
        global $DB;

        $points = get_config('block_ludifica', 'pointsbychangemail');
        $validpattern = get_config('block_ludifica', 'emailvalidpattern');
        $invalidpattern = get_config('block_ludifica', 'emailinvalidpattern');

        if (empty($points)) {
            return false;
        }

        $conditions = [
            'userid' => $userid,
            'courseid' => SITEID,
            'type' => player::POINTS_TYPE_EMAILCHANGED
        ];

        // If exists not add points again.
        if ($DB->record_exists('block_ludifica_userpoints', $conditions)) {
            return false;
        }

        $useremail = $DB->get_field('user', 'email', ['id' => $userid]);

        // If the email address is not valid, do not assign points.
        // Validate useremail with pattern as a regular expresion.
        if (!empty($invalidpattern)) {
            $pattern = '/' . $invalidpattern . '$/';
            if (preg_match($pattern, $useremail) === 1) {
                return false;
            }
        }

        if (!empty($validpattern)) {
            $pattern = '/' . $validpattern . '$/';
            if (preg_match($pattern, $useremail) !== 1) {
                return false;
            }
        }

        $player = new player($userid);

        $infodata = new \stdClass();
        $infodata->userid = $userid;
        $infodata->email = $useremail;

        $player->add_points($points, SITEID, player::POINTS_TYPE_EMAILCHANGED, $infodata, $userid);

        return true;
    }

    /**
     * Calc current level according the points.
     *
     * @param int $points
     * @return object Level information.
     */
    public static function calc_level($points) {

        $levels = self::get_levels();

        foreach ($levels as $level) {
            if ($level->maxpoints === null || $points <= $level->maxpoints) {
                return $level;
            }
        }

        // Theoretically, this option should not be valid but it is left by control,
        // in case there is a problem in the configuration of the levels.
        $level = new \stdClass();
        $level->name = '';
        $level->maxpoints = 0;
        $level->index = 0;
        return $level;
    }

    /**
     * Get current levels list.
     *
     * @return array Levels list.
     */
    public static function get_levels() {
        if (!self::$levels) {
            self::$levels = [];

            $levels = get_config('block_ludifica', 'levels');

            if (empty($levels)) {
                $level = new \stdClass();
                $level->maxpoints = null;
                $level->name = get_string('defaultlevel', 'block_ludifica');
                $level->index = 0;
                return [$level];
            }

            $lines = explode("\n", $levels);

            foreach ($lines as $key => $line) {
                $fields = explode('|', $line);

                $level = new \stdClass();
                $level->name = trim($fields[0]);
                $level->index = $key;

                if (count($fields) != 2) {
                    // If it is the last line is the maximum level. If not, it is not a valid line.
                    if ($key == (count($lines) - 1)) {
                        $level->maxpoints = null;
                    } else {
                        continue;
                    }
                } else {
                    $level->maxpoints = (int)$fields[1];
                }

                self::$levels[] = $level;
            }

        }

        return self::$levels;
    }

    /**
     * If a ticket is avalilable according the requirement compliance.
     *
     * @param int $userid
     * @param object $ticket
     * @return bool True if is available, false in other case.
     */
    public static function requirements_compliance($userid, $ticket) {
        global $CFG;

        $infodata = is_string($ticket->infodata) ? json_decode($ticket->infodata) : $ticket->infodata;

        if (is_object($infodata) && property_exists($infodata, 'requirements') && is_array($infodata->requirements)) {
            $player = new player($userid);
            foreach ($infodata->requirements as $requirement) {
                if (property_exists($requirement, 'type')) {
                    $fullpath = $CFG->dirroot . '/blocks/ludifica/requirements/' . $requirement->type . '/requirement.php';
                    if (file_exists($fullpath)) {
                        include_once($fullpath);
                        $class = 'block_ludifica\\requirements\\' . $requirement->type;
                        $options = property_exists($requirement, 'options') ? $requirement->options : null;
                        $logic = new $class($options);

                        if (!$logic->compliance($player)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Text to user about the requirement compliance.
     *
     * @param int $userid
     * @param object $ticket
     * @return array Available captions.
     */
    public static function requirements_text($userid, $ticket) {
        global $CFG;

        $captions = array();

        $infodata = is_string($ticket->infodata) ? json_decode($ticket->infodata) : $ticket->infodata;

        if (is_object($infodata) && property_exists($infodata, 'requirements') && is_array($infodata->requirements)) {
            $player = new player($userid);
            foreach ($infodata->requirements as $requirement) {
                if (property_exists($requirement, 'type')) {
                    $fullpath = $CFG->dirroot . '/blocks/ludifica/requirements/' . $requirement->type . '/requirement.php';
                    if (file_exists($fullpath)) {
                        include_once($fullpath);
                        $class = 'block_ludifica\\requirements\\' . $requirement->type;
                        $options = property_exists($requirement, 'options') ? $requirement->options : null;
                        $logic = new $class($options);

                        $captions[] = $logic->caption($player);
                    }
                }
            }
        }

        return $captions;
    }

    /**
     * To generate a random string with a specific length.
     *
     * @param int $len String length.
     * @return string Random string.
     */
    public static function generate_code($len = 10) {
        $word = array_merge(range('a', 'z'), range(0, 9));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    /**
     * Get list of top users in courses by points.
     *
     * @param int $courseid
     * @param bool $includecurrent If include the current user
     * @return array Users list.
     */
    public static function get_topbycourse($courseid, $includecurrent = true) {
        global $DB;
        global $DB, $CFG, $USER;

        $isworkplace = isset($CFG->workplaceproductionstate);

        // If is a Moodle Workplace instance.
        if ($isworkplace) {
            $usertenant = \tool_tenant\tenancy::get_tenant_id($USER->id);
            $sql = " SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                   " FROM {block_ludifica_userpoints} lu " .
                   " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                   " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                   " LEFT JOIN {tool_tenant_user} tu ON tu.userid = lu.userid" .
                   " LEFT JOIN {tool_tenant} t ON t.id = tu.tenantid AND t.archived = 0" .
                   " WHERE t.id = $usertenant AND lu.courseid = :courseid" .
                   " GROUP BY lu.userid, g.nickname" .
                   " ORDER BY points DESC, g.nickname ASC";
        } else {
            $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                    " WHERE lu.courseid = :courseid" .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        }

        $records = $DB->get_records_sql($sql, array('courseid' => $courseid));

        return self::get_toplist($records, $includecurrent);

    }

    /**
     * Get list of top users in site by points.
     *
     * @param bool $includecurrent If include the current user
     * @return array Users list.
     */
    public static function get_topbysite($includecurrent = true) {
        global $DB, $CFG, $USER;

        $isworkplace = isset($CFG->workplaceproductionstate);

        // If is a Moodle Workplace instance.
        if ($isworkplace) {
            $usertenant = \tool_tenant\tenancy::get_tenant_id($USER->id);
            $sql = " SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                    " LEFT JOIN {tool_tenant_user} tu ON tu.userid = lu.userid" .
                    " LEFT JOIN {tool_tenant} t ON t.id = tu.tenantid AND t.archived = 0" .
                    " WHERE t.id = $usertenant" .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        } else {
            $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        }

        $records = $DB->get_records_sql($sql);

        return self::get_toplist($records, $includecurrent);
    }

    /**
     * Get list of top users in a course for the last month.
     *
     * @param int $courseid
     * @param bool $includecurrent If include the current user
     * @return array Users list.
     */
    public static function get_lastmonth($courseid, $includecurrent = true) {
        global $DB, $CFG, $USER;

        $timeinit = strtotime(date('Y-m-01')); // First day of the current month.
        $conditions = ['timeinit' => $timeinit];
        $coursecondition = '';

        if ($courseid != SITEID) {
            $conditions['courseid'] = $courseid;
            $coursecondition = "lu.courseid = :courseid AND ";
        }

        $isworkplace = isset($CFG->workplaceproductionstate);

        // If is a Moodle Workplace instance.
        if ($isworkplace) {
            $usertenant = \tool_tenant\tenancy::get_tenant_id($USER->id);
            $sql = " SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                    " LEFT JOIN {tool_tenant_user} tu ON tu.userid = lu.userid" .
                    " LEFT JOIN {tool_tenant} t ON t.id = tu.tenantid AND t.archived = 0" .
                    " WHERE t.id = $usertenant AND " . $coursecondition . " lu.timecreated >= :timeinit" .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        } else {
            $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0" .
                    " WHERE " . $coursecondition . " lu.timecreated >= :timeinit" .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        }

        $records = $DB->get_records_sql($sql, $conditions);

        return self::get_toplist($records, $includecurrent);
    }

    /**
     * Get list of top users in a course for the last month.
     *
     * @param object $custom Custom ranking information.
     * @param int $courseid
     * @param bool $includecurrent If include the current user
     * @return array Users list.
     */
    public static function get_customranking($custom, $courseid = null, $includecurrent = true) {
        global $DB, $CFG, $USER;

        $conditions = [];
        $tables = [];
        $coursecondition = '';

        if ($courseid != SITEID) {
            $conditions['courseid'] = $courseid;
            $coursecondition = "lu.courseid = :courseid AND ";
        }

        if (property_exists($custom, 'field')) {
            // It is a custom profile field.

            if (!$custom->value === '') {
                // If the field dont exist or is empty, do not show any user.
                return [];
            }

            $tables[] = ' INNER JOIN {user_info_data} uid ON ' .
                        'uid.fieldid = :fieldid AND uid.data = :fieldvalue AND uid.userid = lu.userid ';
            $conditions['fieldid'] = $custom->field->id;
            $conditions['fieldvalue'] = $custom->value;

            $tables[] = " INNER JOIN {user} u ON u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0";

        } else {
            // It is a profile user field.
            $tables[] = ' INNER JOIN {user} u ON u.' . $custom->key . ' = :fieldvalue
                         AND u.id = lu.userid AND u.deleted = 0 AND u.suspended = 0 ';
            $conditions['fieldvalue'] = $custom->value;
        }

        $isworkplace = isset($CFG->workplaceproductionstate);

        // If is a Moodle Workplace instance.
        if ($isworkplace) {
            $usertenant = \tool_tenant\tenancy::get_tenant_id($USER->id);
            $sql = " SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    implode(' ', $tables) .
                    " LEFT JOIN {tool_tenant_user} tu ON tu.userid = lu.userid" .
                    " LEFT JOIN {tool_tenant} t ON t.id = tu.tenantid AND t.archived = 0" .
                    " WHERE t.id = $usertenant AND " . $coursecondition .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        } else {
            $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                    " FROM {block_ludifica_userpoints} lu " .
                    " INNER JOIN {block_ludifica_general} g ON g.userid = lu.userid" .
                    implode(' ', $tables) .
                    (empty($coursecondition) ? '' : " WHERE " . $coursecondition) .
                    " GROUP BY lu.userid, g.nickname" .
                    " ORDER BY points DESC, g.nickname ASC";
        }

        $records = $DB->get_records_sql($sql, $conditions);

        return self::get_toplist($records, $includecurrent);
    }

    /**
     * Get avatar id for a user.
     *
     * @param int $userid
     * @return void
     */
    public static function get_avatar_id($userid) {
        global $DB;

        $records = $DB->get_record('block_ludifica_general', array('userid' => $userid));
        if (!empty($records)) {
            return $records->avatarid;
        } else {
            return null;
        }
    }

    /**
     * Get user profile URL.
     *
     * @param int $userid Get user id.
     * @return \moodle_url User profile URL.
     */
    public static function user_profile_url($userid) {
        global $CFG;

        return new \moodle_url($CFG->wwwroot . '/user/view.php', array('id' => $userid));
    }

    /**
     * Process a list of users for general display.
     *
     * @param array $records Users list.
     * @param bool $includecurrent If include the current user
     * @return array Processed users list.
     */
    private static function get_toplist($records, $includecurrent = true) {
        global $USER, $PAGE;

        $list = [];
        $userealinformation = get_config('block_ludifica', 'userealinformation');

        $k = 0;
        $curentincluded = false;
        foreach ($records as $record) {
            $k++;

            if ($k >= player::LIMIT_RANKING &&
                    ($record->id != $USER->id || !$includecurrent || $curentincluded)) {
                continue;
            }

            $record->position = $k;
            $record->profileurl = self::user_profile_url($record->id)->out();
            $record->avatarprofile = null;
            $avatarid = self::get_avatar_id($record->id);

            if ($userealinformation) {
                $user = \core_user::get_user($record->id);
            }

            if (!empty($avatarid)) {
                $avatar = new \block_ludifica\avatar($avatarid);
                $record->avatarprofile = $avatar->get_busturi();
            } else {

                if ($userealinformation) {
                    // Return the user profile image.
                    $userpicture = new \user_picture($user);

                    if ($userpicture) {
                        $userpicture->size = 'f2';
                        $profileimageurl = $userpicture->get_url($PAGE);
                        $record->avatarprofile = $profileimageurl;
                    }
                }
            }

            if (!$record->avatarprofile) {
                $record->avatarprofile = avatar::default_avatar();
            }

            $list[] = $record;

            if ($record->id == $USER->id) {
                $record->current = true;
                $curentincluded = true;
            }

            if (empty($record->nickname)) {
                if ($record->id == $USER->id || $userealinformation) {

                    if ($record->id == $USER->id) {
                        $user = $USER;
                    }

                    $record->nickname = fullname($user);
                } else {
                    $record->nickname = get_string('nicknameunasined', 'block_ludifica', $record->id);
                }
            }

        }

        return $list;
    }

    /**
     * Get the current store tabs list.
     *
     * @param string $active Name of current active tab.
     * @return array Tabs list.
     */
    public static function get_storetabs($active) {
        $tabs = array();

        $avatars = new \stdClass();
        $avatars->text = get_string('avatars', 'block_ludifica');
        $avatars->title = $avatars->text;
        $avatars->url = new \moodle_url('/blocks/ludifica/avatars.php');
        $avatars->active = $active == 'avatars';
        $tabs[] = $avatars;

        $tickets = new \stdClass();
        $tickets->text = get_string('tickets', 'block_ludifica');
        $tickets->title = $tickets->text;
        $tickets->url = new \moodle_url('/blocks/ludifica/tickets.php');
        $tickets->active = $active == 'tickets';
        $tabs[] = $tickets;

        $badges = new \stdClass();
        $badges->text = get_string('badges', 'block_ludifica');
        $badges->title = $badges->text;
        $badges->url = new \moodle_url('/blocks/ludifica/badges.php');
        $badges->active = $active == 'badges';
        $tabs[] = $badges;

        return $tabs;
    }

    /**
     * Generate a unique id for block instance.
     *
     * @return string Unique identifier.
     */
    public static function get_uniqueid() {
        $uniqueid = 'block_ludifica_' . self::$instancescounter;
        self::$instancescounter++;

        return $uniqueid;
    }

    /**
     * Get a list with all modules for the current course.
     *
     * @return array Course modules.
     */
    public static function get_coursemodules() {
        global $COURSE;

        $coursemodules = array();

        // Points by complete modules not apply in the site level.
        if ($COURSE->id > SITEID && $COURSE->enablecompletion) {

            $pointsbycoursemodule = intval(get_config('block_ludifica', 'pointsbyendcoursemodule'));
            $allmodules = get_config('block_ludifica', 'pointsbyendallmodules');

            if (!empty($pointsbycoursemodule) && !$allmodules) {

                $format = course_get_format($COURSE->id);
                $sections = $format->get_sections();
                $modinfo = get_fast_modinfo($COURSE);
                $context = \context_course::instance($COURSE->id);
                $completioninfo = new \completion_info($COURSE);

                foreach ($sections as $section) {
                    $sectionindex = $section->section;

                    if (isset($COURSE->numsections) && $sectionindex > $COURSE->numsections) {
                        // Support for legacy formats that still provide numsections (see MDL-57769).
                        break;
                    }

                    if (empty($modinfo->sections[$sectionindex])) {
                        continue;
                    }

                    foreach ($modinfo->sections[$sectionindex] as $modnumber) {
                        $module = $modinfo->cms[$modnumber];
                        $iconurl = $module->get_icon_url();
                        $siconurl = s($iconurl);

                        // Exclude labels.
                        if ($module->modname == 'label') {
                            continue;
                        }

                        if ($module->deletioninprogress) {
                            continue;
                        }

                        if ($completioninfo->is_enabled($module) == COMPLETION_TRACKING_NONE) {
                            continue;
                        }

                        if (!$module->visible) {
                            continue;
                        }

                        $thismod = new \stdClass();
                        $thismod->id = $module->id;
                        $thismod->name = format_string($module->name, true, ['context' => $context]);
                        $thismod->type = $module->modname;
                        $thismod->typetitle = get_string('pluginname', $module->modname);
                        $thismod->iconurl = $siconurl;

                        $coursemodules[] = $thismod;
                    }
                }
            }
        }

        return $coursemodules;
    }

    /**
     * Get a list with the points given to each module in an instance.
     *
     * @param int $courseid
     * @param int $cmid Course module completed.
     * @return array Configurations of the first block instance.
     */
    public static function get_modulespoints($courseid, $cmid = null) {
        global $DB;

        $cmconfig = [];

        $context = \context_course::instance($courseid);

        // Get the first block instance in the course.
        $conditions = ['blockname' => 'ludifica', 'parentcontextid' => $context->id];
        $blockinstances = $DB->get_records('block_instances', $conditions, 'timemodified DESC', 'id, configdata');

        $fieldkey = $cmid ? "points_module_" . $cmid : null;

        foreach ($blockinstances as $instance) {

            if (!empty($instance->configdata)) {

                $instanceconfig = unserialize(base64_decode($instance->configdata));

                if ($fieldkey && isset($instanceconfig->{$fieldkey}) && !empty($instanceconfig->{$fieldkey})) {
                    // Use the first instance configuration found. This is the more recently block instance configured.
                    $cmconfig[$cmid] = (object)[
                        'instanceid' => $instance->id,
                        'points' => $instanceconfig->{$fieldkey}
                    ];
                    break;
                } else if (!$fieldkey) {
                    foreach ($instanceconfig as $key => $cmpoints) {

                        if (strpos($key, 'points_module_') === false) {
                            continue;
                        }

                        $cmid = str_replace('points_module_', '', $key);
                        if (!isset($cmconfig[$cmid])) {
                            $cmconfig[$cmid] = (object)[
                                'instanceid' => $instance->id,
                                'points' => $cmpoints
                            ];
                        }
                    }
                }
            }
        }

        return $cmconfig;
    }

    /**
     * Include the CSS file to its corresponding template.
     *
     * @return void
     */
    public static function include_templatecss() {

        global $CFG, $PAGE;

        $template = get_config('block_ludifica', 'templatetype');
        $csspath = $CFG->dirroot . '/blocks/ludifica/templates/' . $template . '/styles.css';

        if ($template != 'default' && file_exists($csspath)) {
            $today = date("Ymd");
            $PAGE->requires->css('/blocks/ludifica/templates/' . $template . '/styles.css?t=' . $today);
        }
    }

    /**
     * Get the available improve criteria for badges.
     *
     * @return array The improve criteria list.
     */
    public static function badges_improvecriteria() : array {
        global $CFG;
        $criteria = [];

        if (!self::$improvecriteria) {
            $directories = glob($CFG->dirroot . '/blocks/ludifica/classes/improvecriteria/*', GLOB_ONLYDIR);

            foreach ($directories as $dir) {
                $slices = explode('/', $dir);
                $name = end($slices);

                if (file_exists($dir . '/manager.php')) {
                    $class = '\\block_ludifica\\improvecriteria\\' . $name . '\\manager';
                    $criteria[$name] = new $class();
                }
            }

            self::$improvecriteria = $criteria;
        } else {
            $criteria = self::$improvecriteria;
        }

        return $criteria;
    }

    /**
     * Get an improve criteria instance by type.
     *
     * @param string $type
     * @return \block_ludifica\improvecriteria\base
     */
    public static function get_badges_improvecriteria(string $type) : \block_ludifica\improvecriteria\base {
        global $CFG;

        $criteria = null;
        $classfile = $CFG->dirroot . '/blocks/ludifica/classes/improvecriteria/' . $type . '/manager.php';
        if (file_exists($classfile)) {
            $class = '\\block_ludifica\\improvecriteria\\' . $type . '\\manager';
            $criteria = new $class();
        }

        return $criteria;
    }

    /**
     * Check if any improve criteria require the event and trigger it.
     *
     * @param string $eventname
     * @param \core\event\base $event
     */
    public static function trigger(string $eventname, \core\event\base $event) : void {

        // Get available criteria to improve.
        $availablecriteria = self::badges_improvecriteria();

        foreach ($availablecriteria as $criteria) {
            $method = 'event_' . $eventname;
            if (method_exists($criteria, $method)) {
                $criteria->{$method}($event);
            }
        }
    }

    /**
     * Get the icont list for views tabs.
     *
     * @return array The icons list.
     */
    public static function get_views_icons() : array {

        $icons = [
            'profile' => 'i/completion_self',
            'topbycourse' => 't/sort_by',
            'topbysite' => 't/award',
            'lastmonth' => 'e/insert_date',
            'dynamichelps' => 'docs'
        ];

        return $icons;
    }

    /**
     * Define if show icons in tabs views.
     *
     * @return bool If show icons.
     */
    public static function show_tabicon() : bool {

        if (self::$showicons !== null) {
            return self::$showicons;
        }

        // Tabs config view.
        $tabview = get_config('block_ludifica', 'tabview');

        self::$showicons = !empty($tabview) ? $tabview !== 'showtext' : false;

        return self::$showicons;
    }

    /**
     * Define if show the text in tabs views.
     *
     * @return bool If show the text.
     */
    public static function show_tabtext() : bool {

        if (self::$showtext !== null) {
            return self::$showtext;
        }

        // Tabs config view.
        $tabview = get_config('block_ludifica', 'tabview');

        self::$showtext = !empty($tabview) ? $tabview !== 'showicon' : false;

        return self::$showtext;
    }

    /**
     * Define if show the text in tabs views.
     *
     * @return bool If show the text.
     */
    public static function show_tabs() : bool {

        if (self::$showtabs !== null) {
            return self::$showtabs;
        }

        // Tabs config view.
        $tabview = get_config('block_ludifica', 'tabview');

        self::$showtabs = !empty($tabview) ? $tabview !== 'none' : true;

        return self::$showtabs;
    }

    /**
     * Get the info for a custom ranking group.
     *
     * @param string $name
     * @return \stdClass The info: icon and title.
     */
    public static function customranking_info(string $name) : ?object {
        global $DB, $USER;

        if (strpos($name, 'profile_field_') !== false) {

            // The 14 is for the prefix 'profile_field_' length.
            $fieldname = substr($name, 14);
            $field = $DB->get_record('user_info_field', ['shortname' => $fieldname]);

            if (!$field) {
                return null;
            }

            $infodata = $DB->get_record('user_info_data', ['fieldid' => $field->id, 'userid' => $USER->id]);

            if (!$infodata || $infodata->data === '') {
                // If the field dont exist or is empty, don't show.
                return null;
            }

            $rankingfield = new \stdClass();
            $rankingfield->icon = 'i/withsubcat';
            $rankingfield->title = $field->name;
            $rankingfield->key = $name;
            $rankingfield->field = $field;
            $rankingfield->value = $infodata->data;
            $rankingfield->labeledvalue = $infodata->data;

            return $rankingfield;
        }

        $userrankingavailables = [
            'institution' => (object)[
                'icon' => 'i/mnethost',
                'title' => get_string('institution'),
            ],
            'department' => (object)[
                'icon' => 't/groups',
                'title' => get_string('department'),
            ],
            'city' => (object)[
                'icon' => 'i/location',
                'title' => get_string('city'),
            ],
            'country' => (object)[
                'icon' => 'i/emojicategoryflags',
                'title' => get_string('country'),
            ],
            'lang' => (object)[
                'icon' => 'e/abbr',
                'title' => get_string('language'),
            ],
        ];

        if (isset($userrankingavailables[$name])) {

            $rankingfield = $userrankingavailables[$name];
            $rankingfield->key = $name;
            $rankingfield->value = $USER->{$name};

            switch ($name) {
                case 'country':
                    $rankingfield->labeledvalue = empty($rankingfield->value) ?
                                                                '' : get_string($rankingfield->value, 'countries');
                    break;
                case 'lang':
                    $langs = get_string_manager()->get_list_of_translations(true);
                    $rankingfield->labeledvalue = isset($langs[$rankingfield->value]) ? $langs[$rankingfield->value] :
                                                                                        $rankingfield->value;
                    break;
                default:
                    $rankingfield->labeledvalue = $USER->{$name};
            }

            return $rankingfield;
        }

        return null;
    }

}
