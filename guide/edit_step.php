<?php
// This file is part of steps block for Moodle - http://moodle.org/
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
 * Steps editing form for administrators.
 *
 * This page contains a form element to edit existing steps or create new steps.
 * Utilizes {@see \block_onboarding\steps_lib} to provide the required functionality.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

// Initializes the page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/steps/edit_step.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('guide', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('steps_admin', 'block_onboarding'), new moodle_url('admin_steps.php'));
$PAGE->navbar->add(get_string('edit_step', 'block_onboarding'));

// Checks whether user holds the required capability for managing steps.
if (has_capability('block/onboarding:s_manage_steps', $context)) {
    // Initializes page title and heading.
    $PAGE->set_title(get_string('edit_step', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_step', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/steps_step_form.php');

    // Retrieves optional URL parameter to determine whether a new step is added or an existing step is edited.
    // The URL variable refers to the step id which is -1 when a new step is to be added to the database.
    $stepid = optional_param('step_id', -1, PARAM_INT);

    // Checks whether the step id refers to an existing step and retrieves the step from the database if this is the case.
    // Otherwise a step id of -1 is used, which will be further processed in the First Steps library.
    $paramstep = new stdClass();
    $paramstep->id = -1;
    if ($stepid != -1) {
        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
    }

    // Creates editing form with the temporary step object.
    $mform = new steps_step_form(null, array('step' => $paramstep));
    if ($mform->is_cancelled()) {
        // Redirects to First Steps administration section when editing process is canceled.
        redirect('admin_steps.php');
    } else {
        // Utilizes related steps library method and redirects to First Steps administration section when editing form is submitted.
        if ($fromform = $mform->get_data()) {
            \block_onboarding\steps_lib::edit_step($fromform);
            redirect('admin_steps.php');
        }
    }

    // Defines the page output.
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();

} else {
    // Initializes page title and heading in case permissions are insufficient.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    // Defines the page output.
    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
