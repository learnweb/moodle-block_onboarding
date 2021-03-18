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

class blocked_table extends table_sql {

    /**
     * Constructor.
     *
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('id', 'firstname', 'lastname', 'blockedsince', 'actions');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(get_string('id', 'block_onboarding'), get_string('firstname', 'block_onboarding'),
            get_string('lastname', 'block_onboarding'), get_string('blockedsince', 'block_onboarding'),
            get_string('actions', 'block_onboarding'));
        $this->define_headers($headers);

        // Table configuration.
        $this->set_attribute('cellspacing', '0');
        $this->sortable(true, 'blockedsince', SORT_DESC);
        $this->no_sorting('actions');
        $this->initialbars(false);
        $this->collapsible(false);
    }

    /**
     * This function is called for each data row to allow processing of the blocked user value.
     *
     * @param object $values Contains object with all the values of record.
     * @return string Return content for each column.
     */

    // Configure Column Content.
    public function col_id($values) {
        return $values->id;
    }

    public function col_firstname($values) {
        return $values->firstname;
    }

    public function col_lastname($values) {
        return $values->lastname;
    }

    public function col_blockedsince($values) {
        $date = userdate($values->blockedsince, get_string('strftimedatetimeshort', 'core_langconfig'));
        return $date;
    }

    public function col_actions($values) {
        return '<span onb-data-id='.$values->id.' onb-data-context="exp-admin-unblock" 
        class="block-onboarding-confirm-btn block-onboarding-link-btn">' . get_string('unblock', 'block_onboarding') . '</span>';
    }

    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     *
     * @return string return processed value. Return NULL if no change has been made.
     */
    function other_cols($colname, $value) {
    }
}
