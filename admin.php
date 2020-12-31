<?php
// This file is part of wiki block for Moodle - http://moodle.org/
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

require(__DIR__ . '/../../config.php');

global $DB, $USER;

$context = context_system::instance();

require_login();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/wiki/overview.php'));
$PAGE->set_title(get_string('overview', 'block_wiki'));
$PAGE->set_heading(get_string('overview', 'block_wiki'));
$PAGE->navbar->add(get_string('pluginname', 'block_wiki'));

require_once('./classes/forms/student_form.php');
$mform = new student_form();

if ($fromform = $mform->get_data()) {
    // Insert the data into the Database.

    $recordtoinsert = new stdClass();
    $recordtoinsert->userid = $USER->id;
    $recordtoinsert->degree = $fromform->degree;
    $recordtoinsert->degreeprogram = $fromform->degreeprogram;
    $recordtoinsert->semester = $fromform->semester;
    $recordtoinsert->changetime = time();

    //$DB->insert_record('local_onboarding', $recordtoinsert);

}

echo $OUTPUT->header();

/*echo html_writer::tag('h2', get_string('about-me', 'local_onboarding'));
echo html_writer::tag('p', get_string('welcome_text', 'local_onboarding'));*/

$mform->display();

/*if (has_capability('local/onboarding:manage_degrees', context_system::instance())) {
    echo html_writer::tag('h2', get_string('admin-area', 'local_onboarding'));
    echo html_writer::tag('p', get_string('admin-area-intro', 'local_onboarding'));
    echo html_writer::tag('a', get_string('edit-courses', 'local_onboarding'), array('href' => '', 'class' => 'btn btn-secondary'));
}*/

echo $OUTPUT->footer();
