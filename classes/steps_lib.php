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

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

class steps_lib {

    public static function edit_step($fromform){
        // speichere Basis Daten aus der Form ausgenommen der position in dem Objekt step -> weitere Verarbeitung folgt
        $step = new stdClass();
        $step->name = $fromform->name;
        $step->description = $fromform->description;
        $step->achievement = isset($fromform->achievement) ? 1 : 0;
        $step->position = $fromform->position + 1;

        // wenn ein bestehender Schritt editiert wird, aktualisiere den Datensatz
        if ($fromform->id != -1) {
            $step->id = $fromform->id;
            \block_onboarding\steps_lib::update_step($step);

            // andernfalls wird ein neuer Schritt bzw. Datensatz hinzugefügt, dessen position aus der Form übernommen wird
        } else {
            \block_onboarding\steps_lib::add_step($step);
        }
    }

    public static function add_step($step){
        global $DB;
        
        $initposition = $DB->count_records('block_onb_s_steps') + 1;
        $insertposition = $step->position;

        $step->position = $initposition;
        $step->timecreated = time();
        $step->timemodified = time();
        $step->id = $DB->insert_record('block_onb_s_steps', $step);

        // wenn neuer Schritt nicht hinten eingefügt werden soll
        if ($initposition != $insertposition) {
            \block_onboarding\step_admin_functions::increment_step_positions($insertposition, $initposition);
            $step->position = $insertposition;
            $step->timemodified = time();
            $DB->update_record('block_onb_s_steps', $step);
        }
    }

    public static function update_step($step){
        global $DB;

        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $step->id));
        $curposition = $paramstep->position;
        $insertposition = $step->position;

        // Prüfen ob Änderung von anderen pos erforderlich ist
        // wenn gewünschte Einfügeposition weiter hinten als aktuelle Position ist
        if ($insertposition > $curposition) {
            \block_onboarding\step_admin_functions::decrement_step_positions($insertposition, $curposition);

            // wenn gewünschte Einfügeposition weiter vorne als aktuelle Position ist
        } else if ($insertposition < $curposition) {
            \block_onboarding\step_admin_functions::increment_step_positions($insertposition, $curposition);
        }
        // andernfalls ist die Position gleich und es müssen keine anderen Schrittpositionen verändert werden
        
        $step->position = $fromform->position + 1;
        $step->timemodified = time();
        $DB->update_record('block_onb_s_steps', $step);
    }

    public static function delete_step($stepid){
        global $DB;
        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
        $curposition = $paramstep->position;
        $stepcount = $DB->count_records('block_onb_s_steps');

        // deleting step and adjusting other step positions accordingly
        \block_onboarding\step_admin_functions::decrement_step_positions($stepcount, $curposition);
        $DB->delete_records('block_onb_s_steps', array('id' => $stepid));

        // deleting all user progress for deleted step
        $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id, 'stepid' => $stepid));
        if($step != false){
            $paramstep = $DB->get_record('block_onb_s_steps', array('position' => 1));
            // gucken, ob überhaupt nich ein Schritt exisitiert
            if($paramstep != false){
                $step->stepid = $paramstep->id;
                $DB->update_record('block_onb_s_current', $step);
            }else{
                $DB->delete_records('block_onb_s_current', array('stepid' => $stepid));
            }
        }
        $DB->delete_records('block_onb_s_completed', array('stepid' => $stepid));
    }
}