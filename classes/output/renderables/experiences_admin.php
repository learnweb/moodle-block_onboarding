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

namespace block_onboarding\output\renderables;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

class experiences_admin implements renderable, templatable {

    public function __construct() {
    }

    public function export_for_template(renderer_base $output) {
        global $DB, $USER;

        // Get Database Entries for display on the Start Page.
        $experiences = array_values($DB->get_records('block_onb_e_exps'));
        $categories = array_values($DB->get_records('block_onb_e_cats'));
        $experiencescategories = array_values($DB->get_records('block_onb_e_exps_cats'));
        $courses = array_values($DB->get_records('block_onb_e_courses'));

        $experiencesmapped = array();
        foreach ($experiences as $experience) {
            if ($USER->id == $experience->user_id || has_capability('block/onboarding:e_manage_experiences',
                    \context_system::instance())) {
                $experience->editable = true;
            }
            $experiencesmapped[$experience->id] = $experience;
        }

        $categoriesmapped = array();
        foreach ($categories as $category) {
            $categoriesmapped[$category->id] = $category;
        }

        foreach ($experiencescategories as $experiencecategory) {
            $experiencesmapped[$experiencecategory->experience_id]->categories[] =
                $categoriesmapped[$experiencecategory->category_id];
        }

        $coursesmapped = array();
        foreach ($courses as $course) {
            $coursesmapped[$course->id] = $course;
        }

        return [
            'can_manage_experiences' => has_capability('block/onboarding:e_manage_experiences',
                \context_system::instance()),
            'categories_general' => $categories,
            'courses_general' => $courses,
            'experiences_with_categories' => array_values($experiencesmapped)
        ];
    }
}
