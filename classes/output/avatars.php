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
 * Class containing renderers for the block.
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
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class avatars implements renderable, templatable {

    /**
     * @var array avatars list.
     */
    private $avatars;

    /**
     * Constructor.
     *
     * @param array $avatars The avatars list.
     */
    public function __construct($avatars) {

        $this->avatars = $avatars;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $USER, $DB;

        $syscontext = \context_system::instance();
        $hasmanage = has_capability('block/ludifica:manage', $syscontext);

        $player = new \block_ludifica\player($USER->id);

        $dateformat = get_string('strftimedatetimeshort');

        $useravatars = $DB->get_records('block_ludifica_useravatars', array('userid' => $USER->id), null, 'avatarid');

        foreach ($this->avatars as $key => $avatar) {

            if (!$hasmanage && !$avatar->enabled) {
                unset($this->avatars[$key]);
                continue;
            }

            $avatarcore = new \block_ludifica\avatar($avatar);

            // If the user has the avatar.
            $avatar->userhas = isset($useravatars[$avatar->id]);

            // If the user use the avatar.
            $avatar->inuse = $player->general->avatarid == $avatar->id;

            if ($player->general->coins < $avatar->cost && !$avatar->userhas) {
                $avatar->notenabledtext = get_string('notcostcompliance', 'block_ludifica');
                $avatar->enabled = false;
            }

            $avatar->timecreatedformated = userdate($avatar->timecreated, $dateformat);

            $avatar->uri = $avatarcore->get_busturi();
        }

        $uniqueid = \block_ludifica\controller::get_uniqueid();

        $homeurl = get_config('block_ludifica', 'homeurl');

        $defaultvariables = [
            'uniqueid' => $uniqueid,
            'avatars' => array_values($this->avatars),
            'baseurl' => $CFG->wwwroot,
            'canedit' => $hasmanage,
            'storetabs' => \block_ludifica\controller::get_storetabs('avatars'),
            'sesskey' => sesskey(),
            'player' => $player->get_profile(),
            'layoutavatars' => true,
            'myprofile' => true,
            'hashomeurl' => !empty($homeurl),
            'homeurl' => $homeurl,
        ];

        return $defaultvariables;
    }
}
