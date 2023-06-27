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
 * Specialized improve criteria type base.
 *
 * @package   block_ludifica
 * @copyright 2023 David Herney - https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\improvecriteria;

/**
 * Class describing specialized improve criteria.
 *
 * @package   block_ludifica
 * @copyright 2023 David Herney - https://bambuco.co
 */
abstract class base {

    /**
     * Get the unique badge key.
     *
     * @return string
     */
    abstract public static function key() : string;

    /**
     * Get the badge title.
     *
     * @return string
     */
    public function title() : string {
        return '';
    }

    /**
     * Get the improve criteria label.
     *
     * @param object|string $settings Setting data.
     * @return string
     */
    public function label($settings = null) : string {
        return $this->title();
    }

    /**
     * Set the improve criteria setting parameters.
     *
     * @param \MoodleQuickForm $mform Edit form.
     * @param object $data Form data.
     */
    public function settings(\MoodleQuickForm $mform, object $data) : void {
    }

    /**
     * Extract and encode the improve criteria settings.
     *
     * @param object $data Form data.
     * @return string
     */
    public function encode_settings(object $data) : string {
        return '';
    }

    /**
     * Decode the improve criteria settings.
     *
     * @param string $settings Criteria encode settings.
     * @return object|null
     */
    public function decode_settings(string $settings) : ?object {
        return @json_decode($settings);
    }

}
