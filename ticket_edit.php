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
 * Ticket edition page.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('ticket_edit_form.php');

$id = optional_param('id', 0, PARAM_INT);

$ticket = null;
if (!empty($id)) {
    $ticket = $DB->get_record('block_ludifica_tickets', ['id' => $id], '*', MUST_EXIST);
}

require_login();

$syscontext = context_system::instance();
require_capability('block/ludifica:manage', $syscontext);

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/ticket_edit.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('tickets', 'block_ludifica'));
$PAGE->set_title(get_string('tickets', 'block_ludifica'));

$filemanageroptions = [
                        'maxbytes' => $CFG->maxbytes,
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => ['.jpg', '.png', '.svg'],
                    ];
$draftitemid = file_get_submitted_draft_itemid('attachments_filemanager');
file_prepare_draft_area($draftitemid, $syscontext->id, 'block_ludifica', 'ticket', $id, $filemanageroptions);

if ($ticket) {
    $ticket->attachments_filemanager = $draftitemid;
}

// First create the form.
$editform = new block_ludifica_ticket_edit(null, ['data' => $ticket, 'filemanageroptions' => $filemanageroptions]);
if ($editform->is_cancelled()) {
    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/tickets.php');
    redirect($url);
} else if ($data = $editform->get_data()) {

    if (!$ticket) {
        $ticket = new stdClass();
        $ticket->timecreated = time();
    }

    $ticket->name = $data->name;

    if (is_array($data->description)) {
        $ticket->description = $data->description['text'];
    } else {
        $ticket->description = $data->description;
    }

    if (is_array($data->moreinfo)) {
        $ticket->moreinfo = $data->moreinfo['text'];
    } else {
        $ticket->moreinfo = $data->moreinfo;
    }

    $ticket->type = $data->type;
    $ticket->code = $data->code;
    $ticket->cost = $data->cost;
    $ticket->availabledate = !empty($data->availabledate) ? $data->availabledate : 0;
    $ticket->available = $data->available;
    $ticket->byuser = $data->byuser;
    $ticket->enabled = $data->enabled;

    // Check by correct json format.
    $ticket->infodata = property_exists($data, 'infodata') ? json_encode(json_decode($data->infodata)) :
                        (property_exists($ticket, 'infodata') ? $ticket->infodata : '');


    if (!empty($ticket->id)) {
        $DB->update_record('block_ludifica_tickets', $ticket);

        $event = \block_ludifica\event\ticket_updated::create([
            'objectid' => $ticket->id,
            'context' => $syscontext,
        ]);
        $event->trigger();
    } else {
        $id = $DB->insert_record('block_ludifica_tickets', $ticket, true);

        $event = \block_ludifica\event\ticket_created::create([
            'objectid' => $id,
            'context' => $syscontext,
        ]);
        $event->trigger();
    }

    file_save_draft_area_files($data->attachments_filemanager, $syscontext->id, 'block_ludifica', 'ticket',
                                $id, $filemanageroptions);

    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/tickets.php', ['msg' => 'changessaved']);
    redirect($url);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('tickets', 'block_ludifica'));

$editform->display();

echo $OUTPUT->footer();
