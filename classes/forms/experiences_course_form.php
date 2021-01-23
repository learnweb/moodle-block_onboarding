<?php
// This file is part of the local onboarding plugin
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

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class experiences_course_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $course = $this->_customdata['course'];

        $mform->addElement('hidden','id', $course->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('course_name', 'block_onboarding'), 'required');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($course->name) ? $course->name : get_string('default_course_name', 'block_onboarding'));

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
