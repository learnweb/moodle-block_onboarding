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
 * This file contains manages the suspension of unfitting experiences.
 *
 * When suspending an experience the administrator can edit the notification mail to the author via a form.
 * When unsuspending an experience the author receives an automatic mail.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $CFG, $DB;

$experienceid = optional_param('experience_id', -1, PARAM_INT);

// Checking whether experience is suspended or not.
$suspended = $DB->get_field('block_onb_e_exps', 'suspended', array('id' => $experienceid));
if ($suspended == 1) {
    \block_onboarding\experiences_lib::unsuspend_experience($experienceid);
    redirect('experience.php?experience_id=' . $experienceid);
} else {

    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/edit_category.php'));
    $PAGE->navbar->add(get_string('experiences', 'block_onboarding'), new moodle_url('overview.php'));
    $experience = $DB->get_field('block_onb_e_exps', 'name', array('id' => $experienceid));
    $PAGE->navbar->add($experience, new moodle_url('experience.php?experience_id=' . $experienceid));

    if (has_capability('block/onboarding:e_manage_experiences', $context)) {
        $PAGE->set_title(get_string('edit_category', 'block_onboarding'));
        $PAGE->set_heading(get_string('edit_category', 'block_onboarding'));
        $PAGE->navbar->add(get_string('suspend_mail', 'block_onboarding') . ' ' . $experienceid);

        require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_mail_form.php');

        $mform = new experiences_mail_form(null, array('experience_id' => $experienceid));

        if ($mform->is_cancelled()) {
            redirect('experience.php?experience_id=' . $experienceid);
        } else {
            if ($fromform = $mform->get_data()) {
                // Processing of data submitted in the form.
                \block_onboarding\experiences_lib::suspend_experience($fromform);
                redirect('experience.php?experience_id=' . $fromform->experience_id);
            }
        }

        // Display of the form.
        echo $OUTPUT->header();
        $mform->display();
        echo $OUTPUT->footer();
    } else {
        redirect(new moodle_url("/my"));
    }
}
