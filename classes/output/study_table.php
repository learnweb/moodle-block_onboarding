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

class study_table extends table_sql {

    /**
     * Constructor.
     *
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('name');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(get_string('name', 'block_onboarding'));
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
        global $OUTPUT;
        $editlink = \html_writer::link(new \moodle_url('/blocks/onboarding/experiences/edit_course.php?course_id=' . $values->id),
            $OUTPUT->pix_icon('t/editinline', 'Edit', 'moodle'));
        $deletelink = \html_writer::link(new \moodle_url('/blocks/onboarding/experiences/edit_course.php?course_id=' . $values->id . '&delete=1'), $OUTPUT->pix_icon('i/trash', 'Delete', 'moodle'));
        return $values->name . ' ' . $editlink . $deletelink;
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
