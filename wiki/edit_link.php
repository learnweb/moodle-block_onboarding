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
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/edit_link.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

if(has_capability('block/onboarding:w_manage_wiki', $context)){
  $PAGE->set_title(get_string('edit_link', 'block_onboarding'));
  $PAGE->set_heading(get_string('edit_link', 'block_onboarding'));

  require_once('./../classes/forms/wiki_link_form.php');

  $link_id = optional_param('link_id', -1, PARAM_INT);
  $pLink = new stdClass;
  $pLink->id = -1;
  if($link_id != -1){
    $pLink = $DB->get_record('block_onb_w_links', array('id'=>$link_id), $fields='*', $strictness=IGNORE_MISSING);
  }
  $mform = new wiki_link_form(null, array('link' => $pLink));
    #$mform->addHelpButton('Link', 'link_description', 'block_onboarding');
   # $this->add_action_buttons();
  if ($mform->is_cancelled()) {
  		redirect('overview.php');
  } else if ($fromform = $mform->get_data()) {
      $link = new stdClass();
      $link->name = $fromform->name;
      $link->category_id = $fromform->category_id;
      $link->url = $fromform->url;
      $link->description = $fromform->description;
      $link->timecreated = time();
      $link->timemodified = time();

      if($fromform->id != -1){
        $link->id = $fromform->id;
        $DB->update_record('block_onb_w_links', $link, $bulk=false);
      }else{
        $link->id = $DB->insert_record('block_onb_w_links', $link);
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
