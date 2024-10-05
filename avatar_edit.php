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
 * Avatar edition page.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('avatar_edit_form.php');

$id = optional_param('id', 0, PARAM_INT);

$avatar = null;
if (!empty($id)) {
    $avatar = $DB->get_record('block_ludifica_avatars', ['id' => $id], '*', MUST_EXIST);
}

require_login();

$syscontext = context_system::instance();
require_capability('block/ludifica:manage', $syscontext);

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/avatar_edit.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('avatars', 'block_ludifica'));
$PAGE->set_title(get_string('avatars', 'block_ludifica'));

$filemanageroptions = [
                        'maxbytes' => $CFG->maxbytes,
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => ['.jpg', '.png', '.svg'],
                    ];
$draftitemid = file_get_submitted_draft_itemid('attachments_filemanager');
file_prepare_draft_area($draftitemid, $syscontext->id, 'block_ludifica', 'avatarbust', $id, $filemanageroptions);

if ($avatar) {
    $avatar->attachments_filemanager = $draftitemid;
}

// First create the form.
$editform = new block_ludifica_avatar_edit(null, ['data' => $avatar, 'filemanageroptions' => $filemanageroptions]);
if ($editform->is_cancelled()) {
    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/avatars.php');
    redirect($url);
} else if ($data = $editform->get_data()) {

    if (!$avatar) {
        $avatar = new stdClass();
        $avatar->timecreated = time();
        $avatar->userid = null; // The avatar is not for a specific user.
    }

    $avatar->name = trim($data->name);

    if (is_array($data->description)) {
        $avatar->description = $data->description['text'];
    } else {
        $avatar->description = $data->description;
    }

    $avatar->type = $data->type;
    $avatar->cost = $data->cost;
    $avatar->sources = trim($data->sources);
    $avatar->enabled = $data->enabled;

    if (!empty($avatar->id)) {
        $DB->update_record('block_ludifica_avatars', $avatar);

        $event = \block_ludifica\event\avatar_updated::create([
            'objectid' => $avatar->id,
            'context' => $syscontext,
        ]);
        $event->trigger();
    } else {
        $id = $DB->insert_record('block_ludifica_avatars', $avatar, true);

        $event = \block_ludifica\event\avatar_created::create([
            'objectid' => $id,
            'context' => $syscontext,
        ]);
        $event->trigger();
    }

    file_save_draft_area_files($data->attachments_filemanager, $syscontext->id, 'block_ludifica', 'avatarbust',
                                $id, $filemanageroptions);

    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/avatars.php', ['msg' => 'changessaved']);
    redirect($url);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('avatars', 'block_ludifica'));

$editform->display();

echo $OUTPUT->footer();
