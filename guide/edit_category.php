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
 * Wiki category editing form for administrators.
 *
 * This page contains a form element to edit existing categories or to create a new category.
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
$PAGE->set_url(new moodle_url('/blocks/onboarding/wiki/edit_category.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('firststeps', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('wiki_admin', 'block_onboarding'), new moodle_url('admin_wiki.php'));
$PAGE->navbar->add(get_string('edit_category', 'block_onboarding'));

// Checks whether user holds the required capability for managing the Wiki.
if (has_capability('block/onboarding:w_manage_wiki', $context)) {
    // Initializes page title and heading.
    $PAGE->set_title(get_string('edit_category', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_category', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/wiki_category_form.php');

    // Retrieves optional URL parameter to determine whether a new category is added or an existing category is edited.
    // The URL variable refers to the category id which is -1 when a new category is to be added to the database.
    $categoryid = optional_param('category_id', -1, PARAM_INT);

    // Checks whether the category id refers to an existing category and retrieves the category from the database
    // if this is the case.
    // Otherwise a category id of -1 is used, which will be further processed in the Wiki library.
    $paramcategory = new stdClass;
    $paramcategory->id = -1;
    if ($categoryid != -1) {
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id' => $categoryid));
    }

    // Creates editing form with the temporary category object.
    $mform = new wiki_category_form(null, array('category' => $paramcategory));
    if ($mform->is_cancelled()) {
        // Redirects to Wiki administration section when editing process is canceled.
        redirect(new moodle_url('/blocks/onboarding/guidesettings.php'));
    } else {
        // Utilizes related Wiki library method and redirects to Wiki administration section when editing form is submitted.
        if ($fromform = $mform->get_data()) {
            \block_onboarding\wiki_lib::edit_category($fromform);
            if (property_exists($fromform, 'submitbutton')) {
                redirect(new moodle_url('/blocks/onboarding/guidesettings.php'));
            }
            if (property_exists($fromform, 'submitbutton2')) {
                redirect(new moodle_url('/blocks/onboarding/guide/edit_category.php'));
            }
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
