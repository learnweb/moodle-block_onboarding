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

require_login();

global $CFG, $DB;

$experience_id = optional_param('experience_id', -1, PARAM_INT);
$suspended = $DB->get_field('block_onb_e_exps', 'suspended', array('id' => $experience_id), $strictness=IGNORE_MISSING);
if ($suspended == 1){
    $DB->set_field('block_onb_e_exps', 'suspended', null, array('id' => $experience_id));
    redirect('experience.php?experience_id=' . $experience_id);
} else {
    $context = context_system::instance();

    $PAGE->set_context($context);
    $PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_category.php'));
    $PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
    $PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
    $PAGE->navbar->add(get_string('experience_admin', 'block_onboarding'), new moodle_url('admin.php'));
    $experience = $DB->get_field('block_onb_e_exps', 'name', array('id' => $experience_id), $strictness=IGNORE_MISSING);
    $PAGE->navbar->add($experience, new moodle_url('experience.php?experience_id=' . $experience_id));

    if (has_capability('block/onboarding:e_manage_experiences', $context)) {
        $PAGE->set_title(get_string('edit_category', 'block_onboarding'));
        $PAGE->set_heading(get_string('edit_category', 'block_onboarding'));
        $PAGE->navbar->add(get_string('suspend_mail', 'block_onboarding') . ' ' . $experience_id);

        require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_mail_form.php');

        $mform = new experiences_mail_form(null, array('experience_id' => $experience_id));

        if ($mform->is_cancelled()) {
            redirect('experience.php?experience_id=' . $experience_id);
        } else if ($fromform = $mform->get_data()) {
            block_onboarding\experiences_lib::suspend_experience($fromform);
        }

        echo $OUTPUT->header();
        $mform->display();
        echo $OUTPUT->footer();
    } else {
        $PAGE->set_context($context);
        $PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_experience.php'));
        $PAGE->set_title(get_string('error', 'block_onboarding'));
        $PAGE->set_heading(get_string('error', 'block_onboarding'));
        $PAGE->navbar->add(get_string('error', 'block_onboarding'));

        echo $OUTPUT->header();
        echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
        echo $OUTPUT->footer();
    }
}
