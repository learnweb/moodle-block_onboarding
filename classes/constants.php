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

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

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