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

require(__DIR__ . '/../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/experiences/edit_experience.php'));
$PAGE->set_title(get_string('edit_experience', 'block_experiences'));
$PAGE->set_heading(get_string('edit_experience', 'block_experiences'));
$PAGE->navbar->add(get_string('pluginname', 'block_experiences'));

require_once('./classes/forms/experience_form.php');

$experience_id = optional_param('experience_id', -1, PARAM_INT);
$pExperience = new stdClass;
$pExperience->id = -1;
if($experience_id != -1){
  $pExperience = $DB->get_record('block_experiences_exps', array('id'=>$experience_id), $fields='*', $strictness=IGNORE_MISSING);
}
$mform = new experience_form(null, array('experience' => $pExperience));

if ($mform->is_cancelled()) {
		redirect('admin.php');
} else if ($fromform = $mform->get_data()) {
    $experience = new stdClass();
    $experience->name = $fromform->name;
    $experience->category_id = $fromform->category_id;
    $experience->url = $fromform->url;
    $experience->description = $fromform->description;
    $experience->timecreated = time();
    $experience->timemodified = time();

    if($fromform->id != -1){
      $experience->id = $fromform->id;
      $DB->update_record('block_experiences_exps', $experience, $bulk=false);
    }else{
      $experience->id = $DB->insert_record('block_experiences_exps', $experience);
    }
    redirect('admin.php');
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
