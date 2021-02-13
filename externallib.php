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


class block_onboarding_view_external extends external_api {

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function init_step() {

        $params = self::validate_parameters(self::init_step_parameters(),
            array()
        );

        // Aktuelle step_id vom User abfragen
        $curstepid = \block_onboarding\step_view_data_functions::get_current_user_stepid();

        // Wenn kein Schritt in der Datenbank existiert
        if ($curstepid == -1) {
            return \block_onboarding\step_view_data_functions::message_no_steps();
        } else {
            // Position des aktuellen User Steps abfragen
            $curposition = \block_onboarding\step_view_data_functions::get_step_position($curstepid);
            // Daten des aktuellen Steps abfragen
            $step = \block_onboarding\step_view_data_functions::get_step_data($curposition);
            // Progress des Users abfragen
            $progress = \block_onboarding\step_view_data_functions::get_user_progress();

            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;
            $returnstep['progress'] = $progress;

            return $returnstep;
        }
    }

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function init_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function init_step_returns() {
        //return new external_multiple_structure(
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'progress' => new external_value(PARAM_INT, 'progress of user'),
            )
            //)
        );
    }


    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */
    public static function next_step() {

        $params = self::validate_parameters(self::next_step_parameters(),
            array()
        );

        // Aktuelle step_id vom User abfragen
        $curstepid = \block_onboarding\step_view_data_functions::get_current_user_stepid();

        // Wenn kein Schritt in der Datenbank existiert
        if ($curstepid == -1) {
            return \block_onboarding\step_view_data_functions::message_no_steps();
        } else {
            // Position des aktuellen User Steps abfragen
            $curposition = \block_onboarding\step_view_data_functions::get_step_position($curstepid);
            // Daten des nächsten Steps (cur_position + 1) abfragen
            $step = \block_onboarding\step_view_data_functions::get_next_step_data($curposition, 1);
            if ($step == -1) {
                $step = \block_onboarding\step_view_data_functions::get_step_data($curposition);
            } else {
                // Datenbank-Eintrag für User updaten mit neuem step
                \block_onboarding\step_view_data_functions::set_current_user_stepid($step->id);
            }
            //Markiert den Step als completed
            \block_onboarding\step_view_data_functions::set_step_id_complete($curposition);
            // berechnet Fortschritt des Nutzers
            $progress = \block_onboarding\step_view_data_functions::get_user_progress();

            // Rückgabe an JavaScript
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;
            $returnstep['progress'] = $progress;

            return $returnstep;
        }
    }

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function next_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */

    public static function next_step_returns() {
//        return new external_multiple_structure(
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'progress' => new external_value(PARAM_INT, 'progress of user'),
            )
//            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    public static function skip_step() {
        $params = self::validate_parameters(self::skip_step_parameters(),
            array()
        );

        // Aktuelle step_id vom User abfragen
        $curstepid = \block_onboarding\step_view_data_functions::get_current_user_stepid();

        // Wenn kein Schritt in der Datenbank existiert
        if ($curstepid == -1) {
            return \block_onboarding\step_view_data_functions::message_no_steps();
        } else {
            // Position des aktuellen User Steps abfragen
            $curposition = \block_onboarding\step_view_data_functions::get_step_position($curstepid);
            // Daten des nächsten Steps (cur_position + 1) abfragen

            $step = \block_onboarding\step_view_data_functions::get_next_step_data($curposition, 1);
            if ($step == -1) {
                $step = \block_onboarding\step_view_data_functions::get_step_data($curposition);
            } else {
                // Datenbank-Eintrag für User updaten mit neuem step
                \block_onboarding\step_view_data_functions::set_current_user_stepid($step->id);
            }
            // Rückgabe an JavaScript
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;

            return $returnstep;
        }
    }

    public static function skip_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function skip_step_returns() {
//        return new external_multiple_structure(
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
            )
//            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    public static function back_step() {
        $params = self::validate_parameters(self::back_step_parameters(),
            array()
        );
        // Aktuelle step_id vom User abfragen
        $curstepid = \block_onboarding\step_view_data_functions::get_current_user_stepid();

        // Wenn kein Schritt in der Datenbank existiert
        if ($curstepid == -1) {
            return \block_onboarding\step_view_data_functions::message_no_steps();
        } else {
            // Position des aktuellen User Steps abfragen
            $cur_position = \block_onboarding\step_view_data_functions::get_step_position($curstepid);
            // Daten des vorherigen Steps (cur_position - 1) abfragen
            $step = \block_onboarding\step_view_data_functions::get_next_step_data($curstepid, -1);
            if ($step == -1) {
                //step aus if raus?
                $step = \block_onboarding\step_view_data_functions::get_step_data($curstepid);
            } else {
                // Datenbank-Eintrag für User updaten mit neuem step
                \block_onboarding\step_view_data_functions::set_current_user_stepid($step->id);
            }
            // Rückgabe an JavaScript
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;

            return $returnstep;
        }
    }

    public static function back_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function back_step_returns() {
//        return new external_multiple_structure(
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
            )
//            )
        );
    }

}