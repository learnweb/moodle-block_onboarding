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
 * The file for the guide_overview renderable class.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_onboarding\output\renderables;

defined('MOODLE_INTERNAL') || die();

use block_onboarding\wikilib;
use renderable;
use templatable;
use renderer_base;

/**
 * Class exporting the guide_overview renderable for the template.
 */
class guide_overview implements renderable, templatable {

    /**
     * Constructor function.
     */
    public function __construct() {
    }

    /**
     * Template export function.
     */
    public function export_for_template(renderer_base $output) {
        global $DB;
        $categories = array_values($DB->get_records('block_onb_w_categories', $conditions = null, $sort = 'position ASC'));
        $links = array_values($DB->get_records('block_onb_w_links'));
        // TODO check if valid URLs or provide appropriate error.
        // filter_var($text, FILTER_VALIDATE_URL).
        foreach ($categories as $category) {
            foreach ($links as $link) {
                if ($link->category_id == $category->id) {
                    $category->links[] = $link;
                }
            }
        }

        return [
            'can_manage_wiki' => has_capability('block/onboarding:w_manage_wiki', \context_system::instance()),
            'can_manage_steps' => has_capability('block/onboarding:s_manage_steps', \context_system::instance()),
            'categories_with_links' => $categories,
            'adminlink' => new \moodle_url('/blocks/onboarding/guidesettings.php')
        ];
    }
}
