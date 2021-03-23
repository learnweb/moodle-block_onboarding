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
 * The file for the experiences_experience rederable class.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_onboarding\output\renderables;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

/**
 * Class exporting the experiences_experience rederable for the template.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class experiences_experience implements renderable, templatable {
    private $experienceid;

    /**
     * Constructor function.
     */
    public function __construct($experienceid) {
        $this->experienceid = $experienceid;
    }

    /**
     * Template export function.
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $DB;

        $experience = $DB->get_record('block_onb_e_exps', array('id' => $this->experienceid));

        // SQL Query to get Category content written by the User.
        $select = "SELECT * FROM {block_onb_e_exps_cats} block_onb_e_exps_cats ";
        $join = "INNER JOIN {block_onb_e_cats} block_onb_e_cats ";
        $on = "ON block_onb_e_exps_cats.category_id = block_onb_e_cats.id ";
        $where = "WHERE block_onb_e_exps_cats.experience_id = {$this->experienceid}";
        $sql = $select . $join . $on . $where;
        $experiencescategoriesjoinedcategories = $DB->get_records_sql($sql);

        // SQL Query to get Degree Program and Authors Firstname.
        $select = "SELECT ee.id, u.firstname as author FROM {block_onb_e_exps} ee ";
        $join = "INNER JOIN {user} u ON u.id = ee.user_id ";
        $where = "WHERE ee.id = {$this->experienceid}";
        $sql = $select . $join . $where;
        $author = $DB->get_record_sql($sql);

        // SQL Query to get Degree Program and Authors Firstname.
        $select = "SELECT ee.id, ec.name as degreeprogram FROM {block_onb_e_exps} ee ";
        $join = "INNER JOIN {block_onb_e_courses} ec ON ee.course_id = ec.id ";
        $where = "WHERE ee.id = {$this->experienceid}";
        $sql = $select . $join . $where;
        $degreeprogram = $DB->get_record_sql($sql);

        $report = $DB->get_record('block_onb_e_report',
            array('experience_id' => $this->experienceid, 'user_id' => $USER->id));
        return [
            'can_edit_experience' => has_capability('block/onboarding:e_manage_experiences',
                \context_system::instance()) || $USER->id == $experience->user_id,
            'can_manage_experiences' => has_capability('block/onboarding:e_manage_experiences',
                    \context_system::instance()),
            'foreignexperience' => !($experience->user_id == $USER->id),
            'experience' => $experience,
            'experiences_categories_joined_categories' => array_values($experiencescategoriesjoinedcategories),
            'author' => $author,
            'degreeprogram' => $degreeprogram,
            'report' => $report
        ];
    }
}
