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
 * File containing the form definition for steps.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for steps.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class steps_step_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;
        $step = $this->_customdata['step'];

        // Hidden step id.
        $mform->addElement('hidden', 'id', $step->id);
        $mform->setType('id', PARAM_INT);

        // Step name Field.
        $mform->addElement('text', 'name', get_string('step_name', 'block_onboarding'),
            array('maxlength' => 150, 'size' => 50,
                'placeholder' => get_string('default_step_name', 'block_onboarding')));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($step->name) ? $step->name : '');
        $mform->addRule('name', get_string('step_name_req', 'block_onboarding'), 'required', null, 'client');

        // Step description field.
        $mform->addElement('textarea', 'description', get_string('block-onboarding-steps-step-description', 'block_onboarding'),
            array('wrap' => "virtual", 'rows' => 10, 'cols' => 50,
                'placeholder' => get_string('step_description_req', 'block_onboarding')));
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', isset($step->description) ? $step->description : '');
        $mform->addRule('description', get_string('step_description_req', 'block_onboarding'), 'required', null, 'client');

        // Achievement checkbox.
        $mform->addElement('checkbox', 'achievement', get_string('step_achievement', 'block_onboarding'));
        $mform->setDefault('achievement', isset($step->achievement) ? (($step->achievement == 1) ? true : false) : false);

        // Step position selector.
        $countpositions = $DB->count_records('block_onb_s_steps');
        if ($step->id == -1) {
            $positionarray = range(1, $countpositions + 1);
        } else {
            $positionarray = range(1, $countpositions);
        }
        $mform->addElement('select', 'position', get_string('step_number', 'block_onboarding'), $positionarray, array());
        $mform->setType('position', PARAM_INT);
        $mform->setDefault('position', isset($step->position) ? $step->position - 1 : $DB->count_records('block_onb_s_steps'));

        // Adds 'Submit'- and 'Cancel'-buttons.
        $this->add_action_buttons();
    }
}
