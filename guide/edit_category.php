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

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/edit_category.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));
$PAGE->navbar->add(get_string('guide', 'block_onboarding'));

if(has_capability('block/onboarding:w_manage_wiki', $context)){
  $PAGE->set_title(get_string('edit_category', 'block_onboarding'));
  $PAGE->set_heading(get_string('edit_category', 'block_onboarding'));

  require_once('./../classes/forms/wiki_category_form.php');

  $categoryid = optional_param('category_id', -1, PARAM_INT);
  $paramcategory = new stdClass;
  $paramcategory->id = -1;
  if($categoryid != -1){
    $paramcategory = $DB->get_record('block_onb_w_categories', array('id'=>$categoryid));
  }

  $mform = new wiki_category_form(null, array('category' => $paramcategory));

  if ($mform->is_cancelled()) {
  		redirect('overview.php');

  } else if ($fromform = $mform->get_data()) {
      $category = new stdClass();
      $category->name = $fromform->name;
      $insertposition = $fromform->position + 1;

      if($fromform->id != -1){
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id'=>$fromform->id));
        $curposition = $paramcategory->position;
        if ($insertposition > $curposition) {
            \block_onboarding\wiki_admin_functions::decrement_category_positions($insertposition, $curposition);
        } else if ($insertposition < $curposition) {
            \block_onboarding\wiki_admin_functions::increment_category_positions($insertposition, $curposition);
        }
        $category->id = $fromform->id;
        $category->position = $fromform->position + 1;
        $category->timemodified = time();
        $DB->update_record('block_onb_w_categories', $category);

      }else{
        $initposition = $DB->count_records('block_onb_w_categories') + 1;
        $category->position = $initposition;
        $category->timecreated = time();
        $category->timemodified = time();
        $category->id = $DB->insert_record('block_onb_w_categories', $category);

        if ($initposition != $insertposition) {
            \block_onboarding\wiki_admin_functions::increment_category_positions($insertposition, $initposition);
            $category->position = $insertposition;
            $category->timemodified = time();
            $DB->update_record('block_onb_w_categories', $category);
        }
      }
      redirect('admin_wiki.php');
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
