<?php
//
// This is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for the block
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$settings = new admin_category('block_ludifica_settings', get_string('pluginname', 'block_ludifica'));

$generalsettings = new admin_settingpage('block_ludifica', get_string('generalsettings', 'block_ludifica'));

if ($ADMIN->fulltree) {

    // // Course fields.
    // $name = 'block_ludifica/settingsheaderfields';
    // $heading = get_string('settingsheaderfields', 'block_ludifica');
    // $setting = new admin_setting_heading($name, $heading, '');
    // $generalsettings->add($setting);

    // Duration field.
    $fields = $DB->get_records_menu('customfield_field', null, 'name', 'id, name');
    $name = 'block_ludifica/duration';
    $title = get_string('durationfield', 'block_ludifica');
    $help = get_string('durationfield_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, '', $fields);
    $generalsettings->add($setting);

    // Complete course points.
    $name = 'block_ludifica/pointsbyendcourse';
    $title = get_string('pointsbyendcourse', 'block_ludifica');
    $help = get_string('pointsbyendcourse_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login days.
    $name = 'block_ludifica/recurrentlogindays';
    $title = get_string('recurrentlogindays', 'block_ludifica');
    $help = get_string('recurrentlogindays_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login points.
    $name = 'block_ludifica/pointsbyrecurrentlogin1';
    $title = get_string('pointsbyrecurrentlogin1', 'block_ludifica');
    $help = get_string('pointsbyrecurrentlogin1_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login points by next days.
    $name = 'block_ludifica/pointsbyrecurrentlogin2';
    $title = get_string('pointsbyrecurrentlogin2', 'block_ludifica');
    $help = get_string('pointsbyrecurrentlogin2_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Coins by points.
    $name = 'block_ludifica/coinsbypoints';
    $title = get_string('coinsbypoints', 'block_ludifica');
    $help = get_string('coinsbypoints_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Coins each x points.
    $name = 'block_ludifica/pointstocoins';
    $title = get_string('pointstocoins', 'block_ludifica');
    $help = get_string('pointstocoins_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Levels.
    $name = 'block_ludifica/levels';
    $title = get_string('levels', 'block_ludifica');
    $help = get_string('levels_help', 'block_ludifica');
    $setting = new admin_setting_configtextarea($name, $title, $help, '');
    $generalsettings->add($setting);

}

$settings->add('block_ludifica_settings', $generalsettings);

$externalpage = new admin_externalpage('block_ludifica_avatars', get_string('avatars', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/avatars.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

/*$externalpage = new admin_externalpage('block_ludifica_cards', get_string('cards', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/cards.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);*/

$externalpage = new admin_externalpage('block_ludifica_tickets', get_string('tickets', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/tickets.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

$externalpage = new admin_externalpage('block_ludifica_state', get_string('generalstate', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/state.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);
