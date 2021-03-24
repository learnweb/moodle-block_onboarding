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
 * The file for the steps_lib class.
 * Contains static methods for Steps administration.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

/**
 * Static methods for Steps administration.
 */
class steps_lib {

    /**
     * Determines whether an existing step is updated or a new step is added.
     * Calls {@see add_step()} for new steps and {@see update_step()} to update an existing step.
     *
     * @param object $fromform Form parameters passed from edit_step.php.
     */
    public static function edit_step($fromform) {
        // Translates form data to new object for further processing.
        $step = new \stdClass();
        $step->name = $fromform->name;
        $step->description = $fromform->description;
        $step->achievement = isset($fromform->achievement) ? 1 : 0;
        $step->position = $fromform->position + 1;

        // Checks whether a new step is added.
        if ($fromform->id != -1) {
            $step->id = $fromform->id;
            self::update_step($step);
        } else {
            self::add_step($step);
        }
    }

    /**
     * Inserts a new step into the database.
     * Calls {@see increment_step_positions()} to update positions of other steps if necessary.
     *
     * @param object $step Step object with form parameters.
     */
    public static function add_step($step) {
        global $DB;
        // Step is added at last step position at first.
        $initposition = $DB->count_records('block_onb_s_steps') + 1;
        $insertposition = $step->position;
        $step->position = $initposition;
        $step->timecreated = time();
        $step->timemodified = time();
        $step->id = $DB->insert_record('block_onb_s_steps', $step);

        // Checks whether intended step position differs from max step position and updates affected step positions accordingly.
        // TODO check this before writing to database -> avoid insertion plus update
        if ($initposition != $insertposition) {
            self::increment_step_positions($insertposition, $initposition);
            $step->position = $insertposition;
            $step->timemodified = time();
            $DB->update_record('block_onb_s_steps', $step);
        }
    }

    /**
     * Updates an existing step in the database.
     * Calls {@see decrement_step_positions()} or {@see increment_step_positions()} to update positions of other steps if necessary.
     *
     * @param object $step Step object with form parameters.
     */
    public static function update_step($step) {
        global $DB;
        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $step->id));
        $curposition = $paramstep->position;
        $insertposition = $step->position;

        // Checks whether intended step position differs from current step position and updates affected step positions accordingly.
        if ($insertposition > $curposition) {
            self::decrement_step_positions($insertposition, $curposition);
        } else {
            if ($insertposition < $curposition) {
                self::increment_step_positions($insertposition, $curposition);
            }
        }
        $step->timemodified = time();
        $DB->update_record('block_onb_s_steps', $step);
    }

    /**
     * Deletes an existing step from the database.
     * Calls {@see decrement_step_positions()} to update positions of remaining steps.
     *
     * @param int $stepid Id of step which is to be deleted.
     */
    public static function delete_step($stepid) {
        global $DB;
        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
        $curposition = $paramstep->position;
        $stepcount = $DB->count_records('block_onb_s_steps');
        self::decrement_step_positions($stepcount, $curposition);
        $DB->delete_records('block_onb_s_steps', array('id' => $stepid));

        // Get all data entries of users whose current step position was set to the now deleted step.
        $affectedusers = $DB->get_records('block_onb_s_current', array('stepid' => $stepid));
        $paramstep = $DB->get_record('block_onb_s_steps', array('position' => 1));
        // Checks whether step data table is now empty and updates affected user step positions to 1
        // or deletes all current user steps when no steps remain in the step data table.
        if ($paramstep != false) {
            foreach ($affectedusers as $currentuserstep) {
                $currentuserstep->stepid = $paramstep->id;
                $DB->update_record('block_onb_s_current', $currentuserstep);
            }
        } else {
            $DB->delete_records('block_onb_s_current', array('stepid' => $stepid));
        }
        // Deletes all completed steps user progress for deleted step.
        $DB->delete_records('block_onb_s_completed', array('stepid' => $stepid));
    }

    /**
     * Increments step positions between the $insert and $current step position, excluding the passed $current position.
     *
     * @param int $insert New step position.
     * @param int $cur Current step position.
     */
    public static function increment_step_positions($insert, $cur) {
        global $DB;
        $sql = "UPDATE {block_onb_s_steps} SET position = position +1 WHERE position >= :insert_pos and position < :cur_pos";
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }

    /**
     * Decrements step positions between the $current step and $insert position, excluding the passed $current position.
     *
     * @param int $insert New step position.
     * @param int $cur Current step position.
     */
    public static function decrement_step_positions($insert, $cur) {
        global $DB;
        $sql = "UPDATE {block_onb_s_steps} SET position = position -1 WHERE position > :cur_pos and position <= :insert_pos";
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }
}
