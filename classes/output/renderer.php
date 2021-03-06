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
 * The file for the rederer class.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_onboarding\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use renderable;

/**
 * Provides functions to reder block pages.
 */
class renderer extends plugin_renderer_base {
    public function render_block(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/block', $data);
    }

    public function render_home(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/home', $data);
    }

    // Guide.

    public function render_guide_overview(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/guide_overview', $data);
    }

    // Wiki.

    public function render_wiki_overview(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/wiki_overview', $data);
    }

    public function render_wiki_admin(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/wiki_admin', $data);
    }

    // Steps.

    public function render_steps_admin(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/steps_admin', $data);
    }

    // Experiences.

    public function render_experiences_overview(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/experiences_overview', $data);
    }

    public function render_experiences_admin(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/experiences_admin', $data);
    }

    public function render_experiences_experience(renderable $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_onboarding/experiences_experience', $data);
    }

}
