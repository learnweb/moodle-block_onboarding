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

// TODO: Randfälle behandeln, z.B. letzter Schritt in, keine Schritte in Liste, usw.

class step_view_data_functions {

    /**
     * Beschreibung hinzufügen!
     */

    // !!! ÜBERGABE DER USERID HIER UND in JS ggf. ÜBERFLÜSSIG WG. $USER->ID ? -> prüfen
    // evtl. Komplikationen wegen Kontexten von verschiedenen Nutzern? -> eher nicht (?)

    public static function get_current_user_stepid($userid) {
        global $DB;

        $step_bool = $DB->record_exists('block_onb_s_current', array('userid' => $userid));

        // wenn noch kein Fortschritt gemacht wurde, also kein Datensatz vorhanden ist -> starten bei pos = 1
        if($step_bool == false){
            $temp_step = $DB->get_record('block_onb_s_steps', array('position' => 1), $fields = '*', $strictness = IGNORE_MISSING);

            $step = new \stdClass();
            $step->userid = $userid;
            $step->stepid = $temp_step->id;
            $step->timecreated = time();
            $step->timemodified = time();
            $step->id = $DB->insert_record('block_onb_s_current', $step);

            $return_stepid = $step->stepid;
        } else {
            $step = $DB->get_record('block_onb_s_current', array('userid' => $userid), $fields = '*', $strictness = IGNORE_MISSING);
            $return_stepid = $step->id;
        }

        return $return_stepid;
    }


    public static function get_step_id($position) {
        global $DB;

        $step = $DB->get_record('block_onb_s_steps', array('position' => $position), $fields = '*', $strictness = IGNORE_MISSING);
        $return_stepid = $step->id;

        return $return_stepid;
    }


    public static function get_step_position($stepid) {
        global $DB;

        $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid), $fields = '*', $strictness = IGNORE_MISSING);
        $return_step_position = $step->position;

        return $return_step_position;
    }


    public static function get_step_data($position) {
        global $DB;

        $return_step = $DB->get_record('block_onb_s_steps', array('position' => $position), $fields = '*', $strictness = IGNORE_MISSING);

        return $return_step;
    }
}