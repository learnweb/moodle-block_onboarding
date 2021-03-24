<?php
// This file is part of the local onboarding plugin
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
 * File containing the form definition for experiences filter.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for experiences filter.
 */
class experiences_filter_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $DB;

        $mform = $this->_form;

        // Experiences course filter selector.
        $courses = $DB->get_records('block_onb_e_courses');
        $coursescount = $DB->count_records('block_onb_e_courses');
        $coursesmodified = array();
        foreach ($courses as $course) {
            $coursesmodified[$course->id] = $course->name;
        }
        $selectcourse = $mform->addElement('select', 'course_filter', get_string('degreeprogram_filter', 'block_onboarding'),
            $coursesmodified, array('size' => $coursescount));
        $selectcourse->setMultiple(true);
        $mform->addHelpButton('course_filter', 'filter_or_courses', 'block_onboarding');

        // Experiences categories filter selector.
        $categories = $DB->get_records('block_onb_e_cats');
        $categoriescount = $DB->count_records('block_onb_e_cats');
        $categoriesmodified = array();
        foreach ($categories as $category) {
            $categoriesmodified[$category->id] = $category->name;
        }
        $selectcategories = $mform->addElement('select', 'category_filter', get_string('category_filter', 'block_onboarding'),
            $categoriesmodified, array('size' => $categoriescount));
        $selectcategories->setMultiple(true);
        $mform->addHelpButton('category_filter', 'filter_or_categories', 'block_onboarding');

        // Adds 'Submit'-button.
        $mform->addElement('submit', 'applyfilter', get_string('applyfilter', 'block_onboarding'));
    }
}
