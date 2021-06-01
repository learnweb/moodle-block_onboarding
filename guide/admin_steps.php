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
 * First Steps content administration section.
 *
 * This page contains an overview of the First Steps for the administrator and links to the editing forms for updating
 * existing steps or creating new steps.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

$context = context_system::instance();
$PAGE->set_url(new moodle_url('/blocks/onboarding/steps/admin.php'));
$PAGE->set_context($context);

// Checks whether user holds the required capability for managing steps.
if (has_capability('block/onboarding:w_manage_wiki', $context)) {
    // Initializes the page and JavaScript function.
    $PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');
    $PAGE->set_title(get_string('steps_admin', 'block_onboarding'));
    $PAGE->set_heading(get_string('steps_admin', 'block_onboarding'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
    $PAGE->navbar->add(get_string('firststeps', 'block_onboarding'), new moodle_url('overview.php'));
    $PAGE->navbar->add(get_string('steps_admin', 'block_onboarding'));

    // Defines the page output.
    $output = $PAGE->get_renderer('block_onboarding');
    echo $output->header();
    echo $output->container_start('steps-admin');
    $renderable = new \block_onboarding\output\renderables\steps_admin();
    echo $output->render($renderable);
    echo $output->container_end();
    echo $output->footer();

} else {
    // Initializes page title and heading in case permissions are insufficient.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

    // Defines the page output.
    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
