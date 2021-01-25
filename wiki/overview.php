<?php
// This file is part of wiki block for Moodle - http://moodle.org/
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

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/overview.php'));
// ------------------------------------------------------------------------------------
// hier nur zum Testen:
global $USER;
global $DB;
$step = $DB->get_record('block_onb_s_steps', array('position' => 1), $fields = '*', $strictness = IGNORE_MISSING);
// startet immer bei pos. 1, später auch $USER->id übergeben in array
$PAGE->requires->js_call_amd('block_onboarding/steps_view', 'next_step', array($step->id, $step->position));
// ------------------------------------------------------------------------------------
$PAGE->set_title(get_string('overview', 'block_onboarding'));
$PAGE->set_heading(get_string('overview', 'block_onboarding'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));

$output = $PAGE->get_renderer('block_onboarding');
echo $output->header();
echo $output->container_start('wiki-overview');
$renderable = new \block_onboarding\output\renderables\wiki_overview();
echo $output->render($renderable);
echo $output->container_end();
echo $output->footer();
