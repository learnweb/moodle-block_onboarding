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

global $DB, $USER;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_experience.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('edit_experience', 'block_onboarding'));

$experience_id = optional_param('experience_id', -1, PARAM_INT);
$pexperience = new stdClass;
$pexperience->id = -1;

//$sql = "SELECT ec.id as id, e.id as exp_id, e.user_id as u_id FROM {block_onb_e_exps_cats} ec
//        INNER JOIN {block_onb_e_exps} e
//        ON ec.experience_id = e.id
//        WHERE e.user_id = {$USER->id}";
//$checkexperience = $DB->get_records_sql($sql);

$checkblocked = $DB->record_exists('block_onb_e_blocked', array('user_id' => $USER->id));
if ($checkblocked == true) {
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('blocked', 'block_onboarding'));
    echo $OUTPUT->footer();

} else {
    if ($experience_id != -1) {
        // Get the existing data from the Database.
        $pexperience = $DB->get_record('block_onb_e_exps',
            array('id' => $experience_id), $fields = '*', $strictness = IGNORE_MISSING);
    }

//elseif ($experience_id == -1 && empty($checkexperience) == false) {
    //redirect('overview.php');
//}

    $checkcourses = $DB->count_records('block_onb_e_courses');
    $checkcategories = $DB->count_records('block_onb_e_cats');

    if ($checkcourses != 0 || $checkcategories != 0) {

        if ($experience_id == -1 || $USER->id == $pexperience->user_id ||
            has_capability('block/onboarding:e_manage_experiences', \context_system::instance())) {
            $PAGE->set_title(get_string('edit_experience', 'block_onboarding'));
            $PAGE->set_heading(get_string('edit_experience', 'block_onboarding'));

            require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_experience_form.php');

            $mform = new experiences_experience_form(null, array('experience' => $pexperience));

            if ($mform->is_cancelled()) {
                redirect('overview.php');
            } else if ($fromform = $mform->get_data()) {

                block_onboarding\experiences_lib::edit_experience($fromform);
                redirect('overview.php');
            }

            echo $OUTPUT->header();
            $mform->display();
            echo $OUTPUT->footer();
        } else {
            $PAGE->set_title(get_string('error', 'block_onboarding'));
            $PAGE->set_heading(get_string('error', 'block_onboarding'));

            echo $OUTPUT->header();
            echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
            echo $OUTPUT->footer();
        }
    } else {
        $PAGE->set_title(get_string('error', 'block_onboarding'));
        $PAGE->set_heading(get_string('error', 'block_onboarding'));

        echo $OUTPUT->header();
        echo html_writer::tag('p', get_string('notenoughdata', 'block_onboarding'));
        echo $OUTPUT->footer();
    }
}
