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
 * PLUGIN external file
 *
 * @package    block_onboarding
 * @category   external
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

// TODO: Randfälle behandeln, z.B. letzter Schritt in Liste, keine Schritte in Liste, usw.
// TODO: Weitere erforderliche Funktionen hinzufügen, siehe steps_view.js Datei für Details

class block_onboarding_view_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_step_info_parameters() {
        return new external_function_parameters(
            array(
                'stepid' => new external_value(PARAM_INT, 'id of step', VALUE_REQUIRED),
                'position' => new external_value(PARAM_INT, 'step position', VALUE_REQUIRED)
            )
        );
    }

    /**
     * The function itself
     * parameter erklären!!
     * @return string welcome message
     */
    public static function get_step_info($stepid, $position) {
        global $DB;

        $params = self::validate_parameters(self::get_step_info_parameters(),
            array(
                'stepid' => $stepid,
                'position' => $position
            )
        );

        // wenn step nur über id gesucht wird (also bspw. bei erstmaliger Registierung des Users für ersten Schritt)
        if($stepid >= 0 and $position = -1) {
            $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid), $fields = '*', $strictness = IGNORE_MISSING);
        // andernfalls wenn step nur über position gesucht wird (also bspw. nächster Schritt mit cur_position +1)
        } else if($stepid = -1 and $position >= 0){
            $step = $DB->get_record('block_onb_s_steps', array('position' => $position), $fields = '*', $strictness = IGNORE_MISSING);
        } else {
            throw new Exception('Invalid function parameters!');
        }

        $return_step['name'] = $step->name;
        $return_step['description'] = $step->description;
        $return_step['position'] = $step->position;

        return $return_step;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_step_info_returns() {
//        return new external_multiple_structure(
            return new external_single_structure(
                array(
                    'name'          => new external_value(PARAM_TEXT, 'name of new step'),
                    'description'   => new external_value(PARAM_TEXT, 'description of new step'),
                    'position'      => new external_value(PARAM_INT, 'position of new step'),
                )
//            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_current_user_step_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'id of user', VALUE_REQUIRED),
            )
        );
    }

    /**
     * The function itself
     * parameter erklären!!
     * @return string welcome message
     */
    public static function get_current_user_step($userid) {
        global $DB;

        $params = self::validate_parameters(self::get_current_user_step_parameters(),
            array(
                'userid' => $userid
            )
        );

        // was returned record_exisits?? try/catch erforderlich? sollte record_exists nicht boolean returnen?
//        $step_bool = true;
//        try{
//            $step_bool = $DB->record_exists('block_onb_s_current', array('id' => $userid));
//        } catch(Exception $e) {
//            $step_bool = false;
//        }
        $step_bool = $DB->record_exists('block_onb_s_current', array('userid' => $userid));

        // wenn noch kein Fortschritt gemacht wurde, also kein Datensatz vorhanden ist -> starten bei pos = 1
        if($step_bool == false){
            $temp_step = $DB->get_record('block_onb_s_steps', array('position' => 1), $fields = '*', $strictness = IGNORE_MISSING);

            $step =  $step = new stdClass();
            $step->userid = $userid;
            $step->stepid = $temp_step->id;
            $step->timecreated = time();
            $step->timemodified = time();
            $step->id = $DB->insert_record('block_onb_s_current', $step);

            $return_step['stepid'] = $step->stepid;
        } else {
            $step = $DB->get_record('block_onb_s_current', array('userid' => $userid), $fields = '*', $strictness = IGNORE_MISSING);
            $return_step['stepid'] = $step->id;
        }

        return $return_step;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_current_user_step_returns() {
//        return new external_multiple_structure(
        return new external_single_structure(
            array(
                'stepid'            => new external_value(PARAM_INT, 'step id of current user step')
            )
//            )
        );
    }


}