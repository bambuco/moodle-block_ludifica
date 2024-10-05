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
 * Tickets validation page.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('ticket_validate_form.php');

$username = optional_param('u', null, PARAM_TEXT);
$usercode = optional_param('c', null, PARAM_TEXT);
$utid = optional_param('ut', 0, PARAM_INT); // Userticket id.
$confirm = optional_param('confirm', '', PARAM_ALPHANUM); // Md5 confirmation hash.

require_login();

$syscontext = context_system::instance();

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/ticket_validate.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('ticketsvalidate', 'block_ludifica'));
$PAGE->set_title(get_string('ticketsvalidate', 'block_ludifica'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('ticketsvalidate', 'block_ludifica'));

$ticket = null;
$data = null;

if (!empty($confirm) && !empty($utid) && confirm_sesskey()) {

    $userticket = $DB->get_record('block_ludifica_usertickets', ['id' => $utid], '*', MUST_EXIST);

    if ($confirm == md5($utid . $userticket->usercode . $userticket->userid)) {
        $userticket->timeused = time();
        $DB->update_record('block_ludifica_usertickets', $userticket);
        echo $OUTPUT->notification(get_string('ticketused', 'block_ludifica'), 'notifysuccess');
    } else {
        echo $OUTPUT->notification(get_string('ticketnotavailable', 'block_ludifica'));
    }

} else {
    $data = ['username' => $username, 'usercode' => $usercode];
}

// Create the form.
$form = new block_ludifica_ticket_validate(null, ['data' => $data]);

if ($data = $form->get_data()) {

    $user = $DB->get_record('user', ['username' => $data->username, 'mnethostid' => $CFG->mnet_localhost_id], '*', MUST_EXIST);

    $userticket = $DB->get_record('block_ludifica_usertickets', [
                                                                    'usercode' => $data->usercode,
                                                                    'userid' => $user->id,
                                                                ], '*', MUST_EXIST);

    $ticket = $DB->get_record('block_ludifica_tickets', ['id' => $userticket->ticketid], '*', MUST_EXIST);

}

if ($ticket) {
    $params = [
                'sesskey' => sesskey(),
                'confirm' => md5($userticket->id . $userticket->usercode . $userticket->userid),
                'ut' => $userticket->id,
            ];

    $renderable = new \block_ludifica\output\validateticket($ticket, $userticket, $user, $params);
    $renderer = $PAGE->get_renderer('block_ludifica');

    echo $renderer->render($renderable);
}

$form->display();

echo $OUTPUT->footer();
