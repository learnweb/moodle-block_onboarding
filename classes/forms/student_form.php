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

class student_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('header', 'basic', get_string('basic', 'local_onboarding'));
        $select = $mform->addElement('select', 'degree', get_string('aspired-degree', 'local_onboarding'),
          array(1 => 'Bachelor', 2 => 'Master'), array());
        $select = $mform->addElement('select', 'degreeprogram', get_string('my-degree-program', 'local_onboarding'),
          array(1 => 'Betriebswirtschaftslehre', 2 => 'Volkwirtschaftslehre', 3 => 'Wirtschaftsinformatik',
              4 => 'Wirtschaft & Recht', 5 => 'Politk & Wirtschaft', 6 => 'Wirtschaftslehre / Politik',
              7 => 'Ökonomik', 8 => 'Politik und Recht', 9 => 'Information Systems',
              10 => 'Public Sector Innovation and eGovernance', 11 => 'Economics'), array());
        $select = $mform->addElement('select', 'semester', get_string('my-semester', 'local_onboarding'),
          array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7'), array());
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
