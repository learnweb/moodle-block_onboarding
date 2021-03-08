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
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function init_step() {
        global $DB, $USER;

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
            // Prüfen, ob step schon completed wurde
            $completed = \block_onboarding\step_view_data_functions::get_user_completed_step($step->id);
            // visibility prüfen
            $visibility = $DB->get_record('block_onb_s_current', array('userid' => $USER->id))->showsteps;

            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;
            $returnstep['achievement'] = $step->achievement;
            $returnstep['progress'] = $progress;
            $returnstep['completed'] = $completed;
            $returnstep['visibility'] = $visibility;

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
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'achievement' => new external_value(PARAM_INT, 'determines whether a step is an achievement'),
                'progress' => new external_value(PARAM_INT, 'progress of user'),
                'completed' => new external_value(PARAM_INT, 'determines whether user already completed step'),
                'visibility' => new external_value(PARAM_INT, 'indicates visibility of First Steps section')
            )
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
            //Markiert den vorherigen Step als completed
            \block_onboarding\step_view_data_functions::set_step_id_complete($curposition);
            // Prüfen, ob step schon completed wurde
            $completed = \block_onboarding\step_view_data_functions::get_user_completed_step($step->id);
            // berechnet Fortschritt des Nutzers
            $progress = \block_onboarding\step_view_data_functions::get_user_progress();

            // Rückgabe an JavaScript
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;
            $returnstep['achievement'] = $step->achievement;
            $returnstep['progress'] = $progress;
            $returnstep['completed'] = $completed;

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
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'achievement' => new external_value(PARAM_INT, 'determines whether a step is an achievement'),
                'progress' => new external_value(PARAM_INT, 'progress of user'),
                'completed' => new external_value(PARAM_INT, 'determines whether user already completed step'),
            )
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
            $curposition = \block_onboarding\step_view_data_functions::get_step_position($curstepid);
            // Daten des vorherigen Steps (cur_position - 1) abfragen
            $step = \block_onboarding\step_view_data_functions::get_next_step_data($curposition, -1);
            if ($step == -1) {
                //step aus if raus?
                $step = \block_onboarding\step_view_data_functions::get_step_data($curstepid);
            } else {
                // Datenbank-Eintrag für User updaten mit neuem step
                \block_onboarding\step_view_data_functions::set_current_user_stepid($step->id);
            }
            // Prüfen, ob step schon completed wurde
            $completed = \block_onboarding\step_view_data_functions::get_user_completed_step($step->id);

            // Rückgabe an JavaScript
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['achievement'] = $step->achievement;
            $returnstep['position'] = $step->position;
            $returnstep['completed'] = $completed;

            return $returnstep;
        }
    }

    public static function back_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function back_step_returns() {
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'achievement' => new external_value(PARAM_INT, 'determines whether a step is an achievement'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'completed' => new external_value(PARAM_INT, 'determines whether user already completed step'),
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */
    public static function reset_progress() {
        global $DB, $USER;

        $params = self::validate_parameters(self::reset_progress_parameters(),
            array()
        );

        // Lösche alle steps und current step
        $DB->delete_records('block_onb_s_completed', array('userid' => $USER->id));
        $DB->delete_records('block_onb_s_current', array('userid' => $USER->id));

        $returnvalue['confirmation'] = 1;
        return $returnvalue;
    }

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function reset_progress_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */

    public static function reset_progress_returns() {
        return new external_single_structure(
            array(
                'confirmation' => new external_value(PARAM_INT, 'confirmation of deletion')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */
    public static function toggle_visibility($visibility) {
        global $DB, $USER;

        $params = self::validate_parameters(self::toggle_visibility_parameters(),
            array(
                'visibility' => $visibility
            )
        );

        $record = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
        $record->timemodified = time();

        if($visibility == 0) {
            $record->showsteps = 0;
            $DB->update_record('block_onb_s_current', $record);
        } else if($visibility == 1) {
            $record->showsteps = 1;
            $DB->update_record('block_onb_s_current', $record);
        }

        // all other inputs just return visibility
        $returnvalue['return_visibility'] = $record->showsteps;
        return $returnvalue;
    }

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function toggle_visibility_parameters() {
        return new external_function_parameters(
            array(
                'visibility' => new external_value(PARAM_INT, 'visibility = 0 -> hide, visibility = 1 -> show')
            )
        );
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */

    public static function toggle_visibility_returns() {
        return new external_single_structure(
            array(
                'return_visibility' => new external_value(PARAM_INT, 'indicates visibility of First Steps section')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function click_helpful_parameters() {
        return new external_function_parameters(
            array(
                'experience_id' => new external_value(PARAM_INT, 'id of experience')
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function click_helpful($experience_id) {

        global $DB, $USER;

        $params = self::validate_parameters(self::click_helpful_parameters(),
            array(
                'experience_id' => $experience_id
            )
        );

        // popularity prüfen
        $popularity = $DB->count_records('block_onb_e_helpful', array('experience_id' => $experience_id));

        // prüfen ob Report bereits markiert ist
        $already_helpful =
            $DB->record_exists('block_onb_e_helpful', array('user_id' => $USER->id, 'experience_id' => $experience_id));

        if ($already_helpful) {
            $DB->delete_records('block_onb_e_helpful', array('user_id' => $USER->id, 'experience_id' => $experience_id));

            $return_helpful['exists'] = 0;
            $return_helpful['popularity'] = $popularity - 1;
            return $return_helpful;
        } else {
            $helpful = new stdClass();
            $helpful->experience_id = $experience_id;
            $helpful->user_id = $USER->id;
            $helpful->id = $DB->insert_record('block_onb_e_helpful', $helpful);

            $return_helpful['exists'] = 1;
            $return_helpful['popularity'] = $popularity + 1;
            return $return_helpful;
        }
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function click_helpful_returns() {
        return new external_single_structure(
            array(
                'exists'      => new external_value(PARAM_INT, 'entry existence'),
                'popularity'      => new external_value(PARAM_INT, 'popularity of report')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function init_helpful_parameters() {
        return new external_function_parameters(
            array(
                'experience_id' => new external_value(PARAM_INT, 'id of experience')
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function init_helpful($experience_id) {

        global $DB, $USER;

        $params = self::validate_parameters(self::init_helpful_parameters(),
            array(
                'experience_id' => $experience_id
            )
        );

        // popularity prüfen
        $popularity = $DB->count_records('block_onb_e_helpful', array('experience_id' => $experience_id));
        $return_helpful['popularity'] = $popularity;

        // prüfen ob Report bereits markiert ist
        $already_helpful =
            $DB->record_exists('block_onb_e_helpful', array('user_id' => $USER->id, 'experience_id' => $experience_id));

        if ($already_helpful) {
            $return_helpful['exists'] = 1;
            return $return_helpful;
        } else {
            $return_helpful['exists'] = 0;
            return $return_helpful;
        }
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function init_helpful_returns() {
        return new external_single_structure(
            array(
                'exists'      => new external_value(PARAM_INT, 'entry existence'),
                'popularity'      => new external_value(PARAM_INT, 'popularity of report')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function delete_confirmation_parameters() {
        return new external_function_parameters(
            array(
                'context' => new external_value(PARAM_TEXT, 'object type which is to be deleted'),
                'id' => new external_value(PARAM_INT, 'data id which is to be deleted'),
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function delete_confirmation($context, $id) {
        global $DB;

        $params = self::validate_parameters(self::delete_confirmation_parameters(),
            array(
                'context' => $context,
                'id' => $id
            )
        );

        switch ($context) {
            case 'wiki-category':
                $affected = $DB->count_records('block_onb_w_links', array('category_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_steps_cats_warning', 'block_onboarding') . $affected . get_string('msg_delete_steps_cats_lost', 'block_onboarding');
                break;
            case 'exp-category':
                $affected = $DB->count_records('block_onb_e_exps_cats', array('category_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_exp_cats_warning', 'block_onboarding') . $affected . get_string('msg_delete_exp_cats_lost', 'block_onboarding');
                break;
            case 'exp-course':
                $affected = $DB->count_records('block_onb_e_exps', array('course_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_exp_course_warning', 'block_onboarding') . $affected . get_string('msg_delete_exp_course_lost', 'block_onboarding');
                break;
            case 'exp-exp':
                $affected = $DB->count_records('block_onb_e_exps_cats', array('experience_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_exp_exp_student_warning', 'block_onboarding');
                break;
        }
        return $returnmessage;
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function delete_confirmation_returns() {
        return new external_single_structure(
            array(
                'text'      => new external_value(PARAM_TEXT, 'information about number of data entries that will be deleted')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Returns description of method parameters
     * Parameter erklären!
     * @return external_function_parameters
     */
    public static function delete_entry_parameters() {
        return new external_function_parameters(
            array(
                'context' => new external_value(PARAM_TEXT, 'object type which is to be deleted'),
                'id' => new external_value(PARAM_INT, 'data id which is to be deleted'),
            )
        );
    }

    /**
     * The function itself
     * Parameter erklären!
     * @return string welcome message
     */

    public static function delete_entry($context, $id) {

        $params = self::validate_parameters(self::delete_entry_parameters(),
            array(
                'context' => $context,
                'id' => $id
            )
        );

        switch ($context) {
            case 'wiki-category':
                \block_onboarding\wiki_lib::delete_category($id);
                $returnvalue['confirmation'] = 1;
                break;
            case 'exp-category':
                \block_onboarding\experiences_lib::delete_category($id);
                $returnvalue['confirmation'] = 1;
                break;
            case 'exp-course':
                \block_onboarding\experiences_lib::delete_course($id);
                $returnvalue['confirmation'] = 1;
                break;
            case 'exp-exp':
                block_onboarding\experiences_lib::delete_experience($id);
                $returnvalue['confirmation'] = 1;
                break;
        }
        return $returnvalue;
    }

    /**
     * Returns description of method result value
     * Parameter erklären!
     * @return external_description
     */
    public static function delete_entry_returns() {
        return new external_single_structure(
            array(
                'confirmation' => new external_value(PARAM_INT, 'confirmation of deletion')
            )
        );
    }
}
