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
 * Class to manage the ticket information.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;

/**
 * Ticket info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ticket extends entity {

    /**
     * @var string Default ticket type.
     */
    public static $defaulttype = 'normal';

    /**
     * Class constructor.
     *
     * @param int|object $ticket Current ticket data or id.
     */
    public function __construct($ticket = null) {
        global $DB;

        $this->data = null;

        if ($ticket) {

            if (is_object($ticket) && property_exists($ticket, 'id')) {
                $this->data = $ticket;
            } else {
                $this->data = $DB->get_record('block_ludifica_tickets', ['id' => (int)$ticket]);
            }
        }

        if (!$this->data) {
            throw new \moodle_exception('errornotticketdata', 'block_ludifica');
        }
    }

    /**
     * Get the preisualization image.
     *
     * @return string Image URI.
     */
    public function get_thumbnail() {

        $uri = '';
        $syscontext = \context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'ticket', $this->data->id);
        foreach ($files as $file) {
            $filename = $file->get_filename();

            if (!empty($filename) && $filename != '.') {
                $path = '/' . implode('/', [
                                                $file->get_contextid(),
                                                'block_ludifica',
                                                'ticket',
                                                $file->get_itemid() . $file->get_filepath() . $filename,
                                            ]);

                return \moodle_url::make_file_url('/pluginfile.php', $path);

                // Only one image by ticket.
                break;
            }
        }

        return $uri;
    }

    /**
     * List the available ticket types.
     *
     * @return array Tickets type list.
     */
    public static function get_types() {
        return ['default' => get_string('ticketstype_default', 'block_ludifica')];
    }

}
