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

require(__DIR__ . '/../../config.php');

require_login();

global $DB;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/steps/edit_step.php'));
$PAGE->navbar->add(get_string('pluginname', 'block_steps'));

if(has_capability('block/steps:edit_steps', $context)){
  $PAGE->set_title(get_string('edit_step', 'block_steps'));
  $PAGE->set_heading(get_string('edit_step', 'block_steps'));

    require_once('./classes/forms/step_form.php');
    /*
     * optional_param speichert die übergebene URL-Variable step_id in step_id
     * (bspw. moodle/blocks/steps/edit_step.php?step_id=8)
     * sofern keine URL-Variable übergeben wurde, wird -1 in step_id gespeichert
     * --> im Anwendungskontext wird somit differenziert ob es sich bei dem refrenzierten
     * Schritt um einen neuen Schritt handelt (keine URL-Variable) oder ein bestehender
     * Schrtitt editiert wird (vorhandene URL-Variable bzw. vorhandene step_id)
     */
    $step_id = optional_param('step_id', -1, PARAM_INT);
    // Hilfsvaribale um Daten eines bestehenden Schritts zu speichern
    $pStep = new stdClass;
    $pStep->id = -1;
    // wenn ein bestehender Schritt editiert werden soll, lese den Datensatz aus der Datenbank
    if ($step_id != -1) {
        /*
         * speichere den Datensatz aus der Tabelle block_steps_steps (Tabebellen-Prefix nicht notwendig)
         * bei dem die id-Daten mit der Variable step-id übereinstimmen (Konditionen werden immer als Array angegeben)
         * wobei alle Felder bzw. Spalten des Datensatzes selektiert werden sollen
         * und werfe keine Exception wenn keine oder mehrere Datensätze auf Basis der Konditionen gefunden werden
         */
        $pStep = $DB->get_record('block_steps_steps', array('id' => $step_id), $fields = '*', $strictness = IGNORE_MISSING);
    }
    /*
     * erstelle eine neue Moodle Form als eine Step Form und übergebe zusätzlich die Hilfsvariable pStep
     * welche beim Editieren eines bestehenden Schrittes die Daten des Schrittes aus der Datenbank enthält
     */
    $mform = new step_form(null, array('step' => $pStep));

    // wenn der Cancel-Button geklickt wird, kehre zu admin.php zurück
    if ($mform->is_cancelled()) {
        redirect('admin.php');
        /*
         * andernfalls wenn der Save-Changes-Button geklickt wurde und die Daten validiert werden konnten,
         * lade die Daten aus der form und speichere diese in der form fromform
         */
    } else if ($fromform = $mform->get_data()) {
        // speichere alle Daten aus der Form ausgenommen der position in dem Objekt step
      $step = new stdClass();
      $step->name = $fromform->name;
      $step->description = $fromform->description;

      $step->timemodified = time();

      // wenn ein bestehender Schritt editiert wird, aktualisiere den Datensatz
      if($fromform->id != -1){
        $step->id = $fromform->id;
        $step->position = $fromform->position;
        $DB->update_record('block_steps_steps', $step, $bulk=false);
        // andernfalls wird ein neuer Schritt bzw. Datensatz hinzugefügt, dessen position aus der Form übernommen wird// andernfalls wird ein neuer Schritt bzw. Datensatz hinzugefügt, dessen position aus der Form übernommen wird
      }else{
          $step->timecreated = time();
          $step->position = $fromform->position;
          $step->id = $DB->insert_record('block_steps_steps', $step);

      }
      redirect('admin.php');
  }

  echo $OUTPUT->header();
  $mform->display();
  echo $OUTPUT->footer();
// andernfalls konnten die Daten nicht validiert werden und es wird ein Fehlerhinweis angezeigt
}else{
  $PAGE->set_title(get_string('error', 'block_steps'));
  $PAGE->set_heading(get_string('error', 'block_steps'));

  echo $OUTPUT->header();
  echo html_writer::tag('p', get_string('insufficient_permissions', 'block_steps'));
  echo $OUTPUT->footer();
}
