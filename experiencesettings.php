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

use block_onboarding\output\exp_category_table;
use block_onboarding\output\study_table;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/admin_form.php');

require_login();

require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('block_onboarding_experience');
echo $OUTPUT->header();

$mform = new \block_onboarding\admin_form();
$PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');

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
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiencesettings.php");
$fields = 'id, name';
$from = 'mdl_block_onb_e_courses';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);

echo html_writer::div(get_string('edit_categories', 'block_onboarding'), 'h3');
echo html_writer::link(new moodle_url('/blocks/onboarding/experiences/edit_category.php'), $OUTPUT->pix_icon('t/add', 'Add', 'moodle') .
    get_string('link_category', 'block_onboarding'));
$table = new exp_category_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiencesettings.php");
$fields = 'id, name, questions';
$from = 'mdl_block_onb_e_cats';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);

echo $OUTPUT->footer();

