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
 * External integration API
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * External WS lib.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends \external_api {

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function get_ticket_parameters() {
        return new \external_function_parameters(
            [
                'id' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Return a ticket information.
     *
     * @param int $id Ticket id.
     * @return object Ticket information.
     */
    public static function get_ticket($id) {
        global $DB, $USER;

        $ticket = $DB->get_record('block_ludifica_tickets', ['id' => $id], '*', MUST_EXIST);

        if (isloggedin() && !isguestuser()) {
            $ticket->usertickets = $DB->get_records('block_ludifica_usertickets', ['userid' => $USER->id, 'ticketid' => $id]);

            // Hack for external presentation.
            $dateformat = get_string('strftimedatetimeshort');
            foreach ($ticket->usertickets as $uticket) {
                $uticket->timeusedformatted = $uticket->timeused ? userdate($uticket->timeused, $dateformat) : null;
            }
        } else {
            $ticket->usertickets = [];
        }

        return $ticket;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function get_ticket_returns() {
        return new \external_single_structure(
            [
                'id' => new \external_value(PARAM_INT, 'Ticket id'),
                'name' => new \external_value(PARAM_TEXT, 'Ticket name'),
                'description' => new \external_value(PARAM_TEXT, 'Ticket description'),
                'moreinfo' => new \external_value(PARAM_RAW, 'Aditional info'),
                'type' => new \external_value(PARAM_TEXT, 'Ticket type'),
                'cost' => new \external_value(PARAM_INT, 'Ticket cost'),
                'availabledate' => new \external_value(PARAM_INT, 'End time to buy the ticket'),
                'available' => new \external_value(PARAM_INT, 'Total available tickets'),
                'byuser' => new \external_value(PARAM_INT, 'User available tickets'),
                'timecreated' => new \external_value(PARAM_INT, 'Ticket created time'),
                'usertickets' => new \external_multiple_structure(
                    new \external_single_structure(
                        [
                            'id' => new \external_value(PARAM_INT, 'User ticket id'),
                            'userid' => new \external_value(PARAM_INT, 'User id'),
                            'usercode' => new \external_value(PARAM_TEXT, 'Unique user ticket code'),
                            'timecreated' => new \external_value(PARAM_INT, 'Time created'),
                            'timeused' => new \external_value(PARAM_INT, 'Time used, null if not used yet', VALUE_DEFAULT, null),
                            'timeusedformatted' => new \external_value(PARAM_TEXT, 'Time used formatted', VALUE_DEFAULT, null),
                        ],
                        'An user to access the resource'),
                    'User access list', VALUE_DEFAULT, [],
                ),
            ],
            'A ticket info', VALUE_DEFAULT, null
        );
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function buy_ticket_parameters() {
        return new \external_function_parameters(
            [
                'id' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Buy a ticket.
     *
     * @param int $id Ticket id.
     * @return bool True if successful, false in other case.
     */
    public static function buy_ticket($id) {
        global $DB, $USER;

        $ticket = $DB->get_record('block_ludifica_tickets', ['id' => $id], '*', MUST_EXIST);
        $usertickets = $DB->count_records('block_ludifica_usertickets', ['userid' => $USER->id, 'ticketid' => $id]);

        $player = new \block_ludifica\player($USER->id);

        if ($ticket->enabled && (empty($ticket->availabledate) || $ticket->availabledate > time()) && $ticket->available > 0 &&
                $usertickets < $ticket->byuser && $player->general->coins >= $ticket->cost) {

            $usercode = $ticket->code;

            if (empty($usercode)) {
                $usercode = controller::generate_code();
            }

            $ticket->available--;
            $DB->update_record('block_ludifica_tickets', ['id' => $id, 'available' => $ticket->available]);

            $data = new \stdClass();
            $data->userid = $USER->id;
            $data->ticketid = $id;
            $data->infodata = '{}';
            $data->usercode = $usercode;
            $data->timecreated = time();
            $data->timeused = null;
            $DB->insert_record('block_ludifica_usertickets', $data);

            $DB->update_record('block_ludifica_general', [
                                                            'id' => $player->general->id,
                                                            'coins' => $player->general->coins - $ticket->cost,
                                                        ]);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function buy_ticket_returns() {
        return new \external_value(PARAM_BOOL, 'True if ticket was bought');
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function give_ticket_parameters() {
        return new \external_function_parameters(
            [
                'ticketid' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED),
                'contactid' => new \external_value(PARAM_INT, 'Contact id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Give a ticket.
     *
     * @param int $ticketid Ticket id.
     * @param int $contactid Target user id.
     * @return bool True if successful, false in other case.
     */
    public static function give_ticket($ticketid, $contactid) {
        global $DB, $USER;

        $usertickets = $DB->get_records('block_ludifica_usertickets',
                                            ['userid' => $USER->id, 'ticketid' => $ticketid, 'timeused' => null],
                                            '', '*', 0, 1);

        if (count($usertickets) > 0) {

            $ticket = reset($usertickets);
            $ticket->userid = $contactid;
            $DB->update_record('block_ludifica_usertickets', $ticket);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function give_ticket_returns() {
        return new \external_value(PARAM_BOOL, 'True if ticket was given');
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function buy_avatar_parameters() {
        return new \external_function_parameters(
            [
                'id' => new \external_value(PARAM_INT, 'Avatar id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Buy an avatar.
     *
     * @param int $id Avatar id.
     * @return bool True if successful, false in other case.
     */
    public static function buy_avatar($id) {
        global $DB, $USER;

        $avatar = $DB->get_record('block_ludifica_avatars', ['id' => $id], '*', MUST_EXIST);
        $useravatar = $DB->count_records('block_ludifica_useravatars', ['userid' => $USER->id, 'avatarid' => $id]);

        $player = new \block_ludifica\player($USER->id);

        if ($avatar->enabled && $useravatar == 0 && $player->general->coins >= $avatar->cost &&
                $avatar->type == \block_ludifica\avatar::$defaulttype) {

            $data = new \stdClass();
            $data->userid = $USER->id;
            $data->avatarid = $id;
            $data->timecreated = time();
            $DB->insert_record('block_ludifica_useravatars', $data);

            $DB->update_record('block_ludifica_general', [
                                                            'id' => $player->general->id,
                                                            'coins' => $player->general->coins - $avatar->cost,
                                                        ]);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function buy_avatar_returns() {
        return new \external_value(PARAM_BOOL, 'True if avatar was bought');
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function use_avatar_parameters() {
        return new \external_function_parameters(
            [
                'id' => new \external_value(PARAM_INT, 'Avatar id', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Use an avatar as current user image.
     *
     * @param int $id Avatar id.
     * @return bool True if successful, false in other case.
     */
    public static function use_avatar($id) {
        global $DB, $USER;

        $general = $DB->get_record('block_ludifica_general', ['userid' => $USER->id], '*', MUST_EXIST);
        $useravatar = $DB->count_records('block_ludifica_useravatars', ['userid' => $USER->id, 'avatarid' => $id]);

        // Check if user has the avatar.
        if ($useravatar > 0) {

            $general->avatarid = $id;
            $general->timeupdated = time();
            $DB->update_record('block_ludifica_general', $general);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function use_avatar_returns() {
        return new \external_value(PARAM_BOOL, 'True if avatar was assigned to user');
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function get_profile_parameters() {
        return new \external_function_parameters(
            []
        );
    }

    /**
     * Get the current player profile.
     *
     * @return object Current player profile.
     */
    public static function get_profile() {
        global $USER, $PAGE;

        if (!isloggedin() || isguestuser()) {
            return null;
        }

        // Set the context because the player class needs it when use OUTPUT in default_avatar.
        $PAGE->set_context(\context_system::instance());

        $player = new \block_ludifica\player($USER->id);

        $profile = $player->get_profile();

        unset($profile->avatar);

        return $profile;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function get_profile_returns() {
        return new \external_single_structure(
            [
                'fullname' => new \external_value(PARAM_TEXT, 'Player fullname'),
                'points' => new \external_value(PARAM_INT, 'Current points'),
                'coins' => new \external_value(PARAM_INT, 'Current coins'),
                'level' => new \external_single_structure(
                    [
                        'name' => new \external_value(PARAM_TEXT, 'Level name'),
                        'maxpoints' => new \external_value(PARAM_INT, 'Level max points'),
                        'index' => new \external_value(PARAM_INT, 'Level position'),
                    ],
                    'The user level information'),
                'nickname' => new \external_value(PARAM_TEXT, 'Player nickname'),
                'avatarurl' => new \external_value(PARAM_TEXT, 'Player avatar URL'),
            ],
            'General current player info', VALUE_DEFAULT, null
        );
    }
}
