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
 * File containing the form definition for Wiki categories.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for Wiki categories.
 */
class wiki_category_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $DB;

        $mform = $this->_form;
        $category = $this->_customdata['category'];

        // Hidden category id.
        $mform->addElement('hidden', 'id', $category->id);
        $mform->setType('id', PARAM_INT);

        // Category name field.
        $mform->addElement('text', 'name', get_string('name', 'block_onboarding'), array('maxlength' => 150, 'size' => 30,
            'placeholder' => get_string('default_category_name_wiki', 'block_onboarding')));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($category->name) ? $category->name : '');
        $mform->addRule('name', get_string('category_name_req', 'block_onboarding'), 'required', null, 'client');

        // Category position selector.
        $countpositions = $DB->count_records('block_onb_w_categories');
        if ($category->id == -1) {
            $positionarray = range(1, $countpositions + 1);
        } else {
            $positionarray = range(1, $countpositions);
        }
        $mform->addElement('select', 'position', get_string('category_number', 'block_onboarding'), $positionarray, array());
        $mform->setType('position', PARAM_INT);
        $mform->setDefault('position', isset($category->position) ? $category->position - 1 : $countpositions);

        // Adds 'Submit'- and 'Cancel'-buttons.
        $this->add_buttons();
    }

    /* Add an extra button for having add next*/
    public function add_buttons() {
        $mform =& $this->_form;

        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));

        $buttonarray[] = &$mform->createElement('submit', 'submitbutton2',
            get_string('addanother', 'block_onboarding'));

        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
}
