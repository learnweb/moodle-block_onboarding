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

require_login();

global $DB, $USER;

$context = context_system::instance();

if(has_capability('block/onboarding:s_manage_steps', $context)){
    $stepid = optional_param('step_id', -1, PARAM_INT);
    \block_onboarding\steps_lib::delete_step($stepid);
    redirect('admin_steps.php');
}else{
    $PAGE->set_context($context);
    $PAGE->set_url(new moodle_url('/blocks/onboarding/steps/delete_step.php'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'));
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
