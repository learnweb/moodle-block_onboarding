<?php
// This file is part of experiences block for Moodle - http://moodle.org/
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
 * This file contains the structure of the blocked user overview block_overview.php.
 *
 * On this page blocked users are displayed in a table format with the possibility to unblock them.
 * This page can only be viewed by the administrator.
 *
 * @package    block_onboarding
 * @category
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');
require($CFG->libdir . '/tablelib.php');
require($CFG->dirroot . '/blocks/onboarding/classes/output/blocked_table.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/report_overview.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('experience_admin', 'block_onboarding'), new moodle_url('admin.php'));
$PAGE->navbar->add(get_string('blocked_overview', 'block_onboarding'));

if (has_capability('block/onboarding:e_manage_experiences', \context_system::instance())) {

    $table = new blocked_table('uniqueid');

    $PAGE->set_title(get_string('blocked_overview', 'block_onboarding'));
    $PAGE->set_heading(get_string('blocked_overview', 'block_onboarding'));
    $PAGE->requires->css('/blocks/onboarding/style.css');
    $PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');
    $output = $PAGE->get_renderer('block_onboarding');

    echo $output->header();

    // SQL Statement for Listview.
    $fields = 'u.id as id, u.firstname as firstname, u.lastname as lastname, b.blockedsince as blockedsince';
    $from = '{block_onb_e_blocked} b
    INNER JOIN {user} u ON b.user_id=u.id';
    $where = '1=1';

    $table->set_sql($fields, $from, $where);
    $table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/blocked_overview.php");
    $table->out(10, true);

    echo $output->footer();
} else {
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
