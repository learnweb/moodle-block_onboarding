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

namespace block_steps\output\renderables;

defined('MOODLE_INTERNAL') || die();

use block_steps\stepslib;
use renderable;
use templatable;
use renderer_base;

class admin implements renderable, templatable {
  public function __construct() {
  }

  public function export_for_template(renderer_base $output) {
    global $DB;

    $steps = array_values($DB->get_records_sql('SELECT * FROM {block_steps_steps} ORDER BY position ASC'));
    $cur = 1;
    foreach($steps as $step){
        $step->index = $cur;
        $cur++;
    }

    //alte Funktion
    //$steps = array_values($DB->get_records('block_steps_steps'));

    return [
        'steps' => $steps
    ];
  }
}
