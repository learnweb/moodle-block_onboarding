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
 * The file for the experiences_overview rederable class.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_onboarding\output\renderables;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

/**
 * Class exporting the experiences_overview rederable for the template.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class experiences_overview implements renderable, templatable {
    private $form;

    public function __construct($form) {
        $this->form = $form;
    }

    public function export_for_template(renderer_base $output) {

        global $DB, $USER;

        // Get Database Entries for display on the Start Page.

        $experience = $DB->get_record('block_onb_e_exps', array('user_id' => $USER->id));

        $blocked = $DB->record_exists('block_onb_e_blocked', array('user_id' => $USER->id));

        return [
            'can_manage_experiences' => has_capability('block/onboarding:e_manage_experiences', \context_system::instance()),
            'form' => $this->form,
            'experience' => $experience,
            'blocked' => $blocked
        ];
    }
}
