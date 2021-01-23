<?php  // $Id$
/**
 * Simple file test.php to drop into root of Moodle installation.
 * This is the skeleton code to print a downloadable, paged, sorted table of
 * data from a sql query.
 */
require(__DIR__. '/../../config.php');
require "$CFG->libdir/tablelib.php";
require($CFG->dirroot . '/blocks/experiences/classes/output/experience_table.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/experiences/test.php');

$table = new experience_table('uniqueid');

// Print the page header
$PAGE->set_title('Testing');
$PAGE->set_heading('Testing table class');
$PAGE->navbar->add('Testing table class', new moodle_url('/blocks/experiences/test.php'));
echo $OUTPUT->header();


// Work out the sql for the table.
$fields = 'ee.id as id, ee.name as name, u.firstname as author, ec.name as degreeprogram, ee.timecreated as published, ee.popularity as popularity';

$from = '{block_experiences_exps} ee 
INNER JOIN {user} u ON ee.user_id=u.id
INNER JOIN {block_experiences_courses} ec ON ee.course_id=ec.id';

$table->set_sql($fields, $from, '1=1');

$table->define_baseurl("$CFG->wwwroot/blocks/experiences/test.php");

$table->out(40, true);

echo $OUTPUT->footer();
