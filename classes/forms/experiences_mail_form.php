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
 * File containing the form definition for specifying a notification email for the author of an experience.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class experiences_mail_form extends moodleform {

    public function definition() {

        $mform = $this->_form;

        $experienceid = $this->_customdata['experience_id'];

        $mform->addElement('hidden', 'experience_id', $experienceid);
        $mform->setType('experience_id', PARAM_INT);

        // Course Name Field.
        $mform->addElement('textarea', 'title', get_string('course_name', 'block_onboarding'),
            array('style="resize:none" wrap="virtual" rows="1" cols="100"'));
        $mform->addRule('title', get_string('title_missing', 'block_onboarding'), 'required', null, 'server');
        $mform->addRule('title', 'Max Length is 30 characters', 'maxlength', 30, 'block_onboarding');
        $mform->setType('title', PARAM_TEXT);
        $mform->setDefault('title', get_string('sus_mail_title', 'block_onboarding'));

        $mform->addElement('textarea', 'comment', get_string('experience_description', 'block_onboarding'),
            array('wrap="virtual" rows="12" cols="100"'));
        $mform->addRule('comment', get_string('experience_description_missing', 'block_onboarding'), 'required', null, 'server');
        $mform->setType('comment', PARAM_TEXT);
        $mform->setDefault('comment', get_string('sus_mail_text', 'block_onboarding'));

        $this->add_action_buttons($cancel = true, $submitlabel = get_string('submit_mail', 'block_onboarding'));
    }

    public function validation($data, $files) {
        return array();
    }
}
