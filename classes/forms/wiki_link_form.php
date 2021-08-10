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
 * File containing the form definition for Wiki links.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


require_once($CFG->libdir . '/formslib.php');

/**
 * Class providing the form for Wiki links.
 */
class wiki_link_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $DB;

        $mform = $this->_form;
        $link = $this->_customdata['link'];

        // Hidden link id.
        $mform->addElement('hidden', 'id', $link->id);
        $mform->setType('id', PARAM_INT);

        // Link name field.
        $mform->addElement('text', 'name', get_string('name', 'block_onboarding'),
            array('maxlength' => 150, 'size' => 30, 'placeholder' => get_string('default_link_name', 'block_onboarding')));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', isset($link->name) ? $link->name : '');
        $mform->addRule('name', get_string('link_name_req', 'block_onboarding'), 'required', null, 'client');

        // Category select field.
        $categories = $DB->get_records('block_onb_w_categories');
        $categoriesmodified = array();
        foreach ($categories as $category) {
            $categoriesmodified[$category->id] = $category->name;
        }
        $mform->addElement('select', 'category_id', get_string('link_category', 'block_onboarding'), $categoriesmodified);
        if (isset($link->category_id)) {
            $mform->setDefault('category_id', $link->category_id);
        }
        $mform->addRule('category_id', get_string('link_category_req', 'block_onboarding'), 'required', null, 'client');

        // Link URL field.
        $mform->addElement('text', 'url', get_string('link_url', 'block_onboarding'),
            array('maxlength' => 255, 'size' => 48, 'placeholder' => get_string('default_link_url', 'block_onboarding')));
        $mform->setType('url', PARAM_TEXT);
        $mform->setDefault('url', isset($link->url) ? $link->url : '');
        $mform->addRule('url', get_string('link_url_req', 'block_onboarding'), 'required', null, 'client');

        // Link description field.
        $mform->addElement('textarea', 'description', get_string('description', 'block_onboarding'),
            array('wrap' => "virtual", 'rows' => 10, 'cols' => 50,
                'placeholder' => get_string('link_description_req', 'block_onboarding')));
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', isset($link->description) ? $link->description : '');
        $mform->addRule('description', get_string('link_description_req', 'block_onboarding'), 'required', null, 'client');

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
