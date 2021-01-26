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
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function init_step_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'id of user', VALUE_REQUIRED),
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */
    public static function init_step($userid) {

        $params = self::validate_parameters(self::init_step_parameters(),
            array(
                'userid' => $userid,
            )
        );

        // Aktuelle step_id vom User abfragen
        $cur_stepid = \block_onboarding\step_view_data_functions::get_current_user_stepid($userid);
        // Position des aktuellen User Steps abfragen
        $cur_position = \block_onboarding\step_view_data_functions::get_step_position($cur_stepid);
        // Daten des aktuellen Steps abfragen
        $step = \block_onboarding\step_view_data_functions::get_step_data($cur_position);

        $return_step['name'] = $step->name;
        $return_step['description'] = $step->description;
        $return_step['position'] = $step->position;

        return $return_step;
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function init_step_returns() {
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
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function next_step_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'id of user', VALUE_REQUIRED),
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */
    public static function next_step($userid) {

        $params = self::validate_parameters(self::next_step_parameters(),
            array(
                'userid' => $userid,
            )
        );

        // Aktuelle step_id vom User abfragen
        $cur_stepid = \block_onboarding\step_view_data_functions::get_current_user_stepid($userid);
        // Position des aktuellen User Steps abfragen
        $cur_position = \block_onboarding\step_view_data_functions::get_step_position($cur_stepid);
        // Daten des nächsten Steps (cur_position + 1) abfragen
        $step = \block_onboarding\step_view_data_functions::get_step_data($cur_position + 1);

        $return_step['name'] = $step->name;
        $return_step['description'] = $step->description;
        $return_step['position'] = $step->position;

        return $return_step;
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
                'name'          => new external_value(PARAM_TEXT, 'name of new step'),
                'description'   => new external_value(PARAM_TEXT, 'description of new step'),
                'position'      => new external_value(PARAM_INT, 'position of new step'),
            )
//            )
        );
    }

}