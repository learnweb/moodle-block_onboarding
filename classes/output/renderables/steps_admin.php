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

use block_onboarding\stepslib;
use renderable;
use templatable;
use renderer_base;

class steps_admin implements renderable, templatable {
  public function __construct() {
  }

  public function export_for_template(renderer_base $output) {
    global $DB;

    //$steps = array_values($DB->get_records_sql('SELECT * FROM {block_onb_s_steps} ORDER BY position ASC'));

    $steps = array_values($DB->get_records('block_onb_s_steps', $conditions=null, $sort='position ASC'));

    foreach($steps as $step){
        if($step->achievement == 1){
            $step->achievement = get_string('step_achievement', 'block_onboarding');
        } else {
            $step->achievement = get_string('step_step', 'block_onboarding');
        }
    }


    return [
      'can_manage_wiki' => has_capability('block/onboarding:s_manage_steps', \context_system::instance()),
      'steps' => $steps
    ];
  }
}
