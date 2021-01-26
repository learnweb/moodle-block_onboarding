<?php
/**
 * Test table class to be put in test_table.php of root of Moodle installation.
 *  for defining some custom column names and proccessing
 * Username and Password fields using custom and other column methods.
 */
class experience_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
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
        //$this->no_sorting('moodlerelease');

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
    function col_name($values) {
        return '<a href="experience.php?experience_id='.$values->id.'">'.$values->name.'</a>';
    }

    function col_author($values) {
        return $values->author;
    }

    function col_degreeprogram($values) {
        return $values->degreeprogram;
    }

    function col_published($values) {
        $date = userdate($values->published, get_string('strftimedatetimeshort', 'core_langconfig'));
        return $date;
    }

    function col_popularity($values) {
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