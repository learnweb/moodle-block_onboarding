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

require(__DIR__ . '/../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/steps/edit_step.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_steps'));

if(has_capability('block/steps:edit_steps', $context)){
  $PAGE->set_title(get_string('edit_step', 'block_steps'));
  $PAGE->set_heading(get_string('edit_step', 'block_steps'));

  require_once('./classes/forms/step_form.php');

  $step_id = optional_param('step_id', -1, PARAM_INT);
  $pStep = new stdClass;
  $pStep->id = -1;
  if($step_id != -1){
    $pStep = $DB->get_record('block_steps_steps', array('id'=>$step_id), $fields='*', $strictness=IGNORE_MISSING);
  }
  $mform = new step_form(null, array('step' => $pStep));

  if ($mform->is_cancelled()) {
  		redirect('admin.php');
  } else if ($fromform = $mform->get_data()) {
      $step = new stdClass();
      $step->name = $fromform->name;
      $step->description = $fromform->description;

      $step->timecreated = time();
      $step->timemodified = time();

      // hier ist edit step
      if($fromform->id != -1){
        $step->id = $fromform->id;
        $step->position = $fromform->position;
        $DB->update_record('block_steps_steps', $step, $bulk=false);

      //hier neuer step
      }else{
          $step->position = $fromform->position;
          $step->id = $DB->insert_record('block_steps_steps', $step);
      }
      //hier muss funktion hin, die alle anderen Steps um einen nach hinten schiebt
      //if(fromform->position )
      redirect('admin.php');
  }

  echo $OUTPUT->header();
  $mform->display();
  echo $OUTPUT->footer();
}else{
  $PAGE->set_title(get_string('error', 'block_steps'));
  $PAGE->set_heading(get_string('error', 'block_steps'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_steps'));
  echo $OUTPUT->footer();
}
