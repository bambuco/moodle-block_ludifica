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
 * Class to manage the player information.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;

/**
 * Player info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class player extends entity {

    /**
     * @var int Default avatar id.
     */
    const DEFAULT_AVATAR = null;

    /**
     * @var string Points when a course is completed by a user.
     */
    const POINTS_TYPE_COURSECOMPLETED = 'coursecompleted';

    /**
     * @var string Points when login a minimum numbers of days.
     */
    const POINTS_TYPE_RECURRENTLOGINBASIC = 'recurrentloginbasic';

    /**
     * @var string Points by recurrent login after basic.
     */
    const POINTS_TYPE_RECURRENTLOGIN = 'recurrentlogin';

     /**
     * @var string Points by new user.
     */
    const POINTS_TYPE_USERCREATED = 'usercreated';

    /**
     * @var string Points when a module is completed by the user.
     */
    const POINTS_TYPE_MODULECOMPLETED = 'modulecompleted';

    /**
     * var string Points by change email.
     */
    const POINTS_TYPE_EMAILCHANGED = 'emailchanged';

    /**
     * @var string Points by recurrent login.
     */
    const COINS_TYPE_BYPOINTS = 'bypoints';

    /**
     * @var int Ranking users.
     */
    const LIMIT_RANKING = 10;

    /**
     * Class constructor.
     *
     * @param int $userid Specific id to load a user. Current logued user is loaded by default.
     */
    public function __construct($userid = null) {
        global $USER, $DB;

        if (!$userid) {
            $this->data = $USER;
        } else {
            $this->data = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
        }

        $general = $DB->get_record('block_ludifica_general', array('userid' => $this->data->id));

        if (!$general) {
            $general = new \stdClass();
            $general->userid = $this->data->id;
            $general->points = 0;
            $general->coins = 0;
            $general->avatarid = self::DEFAULT_AVATAR;
            $general->nickname = '';
            $general->timeupdated = time();
            $general->id = $DB->insert_record('block_ludifica_general', $general, true);
        }

        $this->data->general = $general;

        if ($this->data->general->avatarid) {
            $this->data->avatar = $DB->get_record('block_ludifica_avatars',
                                                    array('id' => $this->data->general->avatarid), '*', MUST_EXIST);
        } else {
            $this->data->avatar = null;
        }
    }

    /**
     * Get the player profile.
     *
     * @return object Profile.
     */
    public function get_profile() {
        global $OUTPUT;

        $info = new \stdClass();
        $info->fullname = fullname($this->data);
        $info->points = $this->data->general->points;
        $info->coins = $this->data->general->coins;
        $info->level = controller::calc_level($this->data->general->points);
        $info->avatar = $this->data->avatar;
        $info->nickname = $this->get_nickname();

        if (!empty($this->data->description)) {
            $usercontext = \context_user::instance($this->data->id);
            $info->description = file_rewrite_pluginfile_urls($this->data->description, 'pluginfile.php', $usercontext->id,
                                                                'user', 'profile', null);
            // ToDo: Validation for format text of info description.
            // Use format_text function with parameters ($this->data->description, $this->data->descriptionformat).
        }

        if ($this->data->avatar) {
            $avatar = new avatar($this->data->avatar);
            $info->avatarurl = $avatar->get_uri($info->level->index);
            $info->avatarbust = $avatar->get_busturi();
        } else {
            $info->avatarurl = avatar::default_avatar();
            $info->avatarbust = $info->avatarurl;
        }

        return $info;
    }

    /**
     * Get the player tickets.
     *
     * @return array Tickets list.
     */
    public function get_tickets() {
        global $OUTPUT, $DB;

        $usertickets = $DB->get_records('block_ludifica_usertickets', array('userid' => $this->data->id));

        $response = array();
        foreach ($usertickets as $one) {

            if (!isset($response[$one->ticketid])) {
                $ticket = new ticket($one->ticketid);
                $info = new \stdClass();
                $info->thumbnail = $ticket->get_thumbnail();
                $info->name = $ticket->name;
                $info->count = 1;
                $response[$one->ticketid] = $info;
            } else {
                $info = $response[$one->ticketid];
                $info->count++;
            }
        }

        return $response;
    }

    /**
     * Get the player displayed nickname.
     *
     * @return string
     */
    public function get_nickname() {

        if (!empty($this->data->general->nickname)) {
            $nickname = $this->data->general->nickname;
        } else {
            $nickname = fullname($this->data);
        }

        return $nickname;
    }

    /**
     * Sum points to the players.
     *
     * @param int $newpoints
     * @param int $courseid
     * @param string $type Points type
     * @param object $infodata Information depend of points type
     * @param int $objectid Other item related with the points.
     */
    public function add_points(int $newpoints, int $courseid, string $type, ?object $infodata = null, ?int $objectid = null) {
        global $DB;

        $totalpoints = $newpoints + $this->data->general->points;
        $timeaction = time();

        // Save the global/total points.
        $DB->update_record('block_ludifica_general', ['id' => $this->data->general->id,
                                                       'points' => $totalpoints,
                                                       'timeupdated' => $timeaction]);

        // Coins earned with new points.
        $this->coinsbypoints($courseid, $newpoints);

        $data = new \stdClass();
        $data->courseid = $courseid;
        $data->userid = $this->data->id;
        $data->type = $type;
        $data->points = $newpoints;
        $data->infodata = json_encode($infodata);
        $data->timecreated = $timeaction;
        $data->objectid = $objectid;

        $DB->insert_record('block_ludifica_userpoints', $data);
    }

    /**
     * Add coins by new points.
     *
     * @param int $courseid
     * @param int $newpoints
     * @return bool True if successful, false in other case.
     */
    public function coinsbypoints(int $courseid, int $newpoints) {
        global $DB;

        $coinsbypoints = intval(get_config('block_ludifica', 'coinsbypoints'));
        $pointstocoins = intval(get_config('block_ludifica', 'pointstocoins'));

        if (empty($coinsbypoints) || empty($pointstocoins)) {
            return false;
        }

        $factorpoints = $newpoints + ($this->data->general->points % $pointstocoins);

        $newcoins = floor($factorpoints / $pointstocoins) * $coinsbypoints;

        // Not new coins by points yet.
        if ($newcoins == 0) {
            return false;
        }

        $totalcoins = $newcoins + $this->data->general->coins;

        // Save the global/total points.
        $DB->update_record('block_ludifica_general', ['id' => $this->data->general->id,
                                                        'coins' => $totalcoins,
                                                        'timeupdated' => time()]);

        $infodata = new \stdClass();
        $infodata->points = $newpoints;
        $infodata->factor = $factorpoints - $newpoints;

        $data = new \stdClass();
        $data->courseid = $courseid;
        $data->userid = $this->data->id;
        $data->type = self::COINS_TYPE_BYPOINTS;
        $data->coins = $newcoins;
        $data->infodata = json_encode($infodata);
        $data->timecreated = time();
        $DB->insert_record('block_ludifica_usercoins', $data);

        return true;
    }

}
