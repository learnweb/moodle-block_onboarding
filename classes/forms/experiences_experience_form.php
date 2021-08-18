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
 * File containing the form definition for experience reports.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for experience reports.
 */
class experiences_experience_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $DB, $USER;

        $mform = $this->_form;
        $experience = $this->_customdata['experience'];

        // Hidden experience report id.
        $mform->addElement('hidden', 'id', $experience->id);
        $mform->setType('id', PARAM_INT);

        // Hidden user id.
        $mform->addElement('hidden', 'user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);
        $output = html_writer::start_tag('div', array('class' => 'alert alert-warning'));
        $output .= get_string('experience_advice', 'block_onboarding');
        $output .= html_writer::end_tag('div');
        $mform->addElement('html', $output);
        // Experience Name Field.
        $mform->addElement('textarea', 'name', get_string('name', 'block_onboarding'),
            array('style="resize:none" wrap="virtual" rows="1" cols="60"'));
        $mform->addRule('name', get_string('experience_name_missing', 'block_onboarding'), 'required', null, 'server');
        $mform->addRule('name', 'Max Length is 30 characters', 'maxlength', 30, 'block_onboarding');
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($experience->name) ? $experience->name : get_string('default_experience_name',
            'block_onboarding'));

        // Degree Program selector.
        $courses = $DB->get_records('block_onb_e_courses');
        $link = $DB->get_record('block_onb_e_exps', array('id' => $experience->id));
        $coursesmodified = array();
        foreach ($courses as $course) {
            $coursesmodified[$course->id] = $course->name;
        }
        $mform->addElement('select', 'course_id', get_string('course_select', 'block_onboarding'),
            $coursesmodified);
        $mform->setDefault('course_id', isset($link->course_id) ? $link->course_id : '');
        $mform->addRule('course_id', get_string('experience_degreeprogram_missing', 'block_onboarding'), 'required', null,
            'server');

        // Category checkboxes.
        $categorygroup = array();
        $categories = $DB->get_records('block_onb_e_cats');
        $experiencescategories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        foreach ($categories as $category) {
            $categorygroup[] = $mform->createElement('checkbox', 'category_' . $category->id, $category->name);
        }
        foreach ($experiencescategories as $experiencecategory) {
            $mform->setDefault('category_' . $experiencecategory->category_id, true);
        }
        $mform->addGroup($categorygroup, 'categorygroup', get_string('selectcategories', 'block_onboarding'), '<br>', false);
        $mform->addHelpButton('categorygroup', 'selectcategory', 'block_onboarding');

        // About Me textarea.
        $mform->addElement('textarea', 'aboutme_text', get_string('aboutme', 'block_onboarding'),
            array('style="resize:none" wrap="virtual" rows="4" cols="100"',
                'placeholder' => get_string('aboutme_default', 'block_onboarding')));
        $mform->setType('aboutme_text', PARAM_TEXT);
        $mform->setDefault('aboutme_text', isset($experience->aboutme) ? $experience->aboutme : "");

        // Category textboxes.
        $categories = $DB->get_records('block_onb_e_cats');
        $experiencescategories = $DB->get_records('block_onb_e_exps_cats', array('experience_id' => $experience->id));
        $experiencescategoriesmapped = array();
        foreach ($experiencescategories as $experiencecategory) {
            $experiencescategoriesmapped[$experiencecategory->category_id] = $experiencecategory;
        }
        foreach ($categories as $category) {
            $mform->addElement('textarea', 'experience_category_' . $category->id . '_description', $category->name,
                array('wrap="virtual" rows="10" cols="100"',
                    'placeholder' => $category->questions));
            $mform->setType('experience_category_' . $category->id . '_description', PARAM_TEXT);
            $mform->setDefault('experience_category_' . $category->id . '_description',
                isset($experiencescategoriesmapped[$category->id]) ?
                    $experiencescategoriesmapped[$category->id]->description : "");
            $mform->hideIf('experience_category_' . $category->id . '_description', 'category_' . $category->id);

            // Key Takeaway field.
            $mform->addElement('textarea', 'experience_category_' . $category->id . '_takeaway',
                get_string('takeaways_required', 'block_onboarding'),
                array('style="resize:none" wrap="virtual" rows="1" cols="100"',
                    'placeholder' => get_string('takeaways_default', 'block_onboarding')));
            $mform->setType('experience_category_' . $category->id . '_takeaway', PARAM_TEXT);
            $mform->setDefault('experience_category_' . $category->id . '_takeaway',
                isset($experiencescategoriesmapped[$category->id]) ?
                    $experiencescategoriesmapped[$category->id]->takeaway : "");
            $mform->hideIf('experience_category_' . $category->id . '_takeaway', 'category_' . $category->id);
        }

        // Contact field.
        $mform->addElement('textarea', 'contact', get_string('experience_contact', 'block_onboarding'),
            array('style="resize:none" wrap="virtual" rows="1" cols="60"'));
        $mform->addRule('contact', 'Max Length is 30 characters', 'maxlength', 30, 'block_onboarding');
        $mform->setType('contact', PARAM_TEXT);
        $mform->setDefault('contact', isset($experience->contact) ? $experience->contact : '');
        $mform->addHelpButton('contact', 'contactinformation', 'block_onboarding');
        $mform->addElement('checkbox', 'anonym', get_string('anonym', 'block_onboarding'));
        $mform->addHelpButton('anonym', 'anonym', 'block_onboarding');

        // Adds 'Publish'-, 'Draft'- and 'Cancel'-buttons.
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'publish', get_string('publish', 'block_onboarding'));
        $buttonarray[] = $mform->createElement('submit', 'draft', get_string('savedraft', 'block_onboarding'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}
