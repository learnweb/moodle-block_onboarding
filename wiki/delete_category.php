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

if(has_capability('block/onboarding:w_manage_wiki', $context)){
  $categoryid = optional_param('category_id', -1, PARAM_INT);
  $paramcategory = $DB->get_record('block_onb_w_categories', array('id'=>$categoryid));
  $curposition = $paramcategory->position;
  $categorycount = $DB->count_records('block_onb_w_categories');
  \block_onboarding\wiki_admin_functions::decrement_category_positions($categorycount, $curposition);
  $DB->delete_records('block_onb_w_categories', array('id' => $categoryid));

  // deleting all links within the category
  $DB->delete_records('block_onb_w_links', array('category_id' => $categoryid));

  redirect('admin.php');
}else{
  $PAGE->set_context($context);
  $PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/delete_category.php'));
  $PAGE->set_title(get_string('error', 'block_onboarding'));
  $PAGE->set_heading(get_string('error', 'block_onboarding'));
  $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
  echo $OUTPUT->footer();
}