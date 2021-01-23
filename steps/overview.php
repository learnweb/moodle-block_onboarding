<?php
// This file is part of steps block for Moodle - http://moodle.org/
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

// OBSOLETE -> DATEI UND VERWEISE ENTFERNEN!!!
require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/steps/overview.php'));
$PAGE->set_title(get_string('overview', 'block_onboarding'));
$PAGE->set_heading(get_string('overview', 'block_onboarding'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));


//baut admin website auf
$output = $PAGE->get_renderer('block_onboarding');
echo $output->header();
echo $output->container_start('steps-overview');
//erzeugt renderable obj. mit allen inhalten die hinzugefügt werden
$renderable = new \block_onboarding\output\renderables\steps_overview();
echo $output->render($renderable);
echo $output->container_end();
echo $output->footer();
