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

require_once($CFG->libdir . '/formslib.php');

class wiki_category_form extends moodleform {

    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;


        $category = $this->_customdata['category'];

        $mform->addElement('hidden','id', $category->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('category_name', 'block_onboarding'), array('maxlength'=>150, 'size'=>24, 'placeholder'=>get_string('default_category_name_wiki', 'block_onboarding')));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($category->name) ? $category->name : '');
        $mform->addRule('name', get_string('category_name_req', 'block_onboarding'), 'required', null, 'client');

        $count_positions = $DB->count_records('block_onb_w_categories');
        if($category->id == -1){
            $position_array = range(1, $count_positions+1);
        }else{
            $position_array = range(1, $count_positions);
        }
        $mform->addElement('select', 'position',get_string('category_number', 'block_onboarding'),$position_array , array());
        $mform->setType('position', PARAM_INT);
        $mform->setDefault('position', isset($category->position) ? $category->position-1 : $count_positions);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
