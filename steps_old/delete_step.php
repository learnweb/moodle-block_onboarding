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

require(__DIR__ . '/../../../config.php');

require_login();

global $DB, $USER;

// TODO: Löschen muss auch user_steps entfernen!!

$context = context_system::instance();

if(has_capability('block/onboarding:s_manage_steps', $context)){
  $stepid = optional_param('step_id', -1, PARAM_INT);
  $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
  $curposition = $paramstep->position;
  $stepcount = $DB->count_records('block_onb_s_steps');

  // deleting step and adjusting other step positions accordingly
  \block_onboarding\step_admin_functions::decrement_step_positions($stepcount, $curposition);
  $DB->delete_records('block_onb_s_steps', array('id' => $stepid));

  // deleting all user progress for deleted step
  $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id, 'stepid' => $stepid));
  if($step != false){
      $paramstep = $DB->get_record('block_onb_s_steps', array('position' => 1));
      // gucken, ob überhaupt nich ein Schritt exisitiert
      if($paramstep != false){
          $step->stepid = $paramstep->id;
          $DB->update_record('block_onb_s_current', $step);
      }else{
          $DB->delete_records('block_onb_s_current', array('stepid' => $stepid));
      }
  }
  $DB->delete_records('block_onb_s_completed', array('stepid' => $stepid));


  redirect('admin.php');
}else{
  $PAGE->set_context($context);
  $PAGE->set_url(new moodle_url('/blocks/onboarding/steps/delete_step.php'));
  $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));
  $PAGE->set_title(get_string('error', 'block_onboarding'));
  $PAGE->set_heading(get_string('error', 'block_onboarding'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
  echo $OUTPUT->footer();
}
