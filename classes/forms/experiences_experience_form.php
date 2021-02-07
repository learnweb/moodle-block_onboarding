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

class experiences_experience_form extends moodleform {

    public function definition() {
        global $CFG, $DB, $USER;

        $mform = $this->_form;

        $experience = $this->_customdata['experience'];

        $mform->addElement('hidden', 'id', $experience->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);

        // Experience Name Field.
        $mform->addElement('text', 'name', get_string('experience_name', 'block_onboarding'));
        $mform->addRule('name', get_string('experience_name_missing', 'block_onboarding'), 'required', null, 'server');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($experience->name) ? $experience->name : get_string('default_experience_name',
            'block_onboarding'));

        // Degree Program Drop Down Menu.
        $courses = $DB->get_records('block_onb_e_courses');
        $coursesmodified = array();
        foreach ($courses as $course){
            $coursesmodified[$course->id] = $course->name;
        }
        $mform->addElement('select', 'course_id', get_string('course_select', 'block_onboarding'),
            $coursesmodified, 'required');
        if (isset($link->course_id)) {
            $mform->setDefault('course_id', $link->course_id);
        }

        // About Me Checkbox
        $mform->addElement('checkbox', 'aboutme', get_string('aboutme', 'block_onboarding'));
        $mform->setDefault('aboutme', true);


        // Category Checkboxes.
        $categories = $DB->get_records('block_onb_e_cats');
        $experiencescategories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        foreach ($categories as $category) {
            $mform->addElement('checkbox', 'category_' . $category->id, $category->name);
        }

        foreach ($experiencescategories as $experiencecategory) {
            $mform->setDefault('category_' . $experiencecategory->category_id, true);
        }

        // About Me Textarea
        $mform->addElement('textarea', 'aboutme_text', get_string('aboutme', 'block_onboarding'),
            array('wrap="virtual" rows="10" cols="100"',
                'placeholder' => get_string('aboutme_default', 'block_onboarding')));
        $mform->setType('aboutme_text', PARAM_TEXT);
        $mform->setDefault('aboutme_text', isset($experience->aboutme) ? $experience->aboutme : "");
        $mform->hideIf('aboutme_text', 'aboutme');

        // Category Textboxes.
        $categories = $DB->get_records('block_onb_e_cats');
        $experiencescategories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        $experiencescategoriesmapped = array();
        foreach ($experiencescategories as $experiencecategory) {
            $experiencescategoriesmapped[$experiencecategory->category_id] = $experiencecategory;
        }
        foreach ($categories as $category) {
            $mform->addElement('textarea', 'experience_category_' . $category->id . '_description', $category->name,
                array('wrap="virtual" rows="10" cols="100"', 'placeholder' => $category->questions));
            $mform->setType('experience_category_' . $category->id . '_description', PARAM_TEXT);
            // $mform->addRule('experience_category_' . $category->id . '_description',
            //    get_string('category_missing', 'block_onboarding'), 'required', null, 'server');
            $mform->setDefault('experience_category_' . $category->id . '_description',
                isset($experiencescategoriesmapped[$category->id]) ?
                    $experiencescategoriesmapped[$category->id]->description : "");
            $mform->hideIf('experience_category_' . $category->id . '_description', 'category_' . $category->id);

            // Key Takeaway Field.
            $mform->addElement('textarea', 'experience_category_' . $category->id . '_takeaway',
                get_string('takeaways_required', 'block_onboarding'),
                array('style="resize:none" wrap="virtual" rows="1" cols="100"',
                    'placeholder' => get_string('takeaways_default', 'block_onboarding')));
            // $mform->addRule('experience_category_' . $category->id . '_takeaway',
            //    get_string('takeaways_missing', 'block_onboarding'), 'required', null, 'server');
            $mform->setType('experience_category_' . $category->id . '_takeaway', PARAM_TEXT);
            $mform->setDefault('experience_category_' . $category->id . '_takeaway',
                isset($experiencescategoriesmapped[$category->id]) ?
                    $experiencescategoriesmapped[$category->id]->takeaway : "");
            $mform->hideIf('experience_category_' . $category->id . '_takeaway', 'category_' . $category->id);
        }

        // Contact Field.
        $mform->addElement('text', 'contact', get_string('experience_contact', 'block_onboarding'));
        $mform->setType('contact', PARAM_TEXT);
        $mform->setDefault('contact', isset($experience->contact) ? $experience->contact : '');

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}
