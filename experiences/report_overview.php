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

require(__DIR__ . '/../../../config.php');
require($CFG->libdir . '/tablelib.php');
require($CFG->dirroot . '/blocks/onboarding/classes/output/report_table.php');
require($CFG->dirroot . '/blocks/onboarding/classes/output/experience_table.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/report_overview.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('experience_admin', 'block_onboarding'), new moodle_url('admin.php'));
$PAGE->navbar->add(get_string('report_overview', 'block_onboarding'));

// Check if the user has the necessary capability.
if (has_capability('block/onboarding:e_manage_experiences', \context_system::instance())) {

    $tablereport = new report_table('uniqueid');
    $tablesuspended = new experience_table('uniqueid');

    $PAGE->set_title(get_string('report_overview', 'block_onboarding'));
    $PAGE->set_heading(get_string('report_overview', 'block_onboarding'));
    $PAGE->requires->css('/blocks/onboarding/style.css');
    $PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');
    $output = $PAGE->get_renderer('block_onboarding');
    echo $output->header();

    // Display Title.
    echo html_writer::div(get_string('reports', 'block_onboarding'), 'title');
    echo html_writer::empty_tag('br');

    // SQL statement for report listview.
    $fields1 = 'er.id as id, ee.name as experience, er.experience_id as experience_id, er.type as type, ';
    $fields2 = 'er.description as description, u.firstname as author, er.timecreated as timecreated';
    $fields = $fields1 . $fields2;
    $from1 = '{block_onb_e_report} er INNER JOIN {user} u ON er.user_id=u.id ';
    $from2 = 'INNER JOIN {block_onb_e_exps} ee ON er.experience_id=ee.id';
    $from = $from1 . $from2;
    $where = '1=1';

    $tablereport->set_sql($fields, $from, $where);
    $tablereport->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/report_overview.php");
    $tablereport->out(5, true);

    // Display Title.
    echo html_writer::empty_tag('br');
    echo html_writer::div(get_string('suspended_experiences', 'block_onboarding'), 'title');
    echo html_writer::empty_tag('br');

    // SQL statement for suspended listview.
    $fields1 = 'ee.id as id, ee.name as name, u.firstname as author, ec.name as degreeprogram, ';
    $fields2 = 'ee.timecreated as published, ee.timemodified as lastmodified, ee.popularity as popularity';
    $fields = $fields1 . $fields2;
    $from1 = '{block_onb_e_exps} ee INNER JOIN {user} u ON ee.user_id=u.id ';
    $from2 = 'INNER JOIN {block_onb_e_courses} ec ON ee.course_id=ec.id';
    $from = $from1 . $from2;
    $where = 'ee.suspended = 1';

    $tablesuspended->set_sql($fields, $from, $where);
    $tablesuspended->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/report_overview.php");
    $tablesuspended->out(5, true);

    echo $output->footer();
} else {
    // If the user doesn't have the capability needed an error page is displayed.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
