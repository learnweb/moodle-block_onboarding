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
    global $DB;

    $categories = array_values($DB->get_records('block_onb_e_cats'));

    $courses = array_values($DB->get_records('block_onb_e_courses'));

    return [
        'can_edit_categories' => has_capability('block/onboarding:e_edit_categories', \context_system::instance()) ? true : false,
        'categories_general' => $categories,
        'can_edit_courses' => has_capability('block/onboarding:e_edit_courses', \context_system::instance()) ? true : false,
        'courses_general' => $courses
    ];
  }
}