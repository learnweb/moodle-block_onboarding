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
 * File to display the report experience prompt.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $CFG, $DB, $USER;

$context = context_system::instance();
$experienceid = optional_param('experience_id', -1, PARAM_INT);
$PAGE->set_context($context);

$checkreport = $DB->record_exists('block_onb_e_report', array('experience_id' => $experienceid, 'user_id' => $USER->id));
if ($checkreport) {
    redirect('experience.php?experience_id=' . $experienceid);
} else {

    $PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/report_experience.php'));
    $PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
    $experience = $DB->get_field('block_onb_e_exps', 'name', array('id' => $experienceid));
    $PAGE->navbar->add($experience, new moodle_url('experience.php?experience_id=' . $experienceid));
    $PAGE->navbar->add(get_string('report_experience', 'block_onboarding'));

    $PAGE->set_title(get_string('report_experience', 'block_onboarding'));
    $PAGE->set_heading(get_string('report_experience', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_report_form.php');

    $mform = new experiences_report_form(null, array('experience_id' => $experienceid));

    if ($mform->is_cancelled()) {
        redirect('experience.php?experience_id=' . $experienceid);
    } else {
        if ($fromform = $mform->get_data()) {
            // Processing of data submitted in the form.
            block_onboarding\experiences_lib::edit_report($fromform);
            redirect('overview.php');
        }
    }

    // Display of the form.
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}
