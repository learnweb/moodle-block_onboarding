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
 * As of March 2021 constants are only used for the different types of the report form in the Experiences section.
 */
class constants {
    /**
     * This is used when the experience includes spam or advertisement.
     */
    const SPAM = 'spam/advertisement';
    /**
     * This is used when the experience includes profanity.
     */
    const PROFANITY = 'profanity';
    /**
     * This is used when the experience includes offensive content.
     */
    const OFFENSIVE = 'offensive content';
    /**
     * This is used when the experience includes false information.
     */
    const FALSEINFO = 'false information';
    /**
     * This is used when the content for a category of the experience does not fit the category.
     */
    const FALSEMATCH = 'content does not fit category';
    /**
     * This is used when the experience includes personal information.
     */
    const PERSONALINFO = 'disclosure of personal information';
    /**
     * This is used when the experience has another issue that can not be described with the constants above.
     */
    const OTHER = 'other';
}
