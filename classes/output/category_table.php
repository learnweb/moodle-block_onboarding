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
 * The file for the experience table class.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding\output;
use table_sql;

defined('MOODLE_INTERNAL') || die();

/**
 * Class defining the steps table.
 */

class category_table extends table_sql {

    /**
     * Constructor.
     *
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('name', 'links', 'position', 'action');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(get_string('name', 'block_onboarding'), get_string('attached_links', 'block_onboarding'),
            get_string('category_number', 'block_onboarding'), get_string('actions', 'moodle'));
        $this->define_headers($headers);

        // Table configuration.
        $this->set_attribute('cellspacing', '0');

        $this->sortable(true, 'position', SORT_ASC);

        $this->initialbars(false);
        $this->collapsible(false);

    }

    /**
     * This function is called for each data row to allow processing of the experience value.
     *
     * @param object $values Contains object with all the values of record.
     * @return string Return content for each column.
     */

    // Configure Column Content.
    public function col_name($values) {
        return $values->name;
    }

    public function col_links($values) {
        global $DB, $OUTPUT;
        $links = $DB->get_records('block_onb_w_links', array('category_id' => $values->id));
        $arrayoflinks = array();
        array_push($arrayoflinks, \html_writer::link(new \moodle_url('/blocks/onboarding/guide/edit_link.php'),
            $OUTPUT->pix_icon('t/add', get_string('new_link', 'block_onboarding'), 'moodle') . get_string('new_link', 'block_onboarding')));
        foreach ($links as $link) {
            $deletelink = '<span onb-data-id="' . $link->id . '"onb-data-context="wiki-link" class="block-onboarding-confirm-btn">' .
                $OUTPUT->pix_icon('i/trash', get_string('delete_link', 'block_onboarding'), 'moodle') . '</a>';
            $editlink = \html_writer::link(new \moodle_url('/blocks/onboarding/guide/edit_link.php?link_id=' . $values->id),
                $OUTPUT->pix_icon('t/editinline', get_string('edit_link', 'block_onboarding'), 'moodle'));
            array_push($arrayoflinks,  $link->name . ' ' . $editlink . $deletelink);
        }
        return \html_writer::alist($arrayoflinks, array('style' => 'list-style-type:none;'));
    }

    public function col_position($values) {
        return $values->position;
    }


    public function col_action($values) {
        global $OUTPUT;
        $editlink = \html_writer::link(new \moodle_url('/blocks/onboarding/guide/edit_category.php?category_id=' . $values->id),
            $OUTPUT->pix_icon('t/editinline', get_string('edit_category', 'block_onboarding'), 'moodle'));
        $deletecategory = '<span onb-data-id="' . $values->id .'" onb-data-context="wiki-category" class="block-onboarding-confirm-btn">' .
            $OUTPUT->pix_icon('i/trash', get_string('delete_category', 'block_onboarding'), 'moodle') . '</span>';
        return $editlink . $deletecategory;
    }



    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     *
     * @return string return processed value. Return NULL if no change has been made.
     */
    public function other_cols($colname, $value) {
    }
}
