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

require(__DIR__ . '/../../../config.php');
require($CFG->libdir . '/tablelib.php');
require($CFG->dirroot . '/blocks/onboarding/classes/output/report_table.php');

require_login();

global $DB;

$context = context_system::instance();

$table = new report_table('uniqueid');
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/report_overview.php'));
$PAGE->set_title(get_string('report_overview', 'block_onboarding'));
$PAGE->set_heading(get_string('report_overview', 'block_onboarding'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));
$PAGE->requires->css('/blocks/onboarding/style.css');
$output = $PAGE->get_renderer('block_onboarding');
echo $output->header();

// SQL Statement for Listview.
$fields = 'er.id as id, ee.name as experience, er.experience_id as experience_id, er.type as type, er.description as description, 
u.firstname as author, er.timecreated as timecreated';
$from = '{block_onb_e_report} er
INNER JOIN {user} u ON er.user_id=u.id
INNER JOIN {block_onb_e_exps} ee ON er.experience_id=ee.id';
$where = '1=1';

$table->set_sql($fields, $from, $where);

$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/report_overview.php");

$table->out(40, true);

echo $output->footer();
