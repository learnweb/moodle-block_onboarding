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
 * The file for the experience_lib class.
 * Contains static methods for Experiences administration.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

/**
 * Static methods for Experiences administration.
 */
class experiences_lib {

    /**
     * Either edits an existing experience or creates a new one.
     *
     * @param object $fromform Form parameters passed from edit_experience.php.
     */
    public static function edit_experience($fromform) {
        global $DB;

        // Translates form data to new object for further processing.
        $experience = new \stdClass();
        $experience->name = $fromform->name;
        $experience->contact = isset($fromform->contact) ? $fromform->contact : null;
        if ($fromform->id == -1) {
            $experience->user_id = $fromform->user_id;
            $experience->timecreated = time();
        }

        $experience->course_id = $fromform->course_id;
        if (!empty($fromform->publish)) {
            $experience->published = 1;
        } else {
            if (!empty($fromform->draft)) {
                $experience->published = null;
            }
        }
        $experience->timemodified = time();

        if (isset($fromform->aboutme_text)) {
            $experience->aboutme = $fromform->aboutme_text;
        }
        if (isset($fromform->anonym)) {
            $experience->anonym = 1;
        }

        // Experience is either updated or newly created.
        if ($fromform->id != -1) {
            $experience->id = $fromform->id;
            $DB->update_record('block_onb_e_exps', $experience);
        } else {
            $experience->id = $DB->insert_record('block_onb_e_exps', $experience);
        }

        // Get all categories from the Database.
        $categories = $DB->get_records('block_onb_e_cats');
        $insertcategories = array();

        foreach ($categories as $category) {
            $formpropertycategorycheckbox = 'category_' . $category->id;
            $formpropertycategorytextarea = 'experience_category_' . $category->id . '_description';

            // Check whether the checkbox for a category was checked and something was written in the textarea.
            if (isset($fromform->$formpropertycategorycheckbox) &&
                empty($fromform->$formpropertycategorytextarea) == false) {
                // Translates form data to new object for further processing.
                $experiencecategory = new \stdClass;
                $formpropertycategorytextarea = 'experience_category_' . $category->id . '_description';
                $experiencecategory->description = $fromform->$formpropertycategorytextarea;
                $formpropertycategorytakeaway = 'experience_category_' . $category->id . '_takeaway';
                $experiencecategory->takeaway = $fromform->$formpropertycategorytakeaway;
                $experiencecategory->timemodified = time();

                // Checking whether there is already a database entry for this category.
                $contentcheck = $DB->get_record('block_onb_e_exps_cats',
                    array('experience_id' => $experience->id, 'category_id' => $category->id));

                if (empty($contentcheck)) {
                    // Adding information for a new database entry.
                    $experiencecategory->experience_id = $experience->id;
                    $experiencecategory->category_id = $category->id;
                    $experiencecategory->timecreated = time();
                    $insertcategories[] = $experiencecategory;
                } else {
                    // Updating an existing database entry.
                    $experiencecategory->id = $contentcheck->id;
                    $DB->update_record('block_onb_e_exps_cats', $experiencecategory);
                }

            } else {
                // Deleting all entries where either the checkbox was not clicked or there was no text.
                $DB->delete_records('block_onb_e_exps_cats',
                    array('experience_id' => $experience->id,
                        'category_id' => $category->id));
            }
        }
        // Creates new database entries for all filled out categories.
        $DB->insert_records('block_onb_e_exps_cats', $insertcategories);
    }

    /**
     * Deletes an existing category from the database.
     *
     * @param int $experienceid Id of experience which is to be deleted.
     */
    public static function delete_experience($experienceid) {
        global $DB;
        $DB->delete_records('block_onb_e_exps_cats', array('experience_id' => $experienceid));
        $DB->delete_records('block_onb_e_exps', array('id' => $experienceid));
    }

    /**
     * sets the experience report invisible and sends an email to the author.
     *
     * @param object $fromform Form parameters passed from suspend_experience.php.
     */
    public static function suspend_experience($fromform) {
        global $USER, $DB;

        // TODO Mail function has to be tested
        // Sends email to author.
        $sql = 'SELECT * FROM {user} u INNER JOIN {block_onb_e_exps} ee ON u.id = ee.user_id WHERE ee.id = '
            . $fromform->experience_id;

        $touser = $DB->get_record_sql($sql);
        $fromuser = $USER;
        $subject = $fromform->title;
        $messagetext = $fromform->comment;
        $messagehtml = $fromform->comment;

        email_to_user($touser, $fromuser, $subject, $messagetext, $messagehtml, '', '', true);

        // Sets "suspended" for the experience in question to "1".
        $DB->set_field('block_onb_e_exps', 'suspended', 1, array('id' => $fromform->experience_id));
    }

    /**
     * Determines whether an existing category is updated or a new category is added.
     *
     * @param object $fromform Form parameters passed from edit_category.php.
     */
    public static function edit_category($fromform) {
        global $DB;
        // Translates form data to new object for further processing.
        $category = new \stdClass();
        $category->name = $fromform->name;
        $category->questions = $fromform->questions;
        $category->timemodified = time();

        // Checks whether a new category is added.
        if ($fromform->id != -1) {
            $category->id = $fromform->id;
            // Nice to have make Category names unique.
            $DB->update_record('block_onb_e_cats', $category, $bulk = false);
        } else {
            $category->timecreated = time();
            $DB->insert_record('block_onb_e_cats', $category);
        }
    }


    /**
     * Deletes an existing category from the database.
     *
     * @param int $categoryid Id of category which is to be deleted.
     */
    public static function delete_category($categoryid) {
        global $DB;
        // Deletion of the category and all content written for it.
        $DB->delete_records('block_onb_e_exps_cats', array('category_id' => $categoryid));
        $DB->delete_records('block_onb_e_cats', array('id' => $categoryid));
    }

    /**
     * Returns Category Object.
     *
     * @param int $categoryid Id of category which is to be returned.
     * @return object Category.
     */
    public static function get_category_by_id($categoryid) {
        global $DB;
        return $DB->get_record('block_onb_e_cats', array('id' => $categoryid));
    }

    /**
     * Determines whether an existing course is updated or a new course is added.
     *
     * @param object $fromform Form parameters passed from edit_course.php.
     */
    public static function edit_course($fromform) {
        global $DB;
        // Data written in the Database.
        $course = new \stdClass();
        $course->name = $fromform->name;
        $course->timemodified = time();

        if ($fromform->id != -1) {
            $course->id = $fromform->id;
            // Nice to have: Make names unique.
            $DB->update_record('block_onb_e_courses', $course, $bulk = false);
        } else {
            $course->timecreated = time();
            $DB->insert_record('block_onb_e_courses', $course);
        }
    }


    /**
     * Deletes an existing course from the database.
     *
     * @param int $courseid Id of course which is to be deleted.
     */
    public static function delete_course($courseid) {
        global $DB;
        $DB->delete_records('block_onb_e_courses', array('id' => $courseid));
        $DB->delete_records('block_onb_e_exps', array('course_id' => $courseid));
        $DB->delete_records('block_onb_e_exps', array('course_id' => $courseid));
    }

    /**
     * Creates a report and sends a notification email to the administrator.
     *
     * @param object $fromform Form parameters passed from report_experience.php.
     */
    public static function edit_report($fromform) {
        global $USER, $DB;
        $reasons = array();
        if ($fromform->profanity == 1) {
            array_push($reasons, constants::PROFANITY);
        }
        if ($fromform->spam == 1) {
            array_push($reasons, constants::SPAM);
        }
        if ($fromform->offensive == 1) {
            array_push($reasons, constants::OFFENSIVE);
        }
        if ($fromform->falseinformation == 1) {
            array_push($reasons, constants::FALSEINFO);
        }
        if ($fromform->falsematching == 1) {
            array_push($reasons, constants::FALSEMATCH);
        }
        if ($fromform->personalinformation == 1) {
            array_push($reasons, constants::PERSONALINFO);
        }
        if ($fromform->other == 1 || empty($reasons)) {
            array_push($reasons, constants::OTHER);
        }
        // Translates form data to new object for further processing.
        $report = new \stdClass();
        $report->experience_id = $fromform->experience_id;
        $report->user_id = $fromform->user_id;
        $report->type = implode(',', $reasons);
        $report->description = $fromform->description;
        $report->timecreated = time();

        $report->id = $DB->insert_record('block_onb_e_report', $report);

        // Define necessary parameters for "email_to_user" function.
        $touser = get_admin();
        $fromuser = $USER;
        $subject = get_string('rep_mail_title', 'block_onboarding');
        $report->title = $DB->get_field('block_onb_e_exps', 'name', array('id' => $report->experience_id));
        $messagetext = get_string('rep_mail_text', 'block_onboarding', $report);
        $messagehtml = get_string('rep_mail_html', 'block_onboarding', $report);

        // Sends email to administrator.
        email_to_user($touser, $fromuser, $subject, $messagetext, $messagehtml, '', '', true);
    }

    /**
     * Sets the experience to visible and sends a notification mail to the author.
     *
     * @param object $experienceid Id of experience which is to be unsuspended.
     */
    public static function unsuspend_experience($experienceid) {
        global $USER, $DB;

        // Get the database entry for the recipient.
        $sql = 'SELECT * FROM {user} u INNER JOIN {block_onb_e_exps} ee ON u.id = ee.user_id WHERE ee.id = '
            . $experienceid;

        $touser = $DB->get_record_sql($sql);
        $fromuser = $USER;
        $subject = get_string('unsus_mail_title', 'block_onboarding');
        $messagetext = get_string('unsus_mail_text', 'block_onboarding');
        $messagehtml = get_string('unsus_mail_html', 'block_onboarding');

        // Sends email to the author.
        email_to_user($touser, $fromuser, $subject, $messagetext, $messagehtml, '', '', true);

        // Sets "suspended" for the experience in question to "null".
        $DB->set_field('block_onb_e_exps', 'suspended', null, array('id' => $experienceid));
    }

    /**
     * Takes the users ability to create an experience.
     *
     * @param int $experienceid Id of experience whose author is to be blocked.
     */
    public static function block_user($experienceid) {
        global $DB;

        $experience = $DB->get_record('block_onb_e_exps', array('id' => $experienceid));

        $user = new \stdClass();
        $user->user_id = $experience->user_id;
        $user->blockedsince = time();
        $DB->insert_record('block_onb_e_blocked', $user);
    }
}
