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

class experiences_experience implements renderable, templatable {
    private $experience_id;

    public function __construct($experience_id) {
        $this->experience_id = $experience_id;
    }

    public function export_for_template(renderer_base $output) {
        global $USER, $DB;

        $experience = $DB->get_record('block_onb_e_exps', array('id' => $this->experience_id));
        // $categories = $DB->get_records('block_onb_e_cats');
        // $experiences_categories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience_id));

        // SQL Query to get Category content written by the User.
        $sql = "SELECT * FROM {block_onb_e_exps_cats} block_onb_e_exps_cats
        INNER JOIN {block_onb_e_cats} block_onb_e_cats
        ON block_onb_e_exps_cats.category_id = block_onb_e_cats.id
        WHERE block_onb_e_exps_cats.experience_id = {$this->experience_id}";
        $experiences_categories_joined_categories = $DB->get_records_sql($sql);

        // SQL Query to get Degree Program and Authors Firstname.
        $sql = "SELECT ee.id, u.firstname as author
        FROM {block_onb_e_exps} ee
        INNER JOIN {user} u
        ON u.id = ee.user_id
        WHERE ee.id = {$this->experience_id}";
        $author = $DB->get_record_sql($sql);

        // SQL Query to get Degree Program and Authors Firstname.
        $sql = "SELECT ee.id, ec.name as degreeprogram
        FROM {block_onb_e_exps} ee
        INNER JOIN {block_onb_e_courses} ec
        ON ee.course_id = ec.id
        WHERE ee.id = {$this->experience_id}";
        $degreeprogram = $DB->get_record_sql($sql);

        $report = $DB->get_record('block_onb_e_report',
            array('experience_id' => $this->experience_id, 'user_id' => $USER->id));

        return [
            'can_edit_experience' => has_capability('block/onboarding:e_manage_experiences',
                \context_system::instance()) || $USER->id == $experience->user_id,
            'can_manage_experiences' => has_capability('block/onboarding:e_manage_experiences',
                    \context_system::instance()),
            'experience' => $experience,
            'experiences_categories_joined_categories' => array_values($experiences_categories_joined_categories),
            'author' => $author,
            'degreeprogram' => $degreeprogram,
            'report' => $report
        ];
    }
}
