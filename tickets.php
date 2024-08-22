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
 * Tickets management page.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$query = optional_param('q', '', PARAM_TEXT);
$spage = optional_param('spage', 0, PARAM_INT);
$sort = optional_param('sort', 'name', PARAM_TEXT);
$bypage = optional_param('bypage', 20, PARAM_INT);
$msg = optional_param('msg', '', PARAM_TEXT);
$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', '', PARAM_ALPHANUM);   // Md5 confirmation hash.

require_login();

// Redirect if the user is a guest.
if (isguestuser()) {
    $url = new moodle_url($CFG->wwwroot);
    redirect($url);
    die();
}

$syscontext = context_system::instance();
$hasmanage = has_capability('block/ludifica:manage', $syscontext);

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/tickets.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('tickets', 'block_ludifica'));
$PAGE->set_title(get_string('tickets', 'block_ludifica'));
$PAGE->requires->js_call_amd('block_ludifica/main', 'init');
$PAGE->requires->js_call_amd('block_ludifica/tickets', 'init', [$USER->id]);

\block_ludifica\controller::include_templatecss();

$sortavailable = ['name', 'available', 'availabledate', 'cost'];
if (!in_array($sort, $sortavailable)) {
    $sort = 'name';
}

echo $OUTPUT->header();

// Delete a ticket, after confirmation.
if ($hasmanage && $delete && confirm_sesskey()) {
    $ticket = $DB->get_record('block_ludifica_tickets', ['id' => $delete], '*', MUST_EXIST);

    if ($confirm != md5($delete)) {
        $returnurl = new moodle_url('/blocks/ludifica/tickets.php', ['sort' => $sort, 'bypage' => $bypage, 'spage' => $spage]);
        echo $OUTPUT->heading(get_string('ticketdelete', 'block_ludifica'));
        $optionsyes = ['delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey()];
        echo $OUTPUT->confirm(get_string('deletecheck', '', "'{$ticket->name}'"),
                                new moodle_url($returnurl, $optionsyes), $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if (data_submitted()) {

        $fs = get_file_storage();
        $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'ticket', $ticket->id);

        foreach ($files as $file) {
            $file->delete();
        }

        $DB->delete_records('block_ludifica_usertickets', ['ticketid' => $ticket->id]);
        $DB->delete_records('block_ludifica_tickets', ['id' => $ticket->id]);

        $event = \block_ludifica\event\ticket_deleted::create([
            'objectid' => $ticket->id,
            'context' => $syscontext,
        ]);
        $event->add_record_snapshot('block_ludifica_tickets', $ticket);
        $event->trigger();

        $msg = 'recorddeleted';
    }
}

if (!empty($msg)) {
    $msg = get_string($msg, 'block_ludifica');
    echo $OUTPUT->notification($msg, 'notifysuccess');
}

if ($hasmanage) {
    $tickets = $DB->get_records('block_ludifica_tickets', null, $sort . ' ASC', '*', $spage * $bypage, $bypage);
    $ticketscount = $DB->count_records('block_ludifica_tickets');
} else {
    $select = "availabledate >= :availabledate AND available > 0 AND enabled = 1";
    $params = ['availabledate' => time()];
    $tickets = $DB->get_records_select('block_ludifica_tickets', $select, $params, $sort . ' ASC', '*', $spage * $bypage, $bypage);
    $ticketscount = $DB->count_records_select('block_ludifica_tickets', $select, $params);
}

$pagingbar = new paging_bar($ticketscount, $spage, $bypage, "/blocks/ludifica/tickets.php?q={$query}&amp;sort={$sort}&amp;");
$pagingbar->pagevar = 'spage';

$renderable = new \block_ludifica\output\tickets($tickets);
$renderer = $PAGE->get_renderer('block_ludifica');

echo $renderer->render($renderable);

echo $OUTPUT->render($pagingbar);

echo $OUTPUT->footer();
