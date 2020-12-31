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

class block implements renderable, templatable {

    public function __construct() {
    }

    public function export_for_template(renderer_base $output) {
      $buttons[] = [
          'title' => get_string('overview', 'block_steps'),
          'url' => new \moodle_url('/blocks/steps/overview.php')
      ];

      $buttons[] = [
          'title' => get_string('admin', 'block_steps'),
          'url' => new \moodle_url('/blocks/steps/admin.php')
      ];

      return ['buttons' => $buttons];
    }
}
