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
// form API
require_once("$CFG->libdir/formslib.php");

class step_form extends moodleform {

    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        $step = $this->_customdata['step'];

        $mform->addElement('hidden','id', $step->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('step_name', 'block_steps'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($step->name) ? $step->name : get_string('default_step_name', 'block_steps'));

        $mform->addElement('text', 'description', get_string('step_description', 'block_steps'));
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', isset($step->description) ? $step->description : get_string('default_step_description', 'block_steps'));
        // zählt DB Eintrag und ändert position anhand Anzahl von Einträgen

        //$mform->addElement('hidden','position', $DB->count_records('block_steps_steps'));
        $select = $mform->addElement('select', 'position',get_string('step_number', 'block_steps'), array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7), array());
        $mform->setType('position', PARAM_INT);



        $this->add_action_buttons();
    }



    public function validation($data, $files) {
        return array();
    }
}
