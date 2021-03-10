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

defined('MOODLE_INTERNAL') || die();

class report_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('experience', 'experience_id', 'type', 'description', 'author', 'timecreated', 'actions');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Experience', 'Id', 'Type', 'Description', 'Author', 'Date', 'Actions');
        $this->define_headers($headers);

        // Table configuration.
        $this->set_attribute('cellspacing', '0');
        $this->sortable(true, 'timecreated', SORT_DESC);
        $this->initialbars(false);
        $this->collapsible(false);
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
     */

    // Configure Column Content.
    public function col_experience($values) {
        return '<a href="experience.php?experience_id=' . $values->experience_id . '">' . $values->experience . '</a>';
    }

    public function col_experience_id($values) {
        return $values->experience_id;
    }

    public function col_type($values) {
        return $values->type;
    }

    public function col_description($values) {
        return $values->description;
    }

    public function col_author($values) {
        return $values->author;
    }

    public function col_timecreated($values) {
        $date = userdate($values->timecreated, get_string('strftimedatetimeshort', 'core_langconfig'));
        return $date;
    }

    public function col_actions($values) {
        return '<a href="delete_report.php?report_id='.$values->id.'">' . get_string('delete', 'block_onboarding') . '</a>';
    }

    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     * @return string return processed value. Return NULL if no change has
     *     been made.
     */
    function other_cols($colname, $value) {

    }
}
