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

require(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();
$url = new moodle_url('/blocks/experiences/admin.php');

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('admin', 'block_experiences'));
$PAGE->set_heading(get_string('admin', 'block_experiences'));
$PAGE->navbar->add(get_string('pluginname', 'block_experiences'));

$output = $PAGE->get_renderer('block_experiences');
echo $output->header();
echo $output->container_start('experiences-admin');
$renderable = new \block_experiences\output\renderables\admin();
echo $output->render($renderable);
echo $output->container_end();
echo $output->footer();
