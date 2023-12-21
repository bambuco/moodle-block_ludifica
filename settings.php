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

    // Course points section.
    $name = 'block_ludifica/settingsheaderpointscourse';
    $heading = get_string('settingsheaderpointscourse', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

    // Duration field.
    $fields = [0 => ''];

    $sql = "SELECT f.id, f.name
            FROM {customfield_field} f
            INNER JOIN {customfield_category} fc ON fc.id = f.categoryid AND fc.component = 'core_course' AND fc.area = 'course'";
    $customfields = $DB->get_records_sql($sql);

    if (is_array($fields) && count($fields) > 0) {

        foreach ($customfields as $k => $v) {
            $fields[$k] = format_string($v->name, true);
        }
    }

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

    // Complete course time.
    $name = 'block_ludifica/endcoursedefaulttime';
    $title = get_string('endcoursedefaulttime', 'block_ludifica');
    $help = get_string('endcoursedefaulttime_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Login points.
    $name = 'block_ludifica/settingsheaderpointslogin';
    $heading = get_string('settingsheaderpointslogin', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
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

    // Modules points.
    $name = 'block_ludifica/settingsheaderpointsmodules';
    $heading = get_string('settingsheaderpointsmodules', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

    // End course module points.
    $name = 'block_ludifica/pointsbyendcoursemodule';
    $title = get_string('pointsbyendcoursemodule', 'block_ludifica');
    $help = get_string('pointsbyendcoursemodule_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Point for all course modules.
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $name = 'block_ludifica/pointsbyendallmodules';
    $title = get_string('pointsbyendallmodules', 'block_ludifica');
    $help = get_string('pointsbyendallmodules_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, 0, $choices);
    $generalsettings->add($setting);

    // Other points.
    $name = 'block_ludifica/settingsheaderpointsother';
    $heading = get_string('settingsheaderpointsother', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

    // Create user points.
    $name = 'block_ludifica/pointsbynewuser';
    $title = get_string('pointsbynewuser', 'block_ludifica');
    $help = get_string('pointsbynewuser_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Change to valid email points.
    $name = 'block_ludifica/pointsbychangemail';
    $title = get_string('pointsbychangemail', 'block_ludifica');
    $help = get_string('pointsbychangemail_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Email valid pattern.
    $name = 'block_ludifica/emailvalidpattern';
    $title = get_string('emailvalidpattern', 'block_ludifica');
    $help = get_string('emailvalidpattern_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, '', PARAM_TEXT);
    $generalsettings->add($setting);

    // Email invalid pattern.
    $name = 'block_ludifica/emailinvalidpattern';
    $title = get_string('emailinvalidpattern', 'block_ludifica');
    $help = get_string('emailinvalidpattern_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, '', PARAM_TEXT);
    $generalsettings->add($setting);

    // Coins section.
    $name = 'block_ludifica/settingsheadercoins';
    $heading = get_string('settingsheadercoins', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
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

    // Levels section.
    $name = 'block_ludifica/settingsheaderlevels';
    $heading = get_string('settingsheaderlevels', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

    // Levels.
    $name = 'block_ludifica/levels';
    $title = get_string('levels', 'block_ludifica');
    $help = get_string('levels_help', 'block_ludifica');
    $setting = new admin_setting_configtextarea($name, $title, $help, '');
    $generalsettings->add($setting);

    // Badges section.
    $name = 'block_ludifica/settingsheaderbadges';
    $heading = get_string('settingsheaderbadges', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

    // Social networks.
    $name = 'block_ludifica/networks';
    $title = get_string('socialnetworks', 'block_ludifica');
    $help = get_string('socialnetworks_help', 'block_ludifica');
    $default = get_string('socialnetworks_default', 'block_ludifica');
    $setting = new admin_setting_configtextarea($name, $title, $help, $default);
    $generalsettings->add($setting);

    // Templates section.
    $name = 'block_ludifica/settingsheaderappearance';
    $heading = get_string('settingsheaderappearance', 'block_ludifica');
    $setting = new admin_setting_heading($name, $heading, '');
    $generalsettings->add($setting);

     // Template type.
    $options = ['default' => get_string('none')];

    $path = $CFG->dirroot . '/blocks/ludifica/templates/';
    $files = array_diff(scandir($path), array('..', '.'));

    foreach ($files as $file) {
        if (is_dir($path . $file)) {
            $options[$file] = $file;
        }
    }

    $name = 'block_ludifica/templatetype';
    $title = get_string('templatetype', 'block_ludifica');
    $help = get_string('templatetype_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, 'default', $options);
    $generalsettings->add($setting);

    // Tabs view.
    $options = [
        'default' => get_string('textandicon', 'block_ludifica'),
        'showtext' => get_string('showtext', 'block_ludifica'),
        'showicon' => get_string('showicon', 'block_ludifica'),
        'none' => get_string('none')
    ];

    $name = 'block_ludifica/tabview';
    $title = get_string('tabview', 'block_ludifica');
    $help = get_string('tabview_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, 'default', $options);
    $generalsettings->add($setting);

    $name = 'block_ludifica/homeurl';
    $title = get_string('homeurl', 'block_ludifica');
    $help = get_string('homeurl_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, $CFG->wwwroot, PARAM_URL);
    $generalsettings->add($setting);

    // Acatar information.
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $name = 'block_ludifica/userealinformation';
    $title = get_string('userealinformation', 'block_ludifica');
    $help = get_string('userealinformation_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, 0, $choices);
    $generalsettings->add($setting);
}

$settings->add('block_ludifica_settings', $generalsettings);

$externalpage = new admin_externalpage('block_ludifica_avatars', get_string('avatars', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/avatars.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

$externalpage = new admin_externalpage('block_ludifica_tickets', get_string('tickets', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/tickets.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

$externalpage = new admin_externalpage('block_ludifica_state', get_string('generalstate', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/state.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);
