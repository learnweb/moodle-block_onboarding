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
 * External API for block_onboarding.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class implementing the external API, especially for AJAX functions.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_onboarding_view_external extends external_api {

    /**
     * Initializes the user's First Steps section upon loading the Guide page.
     * Utilizes the {@see \block_onboarding\steps_interaction_lib} to generate content.
     *
     * @return object Object containing content to initialize First Steps section.
     */
    public static function init_step() {
        global $DB, $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::init_step_parameters(),
            array()
        );

        // Gets current user step and checks whether any steps exist in the database.
        $curstepid = \block_onboarding\steps_interaction_lib::get_current_user_stepid();
        if ($curstepid == -1) {
            // Returns dummy object to display instead of step when there are no steps saved in the database.
            return \block_onboarding\steps_interaction_lib::message_no_steps();
        } else {
            // Collects relevant data for First Steps section.
            $curposition = \block_onboarding\steps_interaction_lib::get_step_position($curstepid);
            $step = \block_onboarding\steps_interaction_lib::get_step_data($curposition);
            $progress = \block_onboarding\steps_interaction_lib::get_user_progress();
            $hascompletedstep = \block_onboarding\steps_interaction_lib::get_user_completed_step($step->id);
            $visibility = $DB->get_record('block_onb_s_current', array('userid' => $USER->id))->showsteps;

            // Return object generation.
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['position'] = $step->position;
            $returnstep['achievement'] = $step->achievement;
            $returnstep['progress'] = $progress;
            $returnstep['hascompletedstep'] = $hascompletedstep;
            $returnstep['visibility'] = $visibility;
            return $returnstep;
        }
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function init_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns result of the step initialization process for the user's First Steps section initialization.
     *
     * @return external_single_structure
     */
    public static function init_step_returns() {
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of new step'),
                'description' => new external_value(PARAM_TEXT, 'description of new step'),
                'position' => new external_value(PARAM_INT, 'position of new step'),
                'achievement' => new external_value(PARAM_INT, 'determines whether a step is an achievement'),
                'progress' => new external_value(PARAM_INT, 'progress of user'),
                'hascompletedstep' => new external_value(PARAM_INT, 'determines whether user already completed step'),
                'visibility' => new external_value(PARAM_INT, 'indicates visibility of First Steps section')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Generates content of following step for user's First Steps section upon clicking the 'Done'-button.
     * Utilizes the {@see \block_onboarding\steps_interaction_lib} to generate content.
     *
     * @return object Object containing content for the First Steps section.
     */
    public static function next_step() {

        // Parameter validation.
        $params = self::validate_parameters(self::next_step_parameters(),
            array()
        );

        // Gets current user step and checks whether any steps exist in the database.
        $curstepid = \block_onboarding\steps_interaction_lib::get_current_user_stepid();
        if ($curstepid == -1) {
            // Returns dummy object to display instead of step when there are no steps saved in the database.
            return \block_onboarding\steps_interaction_lib::message_no_steps();
        } else {
            // Collects relevant data for the next step in the First Steps section.
            $curposition = \block_onboarding\steps_interaction_lib::get_step_position($curstepid);
            $step = \block_onboarding\steps_interaction_lib::get_next_step_data($curposition, 1);
            $progress = \block_onboarding\steps_interaction_lib::get_user_progress();

            // Checks whether next step is out of bounds.
            if ($step == -1) {
                // Uses current step as next step when out of bounds.
                $step = \block_onboarding\steps_interaction_lib::get_step_data($curposition);
            } else {
                // Sets next step to current user step when not out of bounds.
                \block_onboarding\steps_interaction_lib::set_current_user_stepid($step->id);
            }
            // Sets step to completed if step has not been completed before.
            \block_onboarding\steps_interaction_lib::set_user_completed_step($curposition);
            // Checks whether next step has been completed before.
            $completed = \block_onboarding\steps_interaction_lib::get_user_completed_step($step->id);

            // Return object generation.
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
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function next_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns result of the next step process for the user's First Steps section.
     *
     * @return external_single_structure
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

    /**
     * Generates content of preceding step for user's First Steps section upon clicking the 'Back'-button.
     * Utilizes the {@see \block_onboarding\steps_interaction_lib} to generate content.
     *
     * @return object Object containing content for the First Steps section.
     */
    public static function preceding_step() {

        // Parameter validation.
        $params = self::validate_parameters(self::preceding_step_parameters(),
            array()
        );

        // Gets current user step and checks whether any steps exist in the database.
        $curstepid = \block_onboarding\steps_interaction_lib::get_current_user_stepid();
        if ($curstepid == -1) {
            // Returns dummy object to display instead of step when there are no steps saved in the database.
            return \block_onboarding\steps_interaction_lib::message_no_steps();
        } else {
            // Collects relevant data for the preceding step in the First Steps section.
            $curposition = \block_onboarding\steps_interaction_lib::get_step_position($curstepid);
            $step = \block_onboarding\steps_interaction_lib::get_next_step_data($curposition, -1);

            // Checks whether next step is out of bounds.
            if ($step == -1) {
                // Uses current step as preceding step when out of bounds.
                $step = \block_onboarding\steps_interaction_lib::get_step_data($curstepid);
            } else {
                // Sets preceding step to current user step when not out of bounds.
                \block_onboarding\steps_interaction_lib::set_current_user_stepid($step->id);
            }
            // Checks whether preceding step has been completed before.
            $completed = \block_onboarding\steps_interaction_lib::get_user_completed_step($step->id);

            // Return object generation.
            $returnstep['name'] = $step->name;
            $returnstep['description'] = $step->description;
            $returnstep['achievement'] = $step->achievement;
            $returnstep['position'] = $step->position;
            $returnstep['completed'] = $completed;
            return $returnstep;
        }
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function preceding_step_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns result of the back step process for the user's First Steps section.
     *
     * @return external_single_structure
     */
    public static function preceding_step_returns() {
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
     * Resets user's First Steps progress upon clicking the 'Reset Progress'-button after completing all steps
     * in the First Steps section.
     *
     * @return int Reset confirmation.
     */
    public static function reset_progress() {
        global $DB, $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::reset_progress_parameters(),
            array()
        );

        // Deletes all steps from the user's step progress table and resets the currently displayed user step.
        $DB->delete_records('block_onb_s_completed', array('userid' => $USER->id));
        $DB->delete_records('block_onb_s_current', array('userid' => $USER->id));

        // Confirmation value generation.
        $returnvalue['confirmation'] = 1;
        return $returnvalue;
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function reset_progress_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * Returns result of the user progress reset for the user's First Steps section.
     *
     * @return external_single_structure
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
     * Toggles the visibility of the user's First Steps section upon clicking the 'Hide'- or 'Show'-Button.
     *
     * @param int $visibility Trivalent logic visibility selector.
     * @return int Updated visibility value.
     */
    public static function toggle_visibility($visibility) {
        global $DB, $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::toggle_visibility_parameters(),
            array(
                'visibility' => $visibility
            )
        );

        // Gets current visibility value.
        $record = $DB->get_record('block_onb_s_current', array('userid' => $USER->id));
        $record->timemodified = time();

        // Updates First Steps section visibility according to passed visibility parameter.
        if ($visibility == 0) {
            // Visibility - hide.
            $record->showsteps = 0;
            $DB->update_record('block_onb_s_current', $record);
        } else {
            if ($visibility == 1) {
                // Visibility - show.
                $record->showsteps = 1;
                $DB->update_record('block_onb_s_current', $record);
            }
        }
        // All other inputs just return the passed visibility parameter which will be the case when no steps are saved
        // in the database as there will be no currently displayed user step to update.
        // Return object generation.
        $returnvalue['return_visibility'] = $record->showsteps;
        return $returnvalue;
    }

    /**
     * Returns description of method parameters.
     *
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
     * Returns result of the visibility toggle process for the user's First Steps section.
     *
     * @return external_single_structure
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
     * Initializes the 'Helpful'-Button for the user when viewing an experience report depending on whether the
     * user has marked the experience report as helpful or not.
     *
     * @param int $experienceid Id of experience report.
     * @return string welcome message
     */
    public static function init_helpful($experienceid) {
        global $DB, $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::init_helpful_parameters(),
            array(
                'experienceid' => $experienceid
            )
        );

        // Checks whether user has marked the experience report as helpful.
        $alreadyhelpful = $DB->record_exists('block_onb_e_helpful',
            array('user_id' => $USER->id, 'experience_id' => $experienceid));
        if ($alreadyhelpful) {
            $returnhelpful['alreadyhelpful'] = 1;
            return $returnhelpful;
        } else {
            $returnhelpful['alreadyhelpful'] = 0;
            return $returnhelpful;
        }
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function init_helpful_parameters() {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'id of experience')
            )
        );
    }

    /**
     * Returns result of the helpfulness initialization for the Experiences section.
     *
     * @return external_single_structure
     */
    public static function init_helpful_returns() {
        return new external_single_structure(
            array(
                'alreadyhelpful' => new external_value(PARAM_INT, 'determines whether user has marked experience as helpful')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Toggles user's helpfulness rating for experience report upon clicking "Helpful"-button depending on whether
     * the user has rated the experience report as helpful before or not.
     *
     * @param int $experienceid Id of experience report.
     * @return int Visibility change confirmation.
     */
    public static function click_helpful($experienceid) {
        global $DB, $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::click_helpful_parameters(),
            array(
                'experienceid' => $experienceid
            )
        );

        // Checks whether user has marked the experience report as helpful.
        $alreadyhelpful = $DB->record_exists('block_onb_e_helpful',
            array('user_id' => $USER->id, 'experience_id' => $experienceid));
        if ($alreadyhelpful) {
            // Remove helpfulness entry from database.
            $DB->delete_records('block_onb_e_helpful', array('user_id' => $USER->id, 'experience_id' => $experienceid));
            $returnhelpful['alreadyhelpful'] = 0;
            return $returnhelpful;
        } else {
            // Insert helpfulness entry in database.
            $helpful = new stdClass();
            $helpful->experience_id = $experienceid;
            $helpful->user_id = $USER->id;
            $helpful->id = $DB->insert_record('block_onb_e_helpful', $helpful);
            $returnhelpful['alreadyhelpful'] = 1;
            return $returnhelpful;
        }
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function click_helpful_parameters() {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'id of experience')
            )
        );
    }

    /**
     * Returns result of the helpfulness processing for the Experiences section.
     *
     * @return external_single_structure
     */
    public static function click_helpful_returns() {
        return new external_single_structure(
            array(
                'alreadyhelpful' => new external_value(PARAM_INT, 'confirms that visibility was toggled')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Generates text to display in a confirmation prompt popup upon clicking an HTML tag with the 'confirm-btn' CSS class.
     * Previously set HTML variables determine the type of prompt to be generated and the id of the object.
     * Mostly used for warning messages when deleting content such as Steps, Wiki Categories or Experiences and may more use cases.
     *
     * @param int $type Type of prompt to be generated.
     * @param int $id Id of the object.
     * @return string Popup message.
     */
    public static function generate_confirmation($type, $id) {
        global $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::generate_confirmation_parameters(),
            array(
                'type' => $type,
                'id' => $id
            )
        );

        // Context Validation.
        $context = context_system::instance();
        self::validate_context($context);

        // Determines which type of warning message is to be generated. In some cases the warning message includes additional
        // information about the consequences of performing the type of action on the passed object.
        // Security checks are performed to determine whether the user is allowed to execute the type of action.
        switch ($type) {
            case 'step':
                require_capability('block/onboarding:s_manage_steps', $context);
                $returnmessage['text'] = get_string('msg_delete_step_warning', 'block_onboarding') . "sadsa " . $context->name;
                break;
            case 'wiki-category':
                require_capability('block/onboarding:w_manage_wiki', $context);
                $affected = $DB->count_records('block_onb_w_links', array('category_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_wiki_cat_warning', 'block_onboarding') . $affected .
                    get_string('msg_delete_wiki_cat_lost', 'block_onboarding');
                break;
            case 'wiki-link':
                require_capability('block/onboarding:w_manage_wiki', $context);
                $returnmessage['text'] = get_string('msg_delete_wiki_link_warning', 'block_onboarding');
                break;
            case 'exp-category':
                $affected = $DB->count_records('block_onb_e_exps_cats', array('category_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_exp_cats_warning', 'block_onboarding') . $affected .
                    get_string('msg_delete_exp_cats_lost', 'block_onboarding');
                break;
            case 'exp-course':
                require_capability('block/onboarding:e_manage_experiences', $context);
                $affected = $DB->count_records('block_onb_e_exps', array('course_id' => $id));
                $returnmessage['text'] = get_string('msg_delete_exp_course_warning', 'block_onboarding') . $affected .
                    get_string('msg_delete_exp_course_lost', 'block_onboarding');
                break;
            case 'exp-my-exp':
                $returnmessage['text'] = get_string('msg_delete_exp_exp_student_warning', 'block_onboarding');
                break;
            case 'exp-exp':
                $returnmessage['text'] = get_string('msg_delete_exp_exp_admin_warning', 'block_onboarding');
                break;
            case 'exp-exp-blacklist':
                require_capability('block/onboarding:e_manage_experiences', $context);
                $returnmessage['text'] = get_string('msg_delete_exp_exp_blacklist_warning', 'block_onboarding');
                break;
            case 'exp-admin-report':
                require_capability('block/onboarding:e_manage_experiences', $context);
                $returnmessage['text'] = get_string('msg_delete_exp_admin_report_warning', 'block_onboarding');
                break;
            case 'exp-admin-unblock':
                require_capability('block/onboarding:e_manage_experiences', $context);
                $returnmessage['text'] = get_string('msg_delete_exp_admin_unblock_warning', 'block_onboarding');
                break;
        }
        return $returnmessage;
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function generate_confirmation_parameters() {
        return new external_function_parameters(
            array(
                'type' => new external_value(PARAM_TEXT, 'type of prompt to be generated'),
                'id' => new external_value(PARAM_INT, 'id of the object'),
            )
        );
    }

    /**
     * Returns result of the confirmation message processing to the user's confirm box popup.
     *
     * @return external_single_structure
     */
    public static function generate_confirmation_returns() {
        return new external_single_structure(
            array(
                'text' => new external_value(PARAM_TEXT, 'information about number of data entries that will be deleted')
            )
        );
    }

    /* --------------------------------------------------------------------------------------------------------- */

    /**
     * Executes predefined action depending on the type after the user has confirmed the prompt within the confirmation popup.
     * After executing the action the method will return redirection instructions depending on the type of action.
     * The parameters are passed on from the {@see generate_confirmation()} through the confirmation_popup.js JavaScript file.
     *
     * @param int $type Type of action to be executed.
     * @param int $id Id of the object.
     * @return string Redirect instructions.
     */
    public static function execute_confirmation($type, $id) {

        // Parameter validation.
        $params = self::validate_parameters(self::execute_confirmation_parameters(),
            array(
                'type' => $type,
                'id' => $id
            )
        );

        // Context Validation.
        $context = context_system::instance();
        self::validate_context($context);

        // Determines which type of action is to be executed.
        // Security checks are performed to determine whether the user is allowed to execute the type of action.
        switch ($type) {
            case 'step':
                require_capability('block/onboarding:s_manage_steps', $context);
                \block_onboarding\steps_lib::delete_step($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'wiki-category':
                require_capability('block/onboarding:w_manage_wiki', $context);
                \block_onboarding\wiki_lib::delete_category($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'wiki-link':
                require_capability('block/onboarding:w_manage_wiki', $context);
                \block_onboarding\wiki_lib::delete_link($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'exp-category':
                require_capability('block/onboarding:e_manage_experiences', $context);
                \block_onboarding\experiences_lib::delete_category($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'exp-course':
                require_capability('block/onboarding:e_manage_experiences', $context);
                \block_onboarding\experiences_lib::delete_course($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'exp-my-exp':
                block_onboarding\experiences_lib::delete_experience($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'exp-exp':
                block_onboarding\experiences_lib::delete_experience($id);
                $returnvalue['redirect'] = '../experiences/overview.php';
                break;
            case 'exp-exp-blacklist':
                require_capability('block/onboarding:e_manage_experiences', $context);
                block_onboarding\experiences_lib::block_user($id);
                block_onboarding\experiences_lib::delete_experience($id);
                $returnvalue['redirect'] = '../experiences/overview.php';
                break;
            case 'exp-admin-report':
                require_capability('block/onboarding:e_manage_experiences', $context);
                block_onboarding\experiences_lib::delete_report($id);
                $returnvalue['redirect'] = 'reload';
                break;
            case 'exp-admin-unblock':
                require_capability('block/onboarding:e_manage_experiences', $context);
                block_onboarding\experiences_lib::unblock_user($id);
                $returnvalue['redirect'] = 'reload';
                break;
        }
        return $returnvalue;
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_confirmation_parameters() {
        return new external_function_parameters(
            array(
                'type' => new external_value(PARAM_TEXT, 'type of action to be executed'),
                'id' => new external_value(PARAM_INT, 'id of the object'),
            )
        );
    }

    /**
     * Returns result of the user confirmation popup prompt processing.
     *
     * @return external_single_structure
     */
    public static function execute_confirmation_returns() {
        return new external_single_structure(
            array(
                'redirect' => new external_value(PARAM_TEXT, 'link of page redirect, reload refers to reloading the initial page')
            )
        );
    }
}
