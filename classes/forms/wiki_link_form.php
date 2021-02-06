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

class wiki_link_form extends moodleform {

    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        $link = $this->_customdata['link'];

        $mform->addElement('hidden','id', $link->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('link_name', 'block_onboarding'), 'maxlength="150"');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($link->name) ? $link->name : get_string('default_link_name', 'block_onboarding'));
        $mform->addRule('name', get_string('link_name_req', 'block_onboarding'), 'required', null, 'client');

        $categories = $DB->get_records('block_onb_w_categories');
        $categories_modified = array();
        foreach($categories as $category){
          $categories_modified[$category->id] = $category->name;
        }
        $mform->addElement('select', 'category_id', get_string('link_category', 'block_onboarding'), $categories_modified);
        if(isset($link->category_id)){
          $mform->setDefault('category_id', $link->category_id);
        }

        $mform->addElement('text', 'url', get_string('link_url', 'block_onboarding'), array('maxlength' => 255, 'size' => 48));
        $mform->setType('url', PARAM_TEXT);
        $mform->setDefault('url', isset($link->url) ? $link->url : get_string('default_link_url', 'block_onboarding'));
        $mform->addRule('url', get_string('link_url_req', 'block_onboarding'), 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('link_description', 'block_onboarding'),'wrap="virtual" rows="10" cols="50"');
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', isset($link->description) ? $link->description : get_string('default_link_description', 'block_onboarding'));
        $mform->addRule('description', get_string('link_description_req', 'block_onboarding'), 'required', null, 'client');

        $count_positions = $DB->count_records('block_onb_w_links');
        if($link->id == -1){
            $position_array = range(1, $count_positions+1);
        }else{
            $position_array = range(1, $count_positions);
        }
        $mform->addElement('select', 'position',get_string('link_number', 'block_onboarding'),$position_array , array());
        $mform->setType('position', PARAM_INT);
        $mform->setDefault('position', isset($link->position) ? $link->position-1 : $count_positions);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
