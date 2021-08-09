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
 * Settings for the onboarding block.
 *
 * @package block_onboarding
 * @copyright 2021 N. Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_onboarding\output\category_table;
use block_onboarding\output\steps_table;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/admin_form.php');

require_login();

require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('block_onboarding_guide');
echo $OUTPUT->header();

$mform = new \block_onboarding\admin_form();
$PAGE->requires->js_call_amd('block_onboarding/confirmation_popup', 'init');

if (empty($entry->id)) {
    $entry = new stdClass;
    $entry->id = 0;
}

echo html_writer::div(get_string('edit_steps', 'block_onboarding'), 'h3');
echo html_writer::link(new moodle_url('/blocks/onboarding/guide/edit_step.php'), $OUTPUT->pix_icon('t/add', 'Add', 'moodle') .
    get_string('step_step', 'block_onboarding'));
$table = new steps_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/guidesettings.php");
$fields = 'id, name, description, position, achievement';
$from = 'mdl_block_onb_s_steps';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);

echo html_writer::div(get_string('edit_categories', 'block_onboarding'), 'h3');
echo html_writer::link(new moodle_url('/blocks/onboarding/guide/edit_category.php'), $OUTPUT->pix_icon('t/add', 'Add', 'moodle') .
    get_string('link_category', 'block_onboarding'));
$table = new category_table('uniqueid');
$table->define_baseurl("$CFG->wwwroot/blocks/onboarding/guidesettings.php");
$fields = 'id, name, position';
$from = 'mdl_block_onb_w_categories';
$table->set_sql($fields, $from, 'id >= 0');
$table->out(10, true);
echo $OUTPUT->footer();

