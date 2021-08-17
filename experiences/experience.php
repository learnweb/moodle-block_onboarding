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
 * File to display an experience.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(__DIR__ . '/../../../config.php');

global $DB, $USER;

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/experience.php'));
$experienceid = optional_param('experience_id', -1, PARAM_INT);
$PAGE->requires->js_call_amd('block_onboarding/experiences_experience', 'init', array($experienceid));
$experience = new stdClass();
$experience = $DB->get_record('block_onb_e_exps', array('id' => $experienceid));

if ($experience->suspended == null || ($experience->suspended == 1  && ($experience->user_id == $USER->id ||
    has_capability('block/onboarding:e_manage_experiences', \context_system::instance())))) {
    $PAGE->set_title(get_string('experience', 'block_onboarding'));
    $PAGE->set_heading(get_string('experience', 'block_onboarding'));
    $PAGE->navbar->add($experience->name);

    $output = $PAGE->get_renderer('block_onboarding');
    $PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');
    echo $output->header();
    echo $output->container_start('experiences-experience');
    $renderable = new block_onboarding\output\renderables\experiences_experience($experienceid);
    echo $output->render($renderable);
    echo $output->container_end();
    echo $output->footer();
} else {
    redirect(new moodle_url("/my"));
}
