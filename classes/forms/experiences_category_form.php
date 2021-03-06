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

class experiences_category_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $category = $this->_customdata['category'];

        $mform->addElement('hidden', 'id', $category->id);
        $mform->setType('id', PARAM_INT);

        // Category Name Field.
        $mform->addElement('text', 'name', get_string('category_name', 'block_onboarding'));
        $mform->addRule('name', get_string('experience_category_missing', 'block_onboarding'),
            'required', null, 'server');
        $mform->addRule('name', 'Max Length is 30 characters', 'maxlength', 30, 'block_onboarding');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($category->name) ? $category->name : get_string('default_category_name',
            'block_onboarding'));

        // Questions Textarea.
        $mform->addElement('textarea', 'questions', get_string('questions', 'block_onboarding'),
            'wrap="virtual" rows="10" cols="100"');
        $mform->addRule('questions', get_string('experience_questions_missing', 'block_onboarding'),
            'required', null, 'server');
        $mform->setType('questions', PARAM_TEXT);
        $mform->setDefault('questions', isset($category->questions) ? $category->questions : '');

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
