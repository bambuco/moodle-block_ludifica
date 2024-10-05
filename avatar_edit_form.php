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
 * Class containing form definition to edit an avatar.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * The form for handling editing a avatar.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica_avatar_edit extends moodleform {

    /**
     * @var object List of local data.
     */
    protected $_data;

    /**
     * Form definition.
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // This contains the data of this form.
        $this->_data = $this->_customdata['data'];

        if ($this->_data) {
            $this->_data->description = ['text' => $this->_data->description];
            if ($this->_data->description == 'null') {
                $this->_data->description = '';
            }
        }

        $editoroptions = [
                            'maxfiles' => 0,
                            'maxbytes' => 0,
                            'enable_filemanagement' => false,
                            'trusttext' => false,
                            'noclean' => false,
                        ];
        $editorattributes = ['rows' => 5, 'cols' => 50];
        $filemanageroptions = $this->_customdata['filemanageroptions'];

        $mform->addElement('header', 'general', get_string('general'));

        $mform->addElement('text', 'name', get_string('name'), 'maxlength="127" size="30"');
        $mform->addRule('name', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('filemanager', 'attachments_filemanager', get_string('avatarbust', 'block_ludifica'),
                                null, $filemanageroptions);

        $mform->addElement('editor', 'description', get_string('description'), $editorattributes, $editoroptions);
        $mform->addRule('description', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');

        $types = \block_ludifica\avatar::get_types();
        $mform->addElement('select', 'type', get_string('avatartype', 'block_ludifica'), $types);

        $mform->addElement('text', 'cost', get_string('cost', 'block_ludifica'), 'maxlength="10" size="10"');
        $mform->setType('cost', PARAM_INT);

        $mform->addElement('textarea', 'sources', get_string('avatarsources', 'block_ludifica'), 'cols="30" rows="10"');
        $mform->setType('sources', PARAM_TEXT);
        $mform->addHelpButton('sources', 'avatarsources', 'block_ludifica');

        $values = ['1' => get_string('yes'), '0' => get_string('no')];
        $mform->addElement('select', 'enabled', get_string('enabled', 'block_ludifica'), $values);

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

        // Finally set the current form data.
        $this->set_data($this->_data);
    }

}
