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
 * Form for editing block instances.
 *
 * @package   block_ludifica
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing block instances.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica_edit_form extends block_edit_form {

    /**
     * Block edition form.
     *
     * @param object $mform Parent form.
     */
    protected function specific_definition($mform) {
        global $CFG, $COURSE, $DB;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('customtitle', 'block_ludifica'));
        $mform->setType('config_title', PARAM_TEXT);

        // Tabs.
        $options = [
            '0' => get_string('no'),
            '1' => get_string('yes'),
        ];

        $mform->addElement('select', 'config_tabprofile', get_string('tabprofile', 'block_ludifica'), $options);
        $mform->setDefault('config_tabprofile', 1);
        $mform->addHelpButton('config_tabprofile', 'tabprofile', 'block_ludifica');

        $mform->addElement('select', 'config_tabtopbysite', get_string('tabtopbysite', 'block_ludifica'), $options);
        $mform->setDefault('config_tabtopbysite', 1);

        if ($this->page->course->id != SITEID) {

            $mform->addElement('select', 'config_tabtopbycourse', get_string('tabtopbycourse', 'block_ludifica'), $options);
            $mform->setDefault('config_tabtopbycourse', 1);
        }

        $mform->addElement('select', 'config_tablastmonth', get_string('tablastmonth', 'block_ludifica'), $options);
        $mform->setDefault('config_tablastmonth', 1);

        $mform->addElement('select', 'config_dynamichelps', get_string('dynamichelps', 'block_ludifica'), $options);
        $mform->setDefault('config_dynamichelps', 1);

        $mform->addElement('text', 'config_userfieldsranking', get_string('userfieldsranking', 'block_ludifica'));
        $mform->setType('config_userfieldsranking', PARAM_TEXT);
        $mform->addHelpButton('config_userfieldsranking', 'userfieldsranking', 'block_ludifica');

        $coursemodules = \block_ludifica\controller::get_coursemodules();

        if (count($coursemodules) > 0) {

            $mform->addElement('header', 'configheader_modules', get_string('configheader_modules', 'block_ludifica'));
            $mform->addElement('static', 'configmodules_help', '', get_string('configmodules_help', 'block_ludifica'));

            foreach ($coursemodules as $cm) {
                $content = '<img src="' . $cm->iconurl . '" alt="' . $cm->typetitle . '" title="' .
                                $cm->typetitle . '" class="icon">';

                $content .= ' ' . $cm->name . ' ';
                $content .= '<label>(' . $cm->typetitle . ')</label>';

                $mform->addElement('text', 'config_points_module_' . $cm->id, $content, ['size' => 4]);
                $mform->setType('config_points_module_' . $cm->id, PARAM_INT);
                $mform->setDefault('config_points_module_' . $cm->id, 0);

            }
        }
    }
}
