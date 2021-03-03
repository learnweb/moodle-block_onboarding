<?php
// This file is part of steps block for Moodle - http://moodle.org/
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

require(__DIR__ . '/../../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/onboarding/steps/edit_step.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_onboarding'), new moodle_url('../index.php'));
$PAGE->navbar->add(get_string('guide', 'block_onboarding'), new moodle_url('overview.php'));
$PAGE->navbar->add(get_string('steps_admin', 'block_onboarding'), new moodle_url('admin_steps.php'));
$PAGE->navbar->add(get_string('edit_step', 'block_onboarding'));

// TODO: Kommentare korrigieren und reduzieren und in Englisch
// TODO: Clean Code!!

// prüft Zugriffsrechte für die Datenbank-Zugriff
if (has_capability('block/onboarding:s_manage_steps', $context)) {
    $PAGE->set_title(get_string('edit_step', 'block_onboarding'));
    $PAGE->set_heading(get_string('edit_step', 'block_onboarding'));

    require_once($CFG->dirroot . '/blocks/onboarding/classes/forms/steps_step_form.php');
    /*
     * optional_param speichert die übergebene URL-Variable step_id in step_id
     * (bspw. moodle/blocks/steps/edit_step.php?step_id=8)
     * sofern keine URL-Variable übergeben wurde, wird -1 in step_id gespeichert
     * --> im Anwendungskontext wird somit differenziert ob es sich bei dem refrenzierten
     * Schritt um einen neuen Schritt handelt (keine URL-Variable) oder ein bestehender
     * Schrtitt editiert wird (vorhandene URL-Variable bzw. vorhandene step_id)
     */
    $stepid = optional_param('step_id', -1, PARAM_INT);
    $paramstep = new stdClass();
    $paramstep->id = -1;

    // wenn ein bestehender Schritt editiert werden soll, lese den Datensatz aus der Datenbank
    if ($stepid != -1) {
        /*
         * speichere den Datensatz aus der Tabelle block_onb_s_steps (Tabebellen-Prefix nicht notwendig)
         * bei dem die id-Daten mit der Variable step-id übereinstimmen (Konditionen werden immer als Array angegeben)
         * wobei alle Felder bzw. Spalten des Datensatzes selektiert werden sollen
         * und werfe keine Exception wenn keine oder mehrere Datensätze auf Basis der Konditionen gefunden werden
         */
        $paramstep = $DB->get_record('block_onb_s_steps', array('id' => $stepid));
    }
    /*
     * erstelle eine neue Moodle Form als eine Step Form und übergebe zusätzlich die Hilfsvariable paramstep
     * welche beim Editieren eines bestehenden Schrittes die Daten des Schrittes aus der Datenbank enthält
     */
    $mform = new steps_step_form(null, array('step' => $paramstep));

    // wenn der Cancel-Button geklickt wird, kehre zu admin_steps.php zurück
    if ($mform->is_cancelled()) {
        redirect('admin_steps.php');
        /*
         * andernfalls wenn der Save-Changes-Button geklickt wurde und die Daten validiert werden konnten,
         * lade die Daten aus der form und speichere diese in der form fromform
         */
    } else if ($fromform = $mform->get_data()) {
        \block_onboarding\steps_lib::edit_step($fromform);
        redirect('admin_steps.php');
    }

    echo $OUTPUT->header();
    // form wird angezeigt (hier nicht über renderable)
    $mform->display();
    echo $OUTPUT->footer();
// andernfalls konnten die Daten nicht validiert werden und es wird ein Fehlerhinweis angezeigt
} else {
    $PAGE->set_title(get_string('error', 'block_onboarding'));
    $PAGE->set_heading(get_string('error', 'block_onboarding'));

    echo $OUTPUT->header();
    echo html_writer::tag('p', get_string('insufficient_permissions', 'block_onboarding'));
    echo $OUTPUT->footer();
}
