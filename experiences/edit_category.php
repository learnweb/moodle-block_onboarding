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

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_category.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

if(has_capability('block/onboarding:e_edit_categories', \context_system::instance())){
  $PAGE->set_title(get_string('edit_category', 'block_onboarding'));
  $PAGE->set_heading(get_string('edit_category', 'block_onboarding'));

  require_once('./../classes/forms/experiences_category_form.php');

  $category_id = optional_param('category_id', -1, PARAM_INT);
  $pCategory = new stdClass;
  $pCategory->id = -1;
  if($category_id != -1){
    // Get the existing data from the Database
    $pCategory = $DB->get_record('block_onb_e_cats', array('id'=>$category_id), $fields='*', $strictness=IGNORE_MISSING);
  }
  $mform = new experiences_category_form(null, array('category' => $pCategory));

  if ($mform->is_cancelled()) {
  		redirect('overview.php');
  } else if ($fromform = $mform->get_data()) {
      // Data written in the Database
      $category = new stdClass();
      $category->name = $fromform->name;
      $category->questions = $fromform->questions;
      $category->timecreated = time();
      $category->timemodified = time();

      if($fromform->id != -1){
        $category->id = $fromform->id;
        $DB->update_record('block_onb_e_cats', $category, $bulk=false);
      }else{
        $category->id = $DB->insert_record('block_onb_e_cats', $category);
      }
      redirect('overview.php');
  }

  echo $OUTPUT->header();
  $mform->display();
  echo $OUTPUT->footer();
}else{
  $PAGE->set_title(get_string('error', 'block_onboarding'));
  $PAGE->set_heading(get_string('error', 'block_onboarding'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
  echo $OUTPUT->footer();
}
