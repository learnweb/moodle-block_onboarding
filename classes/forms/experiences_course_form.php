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

/**
 * File containing the form definition for experience courses.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for experience courses.
 */
class experiences_course_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {

        $mform = $this->_form;
        $course = $this->_customdata['course'];

        // Hidden course id.
        $mform->addElement('hidden', 'id', $course->id);
        $mform->setType('id', PARAM_INT);

        // Course Name Field.
        $mform->addElement('text', 'name', get_string('name', 'block_onboarding'));
        $mform->addRule('name', get_string('course_name_missing', 'block_onboarding'), 'required', null, 'server');
        $mform->addRule('name', 'Max Length is 30 characters', 'maxlength', 30, 'block_onboarding');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($course->name) ? $course->name :
            get_string('default_course_name', 'block_onboarding'));

        // Adds 'Submit'- and 'Cancel'-buttons.
        $this->add_action_buttons();
    }
}
