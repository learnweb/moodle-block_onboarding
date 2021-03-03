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

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

class experiences_lib {
    public static function edit_experience($fromform){
        global $DB;
        
        // Data written in the Database.
        $experience = new \stdClass();
        $experience->name = $fromform->name;
        $experience->contact = $fromform->contact;
        $experience->user_id = $fromform->user_id;
        $experience->course_id = $fromform->course_id;
        $experience->timecreated = time();
        $experience->timemodified = time();

        if (isset($fromform->aboutme)) {
            $experience->aboutme = $fromform->aboutme_text;
        } else {
            $experience->aboutme = null;
        }

        if ($fromform->id != -1) {
            $experience->id = $fromform->id;
            $DB->update_record('block_onb_e_exps', $experience, $bulk = false);
        } else {
            $experience->id = $DB->insert_record('block_onb_e_exps', $experience);
        }

        $DB->delete_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        $categories = $DB->get_records('block_onb_e_cats');
        $experiences_categories = array();

        foreach ($categories as $category) {
            $formproperty_category_checkbox = 'category_' . $category->id;
            $formproperty_category_textarea = 'experience_category_' . $category->id . '_description';
            if (isset($fromform->$formproperty_category_checkbox) && empty($fromform->$formproperty_category_textarea) == false) {
                $experience_category = new \stdClass;
                $experience_category->experience_id = $experience->id;
                $experience_category->category_id = $category->id;
                $formproperty_category_textarea = 'experience_category_' . $category->id . '_description';
                $experience_category->description = $fromform->$formproperty_category_textarea;
                $formproperty_category_takeaway = 'experience_category_' . $category->id . '_takeaway';
                $experience_category->takeaway = $fromform->$formproperty_category_takeaway;
                $experience_category->timecreated = time();
                $experience_category->timemodified = time();
                $experiences_categories[] = $experience_category;
            } else {
                $DB->delete_records('block_onb_e_exps_cats',
                    array('experience_id' => $experience->id, 'category_id' => $category->id));
            }
        }
        $DB->insert_records('block_onb_e_exps_cats', $experiences_categories);
    }

    public static function update_experience($step){
        global $DB;

        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $step->id));
        $curposition = $paramstep->position;
        $insertposition = $step->position;

        // Prüfen ob Änderung von anderen pos erforderlich ist
        // wenn gewünschte Einfügeposition weiter hinten als aktuelle Position ist
        if ($insertposition > $curposition) {
            \block_onboarding\step_admin_functions::decrement_step_positions($insertposition, $curposition);

            // wenn gewünschte Einfügeposition weiter vorne als aktuelle Position ist
        } else if ($insertposition < $curposition) {
            \block_onboarding\step_admin_functions::increment_step_positions($insertposition, $curposition);
        }
        // andernfalls ist die Position gleich und es müssen keine anderen Schrittpositionen verändert werden
        
        $step->position = $fromform->position + 1;
        $step->timemodified = time();
        $DB->update_record('block_onb_s_steps', $step);
    }

    public static function delete_experience($experience_id){
        global $DB;
        $DB->delete_records('block_onb_e_exps_cats', array('experience_id' => $experience_id));
        $DB->delete_records('block_onb_e_exps', array('id' => $experience_id));
    }

    public static function get_category_by_id($category_id){
        global $DB;
        return $DB->get_record('block_onb_e_cats', array('id'=>$category_id), $fields='*', $strictness=IGNORE_MISSING);
    }

    public static function add_category($category){
        global $DB;
        $DB->insert_record('block_onb_e_cats', $category);
    }

    public static function update_category($category){
        global $DB;
        $DB->update_record('block_onb_e_cats', $category, $bulk = false);
    }

    public static function delete_category($category_id){
        global $DB;
        // Deletion of the category and all content written for it.
        $DB->delete_records('block_onb_e_exps_cats', array('category_id' => $category_id));
        $DB->delete_records('block_onb_e_cats', array('id' => $category_id));
    }

    public static function add_course($course){
        global $DB;
        $DB->insert_record('block_onb_e_courses', $course);
    }

    public static function update_course($course){
        global $DB;
        $DB->update_record('block_onb_e_courses', $course, $bulk = false);
    }

    public static function delete_course($course_id){
        global $DB;
        $DB->delete_records('block_onb_e_courses', array('id' => $course_id));
    }

    public static function get_course_by_id($course_id){
        global $DB;
        return $DB->get_record('block_onb_e_courses', array('id' => $course_id), $fields = '*',
        $strictness = IGNORE_MISSING);
    }

    public static function add_report($fromform){
        // Data written in the Database.
        $report = new stdClass();
        $report->experience_id = $fromform->experience_id;
        $report->user_id = $fromform->user_id;
        $report->type = $fromform->type;
        $report->description = $fromform->description;
        $report->timecreated = time();

        $report->id = $DB->insert_record('block_onb_e_report', $report);
    }
}