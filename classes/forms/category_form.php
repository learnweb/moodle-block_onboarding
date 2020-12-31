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

class category_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $category = $this->_customdata['category'];

        $mform->addElement('hidden','id', $category->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('category_name', 'block_wiki'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($category->name) ? $category->name : get_string('default_category_name', 'block_wiki'));

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
