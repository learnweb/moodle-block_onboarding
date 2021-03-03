<?php
// This file is part of a plugin for Moodle - http://moodle.org/
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
 * functions to support externallib for ajax calls
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;
defined('MOODLE_INTERNAL') || die();

// TODO: Language Strings bei Messages verwenden!
// TODO: Funktionen vereinfachen und zusammenfassen

class step_view_data_functions {

    /**
     * Beschreibung hinzufügen!
     */

    // !!! ÜBERGABE DER USERID HIER UND in JS ggf. ÜBERFLÜSSIG WG. $USER->ID ? -> prüfen
    // evtl. Komplikationen wegen Kontexten von verschiedenen Nutzern? -> eher nicht (?)

    public static function get_current_user_stepid() {
        global $DB, $USER;

        $stepbool = $DB->record_exists('block_onb_s_current', array('userid' => $USER->id));

        // wenn noch kein Fortschritt gemacht wurde, also kein Datensatz vorhanden ist -> starten bei pos = 1
        if ($stepbool == false) {

            // wenn kein step in Liste vorhanden ist
            if ($DB->count_records('block_onb_s_steps') == 0) {
                $returnstepid = -1;
            } else {
                $tempstep = $DB->get_record('block_onb_s_steps', array('position' => 1));

                $step = new \stdClass();
                $step->userid = $USER->id;
                $step->stepid = $tempstep->id;
                $step->timecreated = time();
                $step->timemodified = time();
                $step->id = $DB->insert_record('block_onb_s_current', $step);

                $returnstepid = $step->stepid;
            }
        } else {
            $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
            $returnstepid = $step->stepid;
        }

        return $returnstepid;
    }

    public static function set_current_user_stepid($stepid) {
        global $DB, $USER;

        // evtl. durch Funktion get_current_user_step (den ganzen Schritt) ersetzen
        $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
        $step->stepid = $stepid;
        $step->timemodified = time();

        $step = $DB->update_record('block_onb_s_current', $step);
    }

    public static function set_step_id_complete($stepid) {
        global $DB, $USER;

        // nur wenn Step noch nicht abgeschlossen wurde, wird dieser hinzugefügt, sonst passiert nichts
        $stepbool = $DB->record_exists('block_onb_s_completed', array('userid' => $USER->id, 'stepid' => $stepid));

        if ($stepbool == false) {
            $step = new \stdClass();
            $step->stepid = $stepid;
            $step->userid = $USER->id;
            $step->id = $DB->insert_record('block_onb_s_completed', $step);
        }
    }

    public static function get_step_id($position) {
        global $DB;

        $step = $DB->get_record('block_onb_s_steps', array('position' => $position));
        $returnstepid = $step->id;

        return $returnstepid;
    }


    public static function get_step_position($stepid) {
        global $DB;

        $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
        $returnstepposition = $step->position;

        return $returnstepposition;
    }


    public static function get_next_step_data($position, $direction) {
        global $DB;
        $position = $position + $direction;
        $stepbool = $DB->record_exists('block_onb_s_steps', array('position' => $position));
        if ($stepbool) {
            $returnstep = $DB->get_record('block_onb_s_steps', array('position' => $position));
        } else {
            $returnstep = -1;
        }
        return $returnstep;
    }

    public static function get_step_data($position) {
        global $DB;

        $returnstep = $DB->get_record('block_onb_s_steps', array('position' => $position));

        return $returnstep;
    }

    public static function get_user_progress() {
        global $DB, $USER;

        $totalsteps = $DB->count_records('block_onb_s_steps');
        $usercompletedsteps = $DB->count_records('block_onb_s_completed', array('userid' => $USER->id));

        $returnprogress = (int)round(($usercompletedsteps / $totalsteps) * 100);

        return $returnprogress;
    }

    public static function get_user_completed_step($stepid) {
        global $DB, $USER;

        $progress = $DB->get_records('block_onb_s_completed', array('userid' => $USER->id));

        $returncompleted = 0;
        foreach ($progress as $prostep)
            if ($prostep->stepid == $stepid){
                $returncompleted = 1;
            }

        return $returncompleted;
    }


    public static function message_no_steps() {
        $returnstep['name'] = 'NO STEPS TO DISPLAY!';
        $returnstep['description'] =
            'There are currently no steps saved in the database. 
            Please add steps in the admin section or contact an administrator.';
        $returnstep['position'] = 0;
        $returnstep['achievement'] = 0;
        $returnstep['progress'] = 0;
        $returnstep['completed'] = 0;

        return $returnstep;
    }
}
