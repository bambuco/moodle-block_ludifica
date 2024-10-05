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
 * Class containing form definition to edit a ticket.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * The form for handling editing a ticket.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica_ticket_edit extends moodleform {

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
            $this->_data->moreinfo = ['text' => $this->_data->moreinfo];
            if ($this->_data->infodata == 'null') {
                $this->_data->infodata = '';
            }
        }

        $dateattributes = ['stopyear' => date('Y', time()) + 15, 'startyear' => date('Y', time())];
        $editoroptions = [
                            'maxfiles' => 0,
                            'maxbytes' => $CFG->maxbytes,
                            'trusttext' => false,
                            'noclean' => true,
                        ];
        $editorattributes = ['rows' => 5, 'cols' => 50];
        $filemanageroptions = $this->_customdata['filemanageroptions'];

        $mform->addElement('header', 'general', get_string('general'));

        $mform->addElement('text', 'name', get_string('name'), 'maxlength="127" size="30"');
        $mform->addRule('name', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('filemanager', 'attachments_filemanager', get_string('thumbnail', 'block_ludifica'),
                                null, $filemanageroptions);

        $mform->addElement('textarea', 'description', get_string('description'), 'cols="30" rows="5"');
        $mform->addRule('description', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('editor', 'moreinfo', get_string('moreinfo', 'block_ludifica'), $editorattributes, $editoroptions);

        $types = \block_ludifica\ticket::get_types();
        $mform->addElement('select', 'type', get_string('ticketstype', 'block_ludifica'), $types);

        $mform->addElement('text', 'code', get_string('ticketcode', 'block_ludifica'), 'maxlength="31" size="30"');
        $mform->setType('code', PARAM_TEXT);
        $mform->addHelpButton('code', 'ticketcode', 'block_ludifica');

        $mform->addElement('text', 'cost', get_string('cost', 'block_ludifica'), 'maxlength="10" size="10"');
        $mform->setType('cost', PARAM_INT);

        $mform->addElement('date_time_selector', 'availabledate', get_string('ticketavailabledate', 'block_ludifica'),
                            $dateattributes);

        $mform->addElement('text', 'available', get_string('ticketavailable', 'block_ludifica'), 'maxlength="10" size="10"');
        $mform->setType('available', PARAM_INT);

        $mform->addElement('text', 'byuser', get_string('ticketbyuser', 'block_ludifica'), 'maxlength="10" size="10"');
        $mform->setType('byuser', PARAM_INT);

        $values = ['1' => get_string('yes'), '0' => get_string('no')];
        $mform->addElement('select', 'enabled', get_string('enabled', 'block_ludifica'), $values);

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

        // Finally set the current form data.
        $this->set_data($this->_data);
    }

}
