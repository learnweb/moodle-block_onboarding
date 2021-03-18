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
 * This file contains the structure of the administration page admin.php.
 *
 * This page can only be viewed by the administrator and contains links to other administrative pages.
 * It also gives the user the ability to edit, delete and create new categories and courses.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

$context = context_system::instance();
$url = new moodle_url('/blocks/onboarding/experiences/admin.php');

$PAGE->set_url($url);
$PAGE->set_context($context);

if (has_capability('block/onboarding:e_manage_experiences', $context)) {
    $PAGE->requires->js_call_amd('block_onboarding/delete_confirmation', 'init');
    $PAGE->set_title(get_string('experiences', 'block_onboarding'));
    $PAGE->set_heading(get_string('experiences', 'block_onboarding'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
    $PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
    $PAGE->navbar->add(get_string('experience_admin', 'block_onboarding'));

    $output = $PAGE->get_renderer('block_onboarding');
    echo $output->header();
    echo $output->container_start('experiences-admin');
    $renderable = new \block_onboarding\output\renderables\experiences_admin();
    echo $output->render($renderable);
    echo $output->container_end();
    echo $output->footer();
} else {
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
