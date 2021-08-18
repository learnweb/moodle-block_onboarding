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
 * Settings.
 *
 * @package    block_onboarding
 * @copyright  2021 Nina Herrmann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Needs this condition or there is error on login page.
    $category = new admin_category('onboarding_category',
        get_string('pluginname', 'block_onboarding'));
    $ADMIN->add('blocksettings', $category);
    $ADMIN->add('onboarding_category', new admin_externalpage('block_onboarding_guide',
        "Guide Settings", new moodle_url('/blocks/onboarding/guidesettings.php')));
    $ADMIN->add('onboarding_category', new admin_externalpage('block_onboarding_experience',
        "Experience Settings", new moodle_url('/blocks/onboarding/experiencesettings.php')));
}
$settings = null;

