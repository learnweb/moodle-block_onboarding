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

class experiences_experience_form extends moodleform {

    public function definition() {
        global $CFG, $DB, $USER;

        $mform = $this->_form;

        $experience = $this->_customdata['experience'];

        $mform->addElement('hidden','id', $experience->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden','user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('experience_name', 'block_onboarding'), 'required');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($experience->name) ? $experience->name : get_string('default_experience_name', 'block_onboarding'));

        $courses = $DB->get_records('block_onb_e_courses');
        $courses_modified = array();
        foreach($courses as $course){
            $courses_modified[$course->id] = $course->name;
        }
        $mform->addElement('select', 'course_id', get_string('course_select', 'block_onboarding'), $courses_modified);
        if(isset($link->course_id)){
            $mform->setDefault('course_id', $link->course_id);
        }

        $categories = $DB->get_records('block_onb_e_cats');
        $experiences_categories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        foreach($categories as $category){
          $mform->addElement('checkbox', 'category_' . $category->id, $category->name);
        }

        foreach($experiences_categories as $experience_category){
          $mform->setDefault('category_' . $experience_category->category_id, true);
        }

        $categories = $DB->get_records('block_onb_e_cats');
        $experiences_categories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        $experiences_categories_mapped = array();
        foreach($experiences_categories as $experience_category){
          $experiences_categories_mapped[$experience_category->category_id] = $experience_category;
        }
        foreach($categories as $category){
          $mform->addElement('textarea', 'experience_category_' . $category->id . '_description', $category->name, array('wrap="virtual" rows="10" cols="100"', 'placeholder' => $category->questions));
          $mform->setType('experience_category_' . $category->id . '_description', PARAM_TEXT);
          $mform->setDefault('experience_category_' . $category->id . '_description', isset($experiences_categories_mapped[$category->id]) ? $experiences_categories_mapped[$category->id]->description : "");
          $mform->hideIf('experience_category_' . $category->id . '_description', 'category_' . $category->id);
        }

        $mform->addElement('text', 'contact', get_string('experience_contact', 'block_onboarding'));
        $mform->setType('contact', PARAM_TEXT);
        $mform->setDefault('contact', isset($experience->contact) ? $experience->contact : '');

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
