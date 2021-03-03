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

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$experience_id = optional_param('experience_id', -1, PARAM_INT);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/report_experience.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$experience = new stdClass();
$experience = $DB->get_record('block_onb_e_exps', array('id' => $experience_id), $fields='*', $strictness=IGNORE_MISSING);
$PAGE->navbar->add($experience->name, new moodle_url('experience.php?experience_id=' . $experience_id));
$PAGE->navbar->add(get_string('report_experience', 'block_onboarding'));

$PAGE->set_title(get_string('report_experience', 'block_onboarding'));
$PAGE->set_heading(get_string('report_experience', 'block_onboarding'));

require_once('./../classes/forms/experiences_report_form.php');

$mform = new experiences_report_form(null, array('experience_id' => $experience_id));

if ($mform->is_cancelled()) {
    redirect('experience.php?experience_id=' . $experience_id);
} else if ($fromform = $mform->get_data()) {
    block_onboarding\experiences_lib::edit_report($fromform);
    redirect('experience.php?experience_id=' . $experience_id);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
