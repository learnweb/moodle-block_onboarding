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
 * File to display the course form and process the input.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_course.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('experience_admin', 'block_onboarding'), new moodle_url('admin.php'));
$PAGE->navbar->add(get_string('edit_course', 'block_onboarding'));

// Check if the user has the necessary capability.
if (has_capability('block/onboarding:e_manage_experiences', \context_system::instance())) {
    $PAGE->set_title(get_string('edit_course', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_course', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_course_form.php');

    $courseid = optional_param('course_id', -1, PARAM_INT);
    $delete = optional_param('delete', -1, PARAM_INT);
    $pcourse = new stdClass;
    $pcourse->id = -1;

    if ($courseid != -1) {
        if ($delete == 1) {
            $DB->delete_records('block_onb_e_courses', array('id' => $courseid));
            redirect(new moodle_url('/blocks/onboarding/adminsettings.php'));
        } else {
            // Get the existing data from the Database.
            $pcourse = $DB->get_record('block_onb_e_courses', array('id' => $courseid));
        }
    }
    $mform = new experiences_course_form(null, array('course' => $pcourse));

    if ($mform->is_cancelled()) {
        redirect('admin.php');
    } else {
        if ($fromform = $mform->get_data()) {
            // Processing of data submitted in the form.
            block_onboarding\experiences_lib::edit_course($fromform);
            redirect(new moodle_url('/blocks/onboarding/adminsettings.php'));
        }
    }

    // Display of the form.
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
} else {
    // If the user doesn't have the capability needed an error page is displayed.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
