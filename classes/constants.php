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
 * The file for the constants class.
 * Contains all constants for the onboarding block.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

/**
 * Constants for the onboarding block.
 * As of March 2021 constants are only used for the different categories of the report function in the Experiences section.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class constants {
    /** @var string */
    const SPAM = 'spam/advertisement';
    /** @var string */
    const PROFANITY = 'profanity';
    /** @var string */
    const OFFENSIVE = 'offensive content';
    /** @var string */
    const FALSEINFO = 'false information';
    /** @var string */
    const FALSEMATCH = 'content does not fit category';
    /** @var string */
    const PERSONALINFO = 'disclosure of personal information';
    /** @var string */
    const OTHER = 'other';
}
