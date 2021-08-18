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
 * File containing the form definition for reporting function for experiences.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use block_onboarding\constants;

/**
 * Class providing the form for reporting function for experiences.
 */
class experiences_report_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $USER;

        $mform = $this->_form;
        $experienceid = $this->_customdata['experience_id'];
        $mform->updateAttributes(array('class' => 'mform report_exp_onboarding'));
        // Hidden experience id.
        $mform->addElement('hidden', 'experience_id', $experienceid);
        $mform->setType('experience_id', PARAM_INT);

        // Hidden user id.
        $mform->addElement('hidden', 'user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);

        $mform->addElement('textarea', 'description', get_string('experience_description', 'block_onboarding'),
            array('wrap="virtual" rows="5" cols="50"'));

        $mform->addRule('description', get_string('experience_description_missing', 'block_onboarding'), 'required', null,
            'server');
        $mform->setType('description', PARAM_TEXT);

        // Reporting reason selector.
        $mform->addElement('advcheckbox', 'spam',
            get_string('spam', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'), array(0, 1));
        $mform->addElement('advcheckbox', 'profanity',
            get_string('profanity', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'),
            array(0, 1));
        $mform->addElement('advcheckbox', 'offensive',
            get_string('offensive', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'),
            array(0, 1));
        $mform->addElement('advcheckbox', 'falseinformation',
            get_string('falseinformation', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'),
            array(0, 1));
        $mform->addElement('advcheckbox', 'falsematching',
            get_string('falsematching', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'),
            array(0, 1));
        $mform->addElement('advcheckbox', 'personalinformation',
            get_string('personalinformation', 'block_onboarding'), '',
            array('group' => 1, 'class' => 'onboarding_close_elements'), array(0, 1));
        $mform->addElement('advcheckbox', 'other',
            get_string('other', 'block_onboarding'), '', array('group' => 1, 'class' => 'onboarding_close_elements'), array(0, 1));
        // Adds 'Report'- and 'Cancel'-buttons.
        $this->add_action_buttons($cancel = true, $submitlabel = get_string('report_experience', 'block_onboarding'));
    }

}
