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
require "$CFG->libdir/tablelib.php";
require($CFG->dirroot . '/blocks/onboarding/classes/output/experience_table.php');

require_login();

$context = context_system::instance();

$table = new experience_table('uniqueid');
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/overview.php'));
$PAGE->set_title(get_string('overview', 'block_onboarding'));
$PAGE->set_heading(get_string('overview', 'block_onboarding'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));
$PAGE->requires->css('/blocks/onboarding/style.css');

$output = $PAGE->get_renderer('block_onboarding');
echo $output->header();
echo $output->container_start('experiences-overview');
$renderable = new \block_onboarding\output\renderables\experiences_overview();
echo $output->render($renderable);
echo $output->container_end();

// SQL Statement for Listview
$fields = 'ee.id as id, ee.name as name, u.firstname as author, ec.name as degreeprogram, ee.timecreated as published, ee.popularity as popularity';
$from = '{block_onb_e_exps} ee 
INNER JOIN {user} u ON ee.user_id=u.id
INNER JOIN {block_onb_e_courses} ec ON ee.course_id=ec.id';
$table->set_sql($fields, $from, '1=1');

$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/overview.php");

$table->out(40, true);

echo $output->footer();
