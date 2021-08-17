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


defined('MOODLE_INTERNAL') || die();

/**
 * Class defining the experiences table.
 */
class experience_table extends table_sql {

    /**
     * Constructor.
     *
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('name', 'author', 'degreeprogram', 'published', 'lastmodified', 'popularity');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(get_string('name', 'block_onboarding'), get_string('author', 'block_onboarding'),
            get_string('experience_degreeprogram', 'block_onboarding'), get_string('published', 'block_onboarding'),
            get_string('lastmodified', 'block_onboarding'), get_string('popularity', 'block_onboarding'));
        $this->define_headers($headers);

        // Table configuration.
        $this->set_attribute('cellspacing', '0');

        $this->sortable(true, 'published', SORT_DESC);

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
        var_dump($values);
        if (isset($values->anonym)) {
            return get_string('anonym', 'block_onboarding');
        }
        return '<a href="experience.php?experience_id=' . $values->id . '">' . $values->name . '</a>';
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

    public function col_lastmodified($values) {
        $date = userdate($values->lastmodified, get_string('strftimedatetimeshort', 'core_langconfig'));
        return $date;
    }

    public function col_popularity($values) {
        global $DB;
        return $DB->count_records('block_onb_e_helpful', array('experience_id' => $values->id));
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
