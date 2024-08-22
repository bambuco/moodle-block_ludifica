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
 * Avatars management page.
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

$player = new \block_ludifica\player();

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/avatars.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('avatars', 'block_ludifica'));
$PAGE->set_title(get_string('avatars', 'block_ludifica'));
$PAGE->requires->js_call_amd('block_ludifica/main', 'init');
$PAGE->requires->js_call_amd('block_ludifica/avatars', 'init', [$USER->id, $player->general->avatarid]);

\block_ludifica\controller::include_templatecss();

$sortavailable = ['name', 'available', 'availabledate', 'cost'];
if (!in_array($sort, $sortavailable)) {
    $sort = 'name';
}

echo $OUTPUT->header();

// Delete an avatar, after confirmation.
if ($hasmanage && $delete && confirm_sesskey()) {
    $avatar = $DB->get_record('block_ludifica_avatars', ['id' => $delete], '*', MUST_EXIST);

    if ($confirm != md5($delete)) {
        $returnurl = new moodle_url('/blocks/ludifica/avatars.php', ['sort' => $sort, 'bypage' => $bypage, 'spage' => $spage]);
        echo $OUTPUT->heading(get_string('avatardelete', 'block_ludifica'));
        $optionsyes = ['delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey()];
        echo $OUTPUT->confirm(get_string('deletecheck', '', "'{$avatar->name}'"),
                                new moodle_url($returnurl, $optionsyes), $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if (data_submitted()) {

        $fs = get_file_storage();
        $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'avatar', $avatar->id);

        foreach ($files as $file) {
            $file->delete();
        }

        $DB->delete_records('block_ludifica_useravatars', ['avatarid' => $avatar->id]);
        $DB->delete_records('block_ludifica_avatars', ['id' => $avatar->id]);
        $DB->set_field('block_ludifica_general', 'avatarid', null, ['avatarid' => $avatar->id]);

        $event = \block_ludifica\event\avatar_deleted::create([
            'objectid' => $avatar->id,
            'context' => $syscontext,
        ]);
        $event->add_record_snapshot('block_ludifica_avatars', $avatar);
        $event->trigger();

        $msg = 'recorddeleted';
    }
}

if (!empty($msg)) {
    $msg = get_string($msg, 'block_ludifica');
    echo $OUTPUT->notification($msg, 'notifysuccess');
}

$conditions = null;
if (!$hasmanage) {
    // ToDo: Not integrated specific user avatars yet.
    $conditions = ['enabled' => 1, 'type' => \block_ludifica\avatar::$defaulttype];
}

$avatars = $DB->get_records('block_ludifica_avatars', $conditions, $sort . ' ASC', '*', $spage * $bypage, $bypage);
$avatarscount = $DB->count_records('block_ludifica_avatars');


$pagingbar = new paging_bar($avatarscount, $spage, $bypage, "/blocks/ludifica/avatars.php?q={$query}&amp;sort={$sort}&amp;");
$pagingbar->pagevar = 'spage';

$renderable = new \block_ludifica\output\avatars($avatars);
$renderer = $PAGE->get_renderer('block_ludifica');

echo $renderer->render($renderable);

echo $OUTPUT->render($pagingbar);

echo $OUTPUT->footer();
