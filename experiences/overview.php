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
 * Overview of the Experiences section.
 *
 * This page contains two form elements that function as filters for the displayed table.
 * Additionally there is an area where the user can see their created experience or create a new one.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');
require($CFG->libdir . '/tablelib.php');
require($CFG->dirroot . '/blocks/onboarding/classes/output/experience_table.php');

require_login();

global $DB;

$context = context_system::instance();

$table = new experience_table('uniqueid');
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/experiences/overview.php'));
$PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');
$PAGE->set_title(get_string('experiences', 'block_onboarding'));
$PAGE->set_heading(get_string('experiences', 'block_onboarding'));

// Passing the form to the mustache file.
require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/experiences_filter_form.php');
$mform = new experiences_filter_form(null, null);
$form = $mform->render();

// SQL Statement for Listview.
$fields1 = 'ee.id as id, ee.name as name, u.firstname as author, ec.name as degreeprogram, ';
$fields2 = 'ee.timecreated as published, ee.timemodified as lastmodified, ee.popularity as popularity';
$fields = $fields1 . $fields2;
$from1 = '{block_onb_e_exps} ee INNER JOIN {user} u ON ee.user_id=u.id ';
$from2 = 'INNER JOIN {block_onb_e_courses} ec ON ee.course_id=ec.id';
$from = $from1 . $from2;
$where = '1=1';
$skip = false;

if ($fromform = $mform->get_data()) {
    $cats = '';
    if (isset($fromform->category_filter)) {
        $cats = '(' . implode(',', $fromform->category_filter) . ')';
    }
    $crs = '';
    if (isset($fromform->course_filter)) {
        $crs = '(' . implode(',', $fromform->course_filter) . ')';
    }

    if (empty($fromform->category_filter) != true) {
        // Category Filter applied.
        $sql = 'SELECT experience_id FROM {block_onb_e_exps_cats} matching WHERE category_id IN' . $cats;
        $firstresult = $DB->get_fieldset_sql($sql);
        $sqlfirstresult = '(' . implode(',', $firstresult) . ')';
        if (empty($firstresult) != true) {
            // Results for Category Filter.
            $w = 'WHERE id IN ' . $sqlfirstresult;
            if (empty($fromform->course_filter) != true) {
                // Category and Course Filter applied.
                $w = $w . ' AND course_id IN' . $crs;
            }
            $w = $w . ' AND published = 1 AND suspended IS NULL';
        } else {
            // No Results for Category Filter.
            if (empty($fromform->course_filter) != true) {
                // No Results for Category Filter + Course Filter applied.
                $w = 'WHERE course_id IN ' . $crs . ' AND published = 1 AND suspended IS NULL';
            } else {
                // No Results for Category Filter + Course Filter empty.
                $where = '1=0';
                $skip = true;
            }
        }
    } else {
        // Category Filter empty.
        if (empty($fromform->course_filter) != true) {
            // Category Filter empty + Course Filter applied.
            $w = 'WHERE course_id IN ' . $crs . ' AND published = 1 AND suspended IS NULL';
        } else {
            // Category and Course Filter empty.
            $skip = true;
        }
    }
    if ($skip != true) {
        $sql = 'SELECT id FROM {block_onb_e_exps} experiences ' . $w;
        $result = $DB->get_fieldset_sql($sql);
        $sqlresult = '(' . implode(',', $result) . ')';
        if (empty($result) != true) {
            // Results.
            $where = 'ee.id IN' . $sqlresult;
        } else {
            // No Results.
            $where = '1=0';
        }
    }
}
$where = $where . ' AND ee.published = 1 AND ee.suspended IS NULL';
$table->set_sql($fields, $from, $where);
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/experiences/overview.php");

$output = $PAGE->get_renderer('block_onboarding');
echo $output->header();
echo $output->container_start('experiences-overview');
$renderable = new block_onboarding\output\renderables\experiences_overview($form);
echo $output->render($renderable);
echo $output->container_end();
$table->out(10, true);
echo $output->footer();
