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

class experience_form extends moodleform {

    public function definition() {
        global $CFG, $DB, $USER;

        $mform = $this->_form;

        $experience = $this->_customdata['experience'];

        $mform->addElement('hidden','id', $experience->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden','user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('experience_name', 'block_experiences'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($experience->name) ? $experience->name : get_string('default_experience_name', 'block_experiences'));

        //$mform->addElement('select', 'course',get_string('course_select', 'block_experiences'),$courses_array , array());
        //$mform->setType('course', PARAM_TEXT);
        //$mform->setDefault('position', isset($step->position) ? $step->position-1 : $DB->count_records('block_steps_steps'));

        $categories = $DB->get_records('block_experiences_cats');
        $experiences_categories = $DB->get_records('block_experiences_exps_cats', array('experience_id' => $experience->id));
        foreach($categories as $category){
          $mform->addElement('checkbox', 'category_' . $category->id, $category->name);
        }

        foreach($experiences_categories as $experience_category){
          $mform->setDefault('category_' . $experience_category->category_id, true);
        }

        $categories = $DB->get_records('block_experiences_cats');
        $experiences_categories = $DB->get_records('block_experiences_exps_cats', array('experience_id' => $experience->id));
        $experiences_categories_mapped = array();
        foreach($experiences_categories as $experience_category){
          $experiences_categories_mapped[$experience_category->category_id] = $experience_category;
        }
        foreach($categories as $category){
          $mform->addElement('textarea', 'experience_category_' . $category->id . '_description', $category->name, 'wrap="virtual" rows="10" cols="100"');
          $mform->setType('experience_category_' . $category->id . '_description', PARAM_TEXT);
          $mform->setDefault('experience_category_' . $category->id . '_description', isset($experiences_categories_mapped[$category->id]) ? $experiences_categories_mapped[$category->id]->description : '');
          $mform->hideIf('experience_category_' . $category->id . '_description', 'category_' . $category->id);
        }

        $mform->addElement('text', 'contact', get_string('experience_contact', 'block_experiences'));
        $mform->setType('contact', PARAM_TEXT);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
