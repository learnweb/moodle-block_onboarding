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

class block_onboarding_view_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_step_parameters() {
        return new external_function_parameters(
            # userid wird auch erfolderlich sein für "richtige" Impl
            array(
                'stepid' => new external_value(PARAM_INT, 'id of step', VALUE_REQUIRED),
                'position' => new external_value(PARAM_INT, 'step position', VALUE_REQUIRED),
            )
        );
    }

    /**
     * The function itself
     * parameter erklären!!
     * @return string welcome message
     */
    public static function get_step($stepid, $position) {
        global $DB;
        $params = self::validate_parameters(self::get_step_parameters(),
            array(
                'stepid' => $stepid,
                'position' => $position,
            )
        );

        // CONTEXT & CAPABILITIES CHECKEN!

        $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid), $fields = '*', $strictness = IGNORE_MISSING);

        // Safety einügen fall step leer ist oder nicht gefunden wurde!
        // kürzer mit  = array_values($step); ???
        $next_step['id'] = $step->id;
        $next_step['name'] = $step->name;
        $next_step['description'] = $step->description;
        $next_step['position'] = $step->position;

        return $next_step;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_step_returns() {
//        return new external_multiple_structure(
            return new external_single_structure(
                array(
                    'id'            => new external_value(PARAM_INT, 'new step id'),
                    'name'          => new external_value(PARAM_TEXT, 'name of new step'),
                    'description'   => new external_value(PARAM_TEXT, 'description of new step'),
                    'position'      => new external_value(PARAM_INT, 'position of new step'),
                )
//            )
        );
    }



}