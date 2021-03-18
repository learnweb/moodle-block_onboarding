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
 * The file for the steps_interaction_lib class.
 * Contains static methods which outsource reoccurring functionality for externallib.php ajax calls.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;
defined('MOODLE_INTERNAL') || die();

/**
 * Static methods for outsourcing reoccurring functionality in externallib.php ajax calls.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class steps_interaction_lib {

    /**
     * Returns step id of the step which is currently displayed in the user's First Steps section.
     *
     * @return int Id of currently displayed step.
     */
    public static function get_current_user_stepid() {
        global $DB, $USER;
        // Checks whether the user has accessed the First Steps section before and adjusts return value accordingly.
        $stepbool = $DB->record_exists('block_onb_s_current', array('userid' => $USER->id));
        if ($stepbool == false) {
            // In case the user has never accessed the First Steps section before, check whether there are any steps saved
            // in the database. If this is the case, initlize the section with the step at position 1, otherwise return -1.
            if ($DB->count_records('block_onb_s_steps') == 0) {
                $returnstepid = -1;
            } else {
                $tempstep = $DB->get_record('block_onb_s_steps', array('position' => 1));
                $step = new \stdClass();
                $step->userid = $USER->id;
                $step->stepid = $tempstep->id;
                $step->showsteps = 1;
                $step->timecreated = time();
                $step->timemodified = time();
                $step->id = $DB->insert_record('block_onb_s_current', $step);
                $returnstepid = $step->stepid;
            }
        } else {
            // Load the saved current user step from the database.
            $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
            $returnstepid = $step->stepid;
        }
        return $returnstepid;
    }

    /**
     * Sets currently displayed step in the user's First Steps section to the passed step id.
     *
     * @param int $stepid Step id of step to be set as currently displayed user step.
     */
    public static function set_current_user_stepid($stepid) {
        global $DB, $USER;
        $step = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
        $step->stepid = $stepid;
        $step->timemodified = time();
        $DB->update_record('block_onb_s_current', $step);
    }

    /**
     * Returns step database entry for a given step position.
     *
     * @param int $position Position of step.
     * @return object Step database entry.
     */
    public static function get_step_data($position) {
        global $DB;
        $returnstep = $DB->get_record('block_onb_s_steps', array('position' => $position));
        return $returnstep;
    }

    /**
     * Returns step position for a given step id.
     *
     * @param int $stepid Id of step.
     * @return int Position of step.
     */
    public static function get_step_position($stepid) {
        global $DB;
        $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
        $returnstepposition = $step->position;
        return $returnstepposition;
    }

    /**
     * Returns step database entry for a given step position plus a given direction value.
     * This method is used to display a different step in the user's First Steps section upon clicking the 'Back'- or 'Done'-button.
     *
     * @param int $position Base position of step.
     * @param int $direction Direction value to be added to the base step position.
     * @return object Step database entry.
     */
    public static function get_next_step_data($position, $direction) {
        global $DB;
        // Direction value which is 1 upon clicking the 'Done'-button and -1 upon clicking the 'Back'-button is
        // added to the base step position.
        $position = $position + $direction;
        $stepbool = $DB->record_exists('block_onb_s_steps', array('position' => $position));
        // Checks whether the record exists and returns -1 when steps are out of bounds.
        if ($stepbool) {
            $returnstep = $DB->get_record('block_onb_s_steps', array('position' => $position));
        } else {
            $returnstep = -1;
        }
        return $returnstep;
    }

    /**
     * Checks whether a user has completed a given step in the First Steps section.
     *
     * @param int $stepid Id of step.
     * @return int Trivalent logic selector value.
     */
    public static function get_user_completed_step($stepid) {
        global $DB, $USER;
        $step = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
        $totalsteps = $DB->count_records('block_onb_s_steps');
        $progress = $DB->get_records('block_onb_s_completed', array('userid' => $USER->id));

        // Checks whether user has completed all steps and is currently at final step.
        if($step->position == $totalsteps and count($progress) == $totalsteps){
            $returncompleted = 2;
        } else{
            // Checks whether given step has already been completed by comparing the given step id with all
            // the user's completed step ids.
            $returncompleted = 0;
            foreach ($progress as $prostep)
                if ($prostep->stepid == $stepid){
                    $returncompleted = 1;
            }
        }
        return $returncompleted;
    }

    /**
     * Creates a new entry in the completed steps data table as long as the user has not yet completed the given step.
     * This method is called upon clicking the 'Done'-button in the First Steps section.
     *
     * @param int $stepid Step id of step to be set as completed.
     */
    public static function set_user_completed_step($stepid) {
        global $DB, $USER;
        $stepbool = $DB->record_exists('block_onb_s_completed', array('userid' => $USER->id, 'stepid' => $stepid));
        if ($stepbool == false) {
            $step = new \stdClass();
            $step->stepid = $stepid;
            $step->userid = $USER->id;
            $step->id = $DB->insert_record('block_onb_s_completed', $step);
        }
    }

    /**
     * Calculates the user progress percentage for the First Steps Section, which will then be displayed in the progress bar.
     *
     * @return int Rounded user progress percentage.
     */
    public static function get_user_progress() {
        global $DB, $USER;
        $totalsteps = $DB->count_records('block_onb_s_steps');
        $usercompletedsteps = $DB->count_records('block_onb_s_completed', array('userid' => $USER->id));
        $returnprogress = (int)round(($usercompletedsteps / $totalsteps) * 100);
        return $returnprogress;
    }

    /**
     * Creates a step object to be displayed in the First Steps section when there are no steps saved in the database.
     *
     * @return object Step object.
     */
    public static function message_no_steps() {
        $returnstep['name'] = get_string('error_nosteps_title', 'block_onboarding');
        $returnstep['description'] = get_string('error_nosteps_message', 'block_onboarding');
        $returnstep['position'] = 0;
        $returnstep['achievement'] = 0;
        $returnstep['progress'] = 0;
        $returnstep['completed'] = 0;
        $returnstep['visibility'] = -1;
        return $returnstep;
    }
}
