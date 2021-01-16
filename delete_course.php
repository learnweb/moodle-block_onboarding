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

if(has_capability('block/experiences:edit_courses', $context)){
  $course_id = optional_param('course_id', -1, PARAM_INT);
  $DB->delete_records('block_experiences_courses', array('id' => $course_id));
  redirect('overview.php');
}else{
  $PAGE->set_context($context);
  $PAGE->set_url(new moodle_url('/blocks/experiences/edit_experience.php'));
  $PAGE->set_title(get_string('error', 'block_experiences'));
  $PAGE->set_heading(get_string('error', 'block_experiences'));
  $PAGE->navbar->add(get_string('pluginname', 'block_experiences'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_experiences'));
  echo $OUTPUT->footer();
}
