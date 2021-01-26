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
    global $DB;

    $experience = $DB->get_record('block_onb_e_exps', array('id' => $this->experience_id));
    //$categories = $DB->get_records('block_onb_e_cats');
    //$experiences_categories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience_id));

    $sql = "SELECT * FROM {block_onb_e_exps_cats} block_onb_e_exps_cats
    INNER JOIN {block_onb_e_cats} block_onb_e_cats
    ON block_onb_e_exps_cats.category_id = block_onb_e_cats.id
    WHERE block_onb_e_exps_cats.experience_id = {$this->experience_id}";
    $experiences_categories_joined_categories = $DB->get_records_sql($sql);

    $moresql= "SELECT ee.id, ec.name as degreeprogram, u.firstname as author
    FROM {block_onb_e_exps} ee
    INNER JOIN {block_onb_e_courses} ec
    ON ee.course_id = ec.id
    INNER JOIN {user} u
    ON u.id = ee.user_id
    WHERE ee.id = {$this->experience_id}";
    $degreeprogram_author = $DB->get_record_sql($moresql);

    return [
        'experience' => $experience,
        'experiences_categories_joined_categories' => array_values($experiences_categories_joined_categories),
        'degreeprogram_author' => $degreeprogram_author
    ];
  }
}
