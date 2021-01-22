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
$PAGE->set_url(new moodle_url('/blocks/experiences/edit_course.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_experiences'));

if(has_capability('block/experiences:edit_courses', \context_system::instance())){
  $PAGE->set_title(get_string('edit_course', 'block_experiences'));
  $PAGE->set_heading(get_string('edit_course', 'block_experiences'));

  require_once('./classes/forms/course_form.php');

  $course_id = optional_param('course_id', -1, PARAM_INT);
  $pCourse = new stdClass;
  $pCourse->id = -1;
  if($course_id != -1){
    $pCourse = $DB->get_record('block_experiences_courses', array('id'=>$course_id), $fields='*', $strictness=IGNORE_MISSING);
  }
  $mform = new course_form(null, array('course' => $pCourse));

  if ($mform->is_cancelled()) {
  		redirect('overview.php');
  } else if ($fromform = $mform->get_data()) {
      $course = new stdClass();
      $course->name = $fromform->name;
      $course->timecreated = time();
      $course->timemodified = time();

      if($fromform->id != -1){
        $course->id = $fromform->id;
        $DB->update_record('block_experiences_courses', $course, $bulk=false);
      }else{
        $course->id = $DB->insert_record('block_experiences_courses', $course);
      }
      redirect('overview.php');
  }

  echo $OUTPUT->header();
  $mform->display();
  echo $OUTPUT->footer();
}else{
  $PAGE->set_title(get_string('error', 'block_experiences'));
  $PAGE->set_heading(get_string('error', 'block_experiences'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_experiences'));
  echo $OUTPUT->footer();
}
