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
 * Onboarding Plug-in external functions and service definitions.
 *
 * @package    block_onboarding
 * @category   external
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die;

// TODO: Array-Informationen der Funktionen prüfen und überarbeiten! READ / WRTIE!! CAPABILITIES BEI LÖSCHEN

$functions = array(
    'block_onboarding_next_step' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'next_step',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_init_step' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'init_step',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_back_step' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'back_step',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_reset_progress' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'reset_progress',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_toggle_visibility' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'toggle_visibility',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_init_helpful' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'init_helpful',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_click_helpful' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'click_helpful',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_delete_confirmation' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'delete_confirmation',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    ),
    'block_onboarding_delete_entry' => array(
        'classname'   => 'block_onboarding_view_external',
        'methodname'  => 'delete_entry',
        'classpath'   => 'blocks/onboarding/externallib.php',
        'description' => 'BESCHREIBUNG',
        'type'        => 'read',
        'ajax'        => true
    )
);
