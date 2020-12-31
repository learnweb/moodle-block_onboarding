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
        global $CFG, $DB;

        $mform = $this->_form;

        $experience = $this->_customdata['experience'];

        $mform->addElement('hidden','id', $experience->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('experience_name', 'block_experiences'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($experience->name) ? $experience->name : get_string('default_experience_name', 'block_experiences'));

        $categories = $DB->get_records('block_experiences_categories');
        $categories_modified = array();
        foreach($categories as $category){
          $categories_modified[$category->id] = $category->name;
        }
        $mform->addElement('select', 'category_id', get_string('experience_category', 'block_experiences'), $categories_modified);
        if(isset($experience->category_id)){
          $mform->setDefault('category_id', $experience->category_id);
        }

        $mform->addElement('text', 'url', get_string('experience_url', 'block_experiences'));
        $mform->setType('url', PARAM_TEXT);
        $mform->setDefault('url', isset($experience->url) ? $experience->url : get_string('default_experience_url', 'block_experiences'));

        $mform->addElement('text', 'description', get_string('experience_description', 'block_experiences'));
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', isset($experience->description) ? $experience->description : get_string('default_experience_description', 'block_experiences'));

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
