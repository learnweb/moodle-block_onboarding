<?php
// This file is part of experiences block for Moodle - http://moodle.org/
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
 * File to display the experience form and process the input.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $DB, $USER;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_experience.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('edit_experience', 'block_onboarding'));

$experienceid = optional_param('experience_id', -1, PARAM_INT);
$pexperience = new stdClass;
$pexperience->id = -1;

// Check if the user is blocked.
$checkblocked = $DB->record_exists('block_onb_e_blocked', array('user_id' => $USER->id));
if ($checkblocked == true) {
    // If blocked the user gets an error page.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('blocked', 'block_onboarding'));
    echo $OUTPUT->footer();

} else {
    if ($experienceid != -1) {
        // Get the existing data from the Database.
        $pexperience = $DB->get_record('block_onb_e_exps',
            array('id' => $experienceid), $fields = '*', $strictness = IGNORE_MISSING);
    }

    // Check if there are existing categories and courses.
    $checkcourses = $DB->count_records('block_onb_e_courses');
    $checkcategories = $DB->count_records('block_onb_e_cats');

    if ($checkcourses != 0 || $checkcategories != 0) {

        // Check if the user is allowed to edit the experience.
        if ($experienceid == -1 || $USER->id == $pexperience->user_id ||
            has_capability('block/onboarding:e_manage_experiences', \context_system::instance())) {
            $PAGE->set_title(get_string('edit_experience', 'block_onboarding'));
            $PAGE->set_heading(get_string('edit_experience', 'block_onboarding'));

            require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_experience_form.php');

            $mform = new experiences_experience_form(null, array('experience' => $pexperience));

            if ($mform->is_cancelled()) {
                redirect('overview.php');
            } else {
                if ($fromform = $mform->get_data()) {
                    // Processing of data submitted in the form.
                    block_onboarding\experiences_lib::edit_experience($fromform);
                    redirect('overview.php');
                }
            }

            // Display of the form.
            echo $OUTPUT->header();
            $mform->display();
            echo $OUTPUT->footer();
        } else {
            // If not allowed to edit the user gets an error page.
            $PAGE->set_title(get_string('error', 'block_onboarding'));
            $PAGE->set_heading(get_string('error', 'block_onboarding'));

            echo $OUTPUT->header();
            echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
            echo $OUTPUT->footer();
        }
    } else {
        throw new moodle_exception('error_notenoughdata', 'block_onboarding');
    }
}
