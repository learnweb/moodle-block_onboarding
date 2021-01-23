<?php
// This file is part of steps block for Moodle - http://moodle.org/
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

defined('MOODLE_INTERNAL') || die();

class block_steps extends block_base {

    public function init() {
        $this->title = get_string('steps', 'block_steps');
    }

    public function get_content() {
      if ($this->content !== null) {
        return $this->content;
      }

      $this->content = new stdClass;
      $renderer = $this->page->get_renderer('block_steps');
      $block = new \block_steps\output\renderables\block();
      $this->content->text = $renderer->render($block);
      return $this->content;
    }

    public function has_config() {
        return true;
    }
}
