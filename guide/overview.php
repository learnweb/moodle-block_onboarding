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
 * Overview of the Guide section.
 *
 * This page contains the basic overview structure for the Guide section, which includes the First Steps section
 * and the Wiki Section as well as the links to the related Administration sections.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

$context = context_system::instance();

global $USER, $DB;

// Initializes the page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/overview.php'));
if (has_capability('block/onboarding:w_manage_wiki', $context)) {
    $PAGE->requires->js_call_amd('block_onboarding/steps_view', 'init');
    $PAGE->set_title(get_string('firststeps', 'block_onboarding'));
    $PAGE->set_heading(get_string('firststeps', 'block_onboarding'));

    // Defines the page output.
    $output = $PAGE->get_renderer('block_onboarding');
    echo $output->header();
    echo $output->container_start('wiki-overview');
    $renderable = new \block_onboarding\output\renderables\guide_overview();
    echo $output->render($renderable);
    echo $output->container_end();
    echo $output->footer();
} else {
    redirect(new moodle_url("/my"));
}