<?php
// This file is part of Moodle - http://moodle.org/
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
 * Qr code library code.
 *
 * @package block_onboarding
 * @copyright 2021 Nina Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;

use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * Class admin_form
 *
 * Moodle form for settings site.
 *
 * @package block_onboarding
 * @copyright 2021 N Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_form extends moodleform {

    /**
     * Creates the form.
     */
    protected function definition() {

        // Checkbox.
        $mform = $this->_form;

        $mform->addElement('textarea', 'csvofstudies', get_string('studies', 'block_onboarding'),
            array('rows' => 5, 'cols' => 50));
        $mform->addHelpButton('csvofstudies', 'csvofstudiestext', 'block_onboarding');
        $mform->setType('csvofstudies', PARAM_RAW);

        // Adds 'Submit'-button.
        $mform->addElement('submit', 'submitbutton', get_string('submit', 'moodle'));
    }
}
