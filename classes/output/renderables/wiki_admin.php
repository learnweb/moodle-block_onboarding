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

use block_onboarding\wikilib;
use renderable;
use templatable;
use renderer_base;

class wiki_admin implements renderable, templatable {

    public function __construct() {
    }

    public function export_for_template(renderer_base $output) {
        global $DB;

        $categories = array_values($DB->get_records('block_onb_w_categories', $conditions = null, $sort = 'position ASC'));
        $links = array_values($DB->get_records('block_onb_w_links'));
        foreach ($categories as $category) {
            foreach ($links as $link) {
                if ($link->category_id == $category->id) {
                    $category->links[] = $link;
                }
            }
        }

        return [
            'can_manage_wiki' => has_capability('block/onboarding:w_manage_wiki', \context_system::instance()),
            'categories_with_links' => $categories
        ];
    }
}
