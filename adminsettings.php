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
 * Settings for the onboarding block.
 *
 * @package block_onboarding
 * @copyright 2021 N. Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_onboarding\output\category_table;
use block_onboarding\output\steps_table;
use block_onboarding\output\study_table;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/admin_form.php');

require_login();

require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('block_onboarding');
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings', 'block_onboarding'));

$mform = new \block_onboarding\admin_form();

// Form is submitted.
if ($data = $mform->get_data()) {
    global $DB;
    // Adds single or multiple courses of studies seperated by semicolons.
    if(isset($data->{"csvofstudies"})){
        $cleanedtext = str_replace(array("\n", "\r"), '', $data->{"csvofstudies"});
        $studies = explode ( ';', $cleanedtext);
        foreach ($studies as $singlestudy) {
            if (strlen($singlestudy) > 0){
                $exist = $DB->get_records('block_onb_e_courses', array('name' => $singlestudy));
                if (!empty($exist)) {
                    echo $singlestudy . ' was not added as it already exists. <br>';
                } else {
                    $study = new stdClass();
                    $study->name = $singlestudy;
                    $study->timecreated = time();
                    $study->timemodified = time();
                    $DB->insert_record('block_onb_e_courses', $study);
                }
            }
        }
    }
}

if (empty($entry->id)) {
    $entry = new stdClass;
    $entry->id = 0;
}
echo html_writer::div('Edit Courses of Study', 'h3');

$mform->set_data($entry);
$mform->display();
echo html_writer::div('Edit Courses of Studies', 'h3');
$table = new study_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/adminsettings.php");
$fields = 'id, name';
$from = 'mdl_block_onb_e_courses';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);

echo html_writer::div('Edit Steps', 'h3');
echo html_writer::link(new moodle_url('/blocks/onboarding/guide/edit_step.php'), '<div class="icon fa fa-plus fa-fw "></div>' . 'Step');
$table = new steps_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/adminsettings.php");
$fields = 'id, name, description, position, achievement';
$from = 'mdl_block_onb_s_steps';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);
echo html_writer::div('Edit Categories', 'h3');
echo html_writer::link(new moodle_url('/blocks/onboarding/guide/edit_category.php'), '<div class="icon fa fa-plus fa-fw "></div>' . 'Category');
$table = new category_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/adminsettings.php");
$fields = 'id, name, position';
$from = 'mdl_block_onb_w_categories';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);
echo $OUTPUT->footer();

