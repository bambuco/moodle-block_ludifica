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
 * This class contains the changepasswordlink webservice functions.
 *
 * @package    block_ludifica
 * @copyright  2024 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace block_ludifica\external;

use external_api;
use external_function_parameters;
use external_value;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/login/lib.php');

/**
 * Service implementation.
 *
 * @copyright   2024 David Herney - cirano
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class givecoins extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new \external_function_parameters(
            [
                'contactid' => new \external_value(PARAM_INT, 'The user contact id', VALUE_REQUIRED),
                'amount' => new \external_value(PARAM_INT, 'The amount of coins to give', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Returns the transaction information about the give.
     *
     * @param int $contactid The user contact id.
     * @param int $amount The amount of coins to give.
     * @return bool
     */
    public static function execute(int $contactid, int $amount): bool {
        global $PAGE, $DB, $CFG, $USER;

        if (!isloggedin() || isguestuser()) {
            require_login(null, false);
        }

        $syscontext = \context_system::instance();
        $PAGE->set_context($syscontext);

        self::validate_parameters(self::execute_parameters(), [
            'contactid' => $contactid,
            'amount' => $amount,
        ]);

        $contact = $DB->get_record('user', ['id' => $contactid], '*', MUST_EXIST);

        $iscontact = \core_message\api::is_contact((int)$USER->id, $contactid);
        if (isguestuser($contact) || !$iscontact) {
            throw new \moodle_exception('invalidusercontact', 'block_ludifica');
        }

        $player = new \block_ludifica\player($USER->id);
        if ($player->general->coins < $amount) {
            throw new \moodle_exception('insufficientcoins', 'block_ludifica');
        }

        return $player->give_coins($amount, $contactid);

    }

    /**
     * Returns description of method result value.
     *
     * @return external_value
     */
    public static function execute_returns(): external_value {
        return new \external_value(PARAM_BOOL, 'The gift result');
    }
}
