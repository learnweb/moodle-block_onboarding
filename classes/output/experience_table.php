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

class experience_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('name', 'author', 'degreeprogram', 'published', 'popularity');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Name', 'Author', 'Degree Program', 'Published', 'Popularity');
        $this->define_headers($headers);

        // Table configuration.
        $this->set_attribute('cellspacing', '0');

        $this->sortable(true, 'published', SORT_DESC);

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
    public function col_name($values) {
        return '<a href="experience.php?experience_id='.$values->id.'">'.$values->name.'</a>';
    }

    public function col_author($values) {
        return $values->author;
    }

    public function col_degreeprogram($values) {
        return $values->degreeprogram;
    }

    public function col_published($values) {
        $date = userdate($values->published, get_string('strftimedatetimeshort', 'core_langconfig'));
        return $date;
    }

    public function col_popularity($values) {
        return $values->popularity;
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