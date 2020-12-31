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

namespace block_wiki;

defined('MOODLE_INTERNAL') || die();

class wikilib {

    public function get_categories(){
      global $DB;

      return array_values($DB->get_records('block_wiki_categories'));
    }

    public function get_links(){
      global $DB;

      return array_values($DB->get_records('block_wiki_links'));
    }

    public function get_categories_with_links(){
      $categories = $this->get_categories();
      $links = $this->get_links();

      foreach($categories as $category){
        foreach($links as $link){
          if($link->category_id == $category->id){
            $category->links[] = $link;
          }
        }
      }

      return $categories;
    }

}
