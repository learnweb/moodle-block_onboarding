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

namespace block_experiences\output\renderables;

defined('MOODLE_INTERNAL') || die();

use block_experiences\experienceslib;
use renderable;
use templatable;
use renderer_base;

class overview implements renderable, templatable {
  public function __construct() {
  }

  public function export_for_template(renderer_base $output) {
    global $DB, $USER;

    $experiences = array_values($DB->get_records('block_experiences_exps'));
    $categories = array_values($DB->get_records('block_experiences_cats'));
    $experiences_categories = array_values($DB->get_records('block_experiences_exps_cats'));

    $experiences_mapped = array();
    foreach($experiences as $experience){
      if($USER->id == $experience->user_id || has_capability('block/experiences:edit_all_experiences', \context_system::instance())){
        $experience->editable = true;
      }
      $experiences_mapped[$experience->id] = $experience;
    }

    $categories_mapped = array();
    foreach($categories as $category){
      $categories_mapped[$category->id] = $category;
    }

    foreach($experiences_categories as $experience_category){
      $experiences_mapped[$experience_category->experience_id]->categories[] = $categories_mapped[$experience_category->category_id];
    }

    return [
        'categories' => $categories,
        'experiences_with_categories' => array_values($experiences_mapped)
    ];
  }
}
