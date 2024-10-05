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
 * Class containing renderers for validate a ticket.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\output;

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing the validation control.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class validateticket implements renderable, templatable {

    /**
     * @var object Ticket.
     */
    private $ticket;

    /**
     * @var object User ticket.
     */
    private $userticket;

    /**
     * @var object Player user.
     */
    private $user;

    /**
     * @var array Params for use the ticket.
     */
    private $params;

    /**
     * Constructor.
     *
     * @param object $ticket The tickets info.
     * @param object $userticket User tickets list.
     * @param object $user The player info.
     * @param array $params Parameters to use the ticket.
     */
    public function __construct($ticket, $userticket, $user, $params) {

        $this->ticket = $ticket;
        $this->userticket = $userticket;
        $this->user = $user;
        $this->params = $params;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG;

        $this->user->fullname = fullname($this->user);

        $params = http_build_query($this->params);
        $dateformat = get_string('strftimedatetime');

        $defaultvariables = [
            'ticket' => $this->ticket,
            'baseurl' => $CFG->wwwroot,
            'user' => $this->user,
            'params' => $params,
            'used' => $this->userticket->timeused ? userdate($this->userticket->timeused, $dateformat) : null,
        ];

        return $defaultvariables;
    }
}
