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
 * Wiki link editing form for administrators.
 *
 * This page contains a form element to edit existing links or to create a new link.
 * Utilizes {@see \block_onboarding\wiki_lib} to provide the required functionality.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

// Initializes the page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/edit_link.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('guide', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('wiki_admin', 'block_onboarding'), new moodle_url('admin_wiki.php'));
$PAGE->navbar->add(get_string('edit_link', 'block_onboarding'));

// Checks whether user holds the required capability for managing the Wiki.
if (has_capability('block/onboarding:w_manage_wiki', $context)) {
    // Initializes page title and heading.
    $PAGE->set_title(get_string('edit_link', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_link', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/wiki_link_form.php');

    // Retrieves optional URL parameter to determine whether a new link is added or an existing link is edited.
    // The URL variable refers to the link id which is -1 when a new link is to be added to the database.
    $linkid = optional_param('link_id', -1, PARAM_INT);

    // Checks whether the link id refers to an existing link and retrieves the link from the database
    // if this is the case.
    // Otherwise a link id of -1 is used, which will be further processed in the Wiki library.
    $paramlink = new stdClass;
    $paramlink->id = -1;
    if ($linkid != -1) {
        $paramlink = $DB->get_record('block_onb_w_links', array('id' => $linkid));
    }

    // Creates editing form with the temporary link object.
    $mform = new wiki_link_form(null, array('link' => $paramlink));
    if ($mform->is_cancelled()) {
        // Redirects to Wiki administration section when editing process is canceled.
        redirect('admin_wiki.php');
    } else {
        // Utilizes related Wiki library method and redirects to Wiki administration section when editing form is submitted.
        if ($fromform = $mform->get_data()) {
            \block_onboarding\wiki_lib::edit_link($fromform);
            redirect('admin_wiki.php');
        }
    }

    // Defines the page output.
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();

} else {
    // Initializes page title and heading in case permissions are insufficient.
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    // Defines the page output.
    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
