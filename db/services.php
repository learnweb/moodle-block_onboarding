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
 * Moodleoverflow external functions and service definitions.
 *
 * @package    block_onboarding
 * @category   external
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die;

$functions = array(
    'blocks_steps_get_step' => array(
        'classname'   => 'steps_view_external',
        'methodname'  => 'get_step',
        'classpath'   => 'blocks/steps/externallib.php',
        'description' => 'Reads step data from database',
        'type'        => 'read',
        'ajax'        => true
//        'capabilities' => '??????????????????????????????????'
    )
//    ,
//    'blocks_steps_record_step' => array(
//        'classname'    => 'steps_view_external',
//        'methodname'   => 'record_step',
//        'classpath'    => 'blocks/steps/externallib.php',
//        'description'  => 'Writes step completion to database',
//        'type'         => 'write',
//        'ajax'         => true,
//        'capabilities' => '??????????????????????????????????'
//    )
);
