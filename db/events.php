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
 * Ludifica event observer.
 *
 * @package   block_ludifica
 * @category  event
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\course_completed',
        'callback' => 'block_ludifica\observer::course_completed'
    ],
    [
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'block_ludifica\observer::user_loggedin'
    ],
    [
         'eventname' => '\core\event\user_created',
         'callback' => 'block_ludifica\observer::user_created'
    ],
    [
        'eventname' => '\core\event\course_module_completion_updated',
        'callback' => 'block_ludifica\observer::course_module_completion_updated'
    ],
    [
        'eventname'   => '\filter_embedquestion\event\question_attempted',
        'callback'    => 'block_ludifica\observer::question_attempted'
    ]
];
