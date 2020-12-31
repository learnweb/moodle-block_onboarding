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

require(__DIR__ . '/../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/wiki/edit_category.php'));
$PAGE->set_title(get_string('edit_category', 'block_wiki'));
$PAGE->set_heading(get_string('edit_category', 'block_wiki'));
$PAGE->navbar->add(get_string('pluginname', 'block_wiki'));

require_once('./classes/forms/category_form.php');

$category_id = optional_param('category_id', -1, PARAM_INT);
$pCategory = new stdClass;
$pCategory->id = -1;
if($category_id != -1){
  $pCategory = $DB->get_record('block_wiki_categories', array('id'=>$category_id), $fields='*', $strictness=IGNORE_MISSING);
}
$mform = new category_form(null, array('category' => $pCategory));

if ($mform->is_cancelled()) {
		redirect('admin.php');
} else if ($fromform = $mform->get_data()) {
    $category = new stdClass();
    $category->name = $fromform->name;
    $category->timecreated = time();
    $category->timemodified = time();

    if($fromform->id != -1){
      $category->id = $fromform->id;
      $DB->update_record('block_wiki_categories', $category, $bulk=false);
    }else{
      $category->id = $DB->insert_record('block_wiki_categories', $category);
    }
    redirect('admin.php');
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
