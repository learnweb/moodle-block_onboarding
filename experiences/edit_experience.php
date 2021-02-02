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
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

$experience_id = optional_param('experience_id', -1, PARAM_INT);
$pexperience = new stdClass;
$pexperience->id = -1;
if ($experience_id != -1) {
    // Get the existing data from the Database.
    $pexperience = $DB->get_record('block_onb_e_exps',
        array('id'=>$experience_id), $fields='*', $strictness=IGNORE_MISSING);
}

if ($experience_id == -1 || $USER->id == $pexperience->user_id ||
    has_capability('block/onboarding:e_edit_all_experiences', \context_system::instance())) {
    $PAGE->set_title(get_string('edit_experience', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_experience', 'block_onboarding'));

    require_once('./../classes/forms/experiences_experience_form.php');

    $mform = new experiences_experience_form(null, array('experience' => $pexperience));

    if ($mform->is_cancelled()) {
        redirect('overview.php');
    } else if ($fromform = $mform->get_data()) {
        // Data written in the Database.
        $experience = new stdClass();
        $experience->name = $fromform->name;
        $experience->contact = $fromform->contact;
        $experience->takeaways = $fromform->takeaways;
        $experience->user_id = $fromform->user_id;
        $experience->course_id = $fromform->course_id;
        $experience->timecreated = time();
        $experience->timemodified = time();

        if ($fromform->id != -1) {
            $experience->id = $fromform->id;
            $DB->update_record('block_onb_e_exps', $experience, $bulk=false);
        } else {
            $experience->id = $DB->insert_record('block_onb_e_exps', $experience);
        }

        $DB->delete_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        $categories = $DB->get_records('block_onb_e_cats');
        $experiences_categories = array();

        foreach ($categories as $category) {
            $formproperty_category_checkbox = 'category_' . $category->id;
            if(isset($fromform->$formproperty_category_checkbox)){
                $experience_category = new stdClass;
                $experience_category->experience_id = $experience->id;
                $experience_category->category_id = $category->id;
                $formproperty_category_textarea = 'experience_category_' . $category->id . '_description';
                $experience_category->description = $fromform->$formproperty_category_textarea;
                $experience_category->timecreated = time();
                $experience_category->timemodified = time();
                $experiences_categories[] = $experience_category;
            }
        }
        $DB->insert_records('block_onb_e_exps_cats', $experiences_categories);

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
