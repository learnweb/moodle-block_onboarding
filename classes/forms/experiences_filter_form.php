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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class experiences_filter_form extends moodleform {

    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        // $mform->addElement('header', 'filter', get_string('filters', 'block_onboarding'));

        $filtergroup=array();

        $courses = $DB->get_records('block_onb_e_courses');
        $coursesmodified = array();
        foreach ($courses as $course) {
            $coursesmodified[$course->id] = $course->name;
        }
        $options = array(
            'multiple' => true,
            'noselectionstring' => '',
            'placeholder' => get_string('degreeprogram_filter', 'block_onboarding')
        );
        $filtergroup[] =& $mform->createElement('autocomplete', 'course_filter', get_string('degreeprogram_filter', 'block_onboarding'),
            $coursesmodified, $options);

        $categories = $DB->get_records('block_onb_e_cats');
        $categoriesmodified = array();
        foreach ($categories as $category){
            $categoriesmodified[$category->id] = $category->name;
        }
        $options = array(
            'multiple' => true,
            'noselectionstring' => '',
            'placeholder' => get_string('category_filter', 'block_onboarding')
        );
        $filtergroup[] =& $mform->createElement('autocomplete', 'category_filter', get_string('category_filter', 'block_onboarding'),
            $categoriesmodified, $options);
        // $mform->addHelpButton('category_filter', 'filter_or', 'block_onboarding');

        $mform->addGroup($filtergroup, 'filtergroup', get_string('filters', 'block_onboarding'), '', false);

        $mform->addElement('submit', 'applyfilter', get_string('applyfilter', 'block_onboarding'));
    }

    public function validation($data, $files) {
        return array();
    }
}
