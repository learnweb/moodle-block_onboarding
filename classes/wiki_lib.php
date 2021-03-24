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
 * The file for the wiki_lib class.
 * Contains static methods for Wiki administration.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_onboarding;

defined('MOODLE_INTERNAL') || die();

/**
 * Static methods for Wiki administration.
 */
class wiki_lib {

    /**
     * Determines whether an existing category is updated or a new category is added.
     * Calls {@see add_category} for new categories and {@see update_category} to update an existing category.
     *
     * @param object $fromform Form parameters passed from edit_category.php.
     */
    public static function edit_category($fromform) {
        // Translates form data to new object for further processing.
        $category = new \stdClass();
        $category->name = $fromform->name;
        $category->position = $fromform->position + 1;

        // Checks whether a new category is added.
        if ($fromform->id != -1) {
            $category->id = $fromform->id;
            self::update_category($category);
        } else {
            self::add_category($category);
        }
    }

    /**
     * Inserts a new category into the database.
     * Calls {@see increment_category_positions()} to update positions of other categories if necessary.
     *
     * @param object $category Category object with form parameters.
     */
    public static function add_category($category) {
        global $DB;
        // Category is added at last category position at first.
        $initposition = $DB->count_records('block_onb_w_categories') + 1;
        $insertposition = $category->position;
        $category->position = $initposition;
        $category->timecreated = time();
        $category->timemodified = time();
        $category->id = $DB->insert_record('block_onb_w_categories', $category);

        // Checks whether intended category position differs from max category position and updates affected
        // category positions accordingly.
        // TODO check this before writing to database -> avoid insertion plus update
        if ($initposition != $insertposition) {
            self::increment_category_positions($insertposition, $initposition);
            $category->position = $insertposition;
            $category->timemodified = time();
            $DB->update_record('block_onb_w_categories', $category);
        }
    }

    /**
     * Updates an existing category in the database.
     * Calls {@see decrement_category_positions()} or {@see increment_category_positions()} to update positions of other
     * categories if necessary.
     *
     * @param object $category Category object with form parameters.
     */
    public static function update_category($category) {
        global $DB;
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id' => $category->id));
        $curposition = $paramcategory->position;
        $insertposition = $category->position;

        // Checks whether intended category position differs from current category position and updates affected
        // category positions accordingly.
        if ($insertposition > $curposition) {
            self::decrement_category_positions($insertposition, $curposition);
        } else {
            if ($insertposition < $curposition) {
                self::increment_category_positions($insertposition, $curposition);
            }
        }
        $category->timemodified = time();
        $DB->update_record('block_onb_w_categories', $category);
    }

    /**
     * Deletes an existing category from the database.
     * Calls {@see decrement_category_positions()} to update positions of remaining categories.
     *
     * @param int $categoryid Id of the category which is to be deleted.
     */
    public static function delete_category($categoryid) {
        global $DB;
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id' => $categoryid));
        $curposition = $paramcategory->position;
        $categorycount = $DB->count_records('block_onb_w_categories');
        self::decrement_category_positions($categorycount, $curposition);
        $DB->delete_records('block_onb_w_categories', array('id' => $categoryid));

        // Deletes all links within the category.
        $DB->delete_records('block_onb_w_links', array('category_id' => $categoryid));
    }

    /**
     * Increments category positions between the $insert and $current category position, excluding the passed $current position.
     *
     * @param int $insert New category position.
     * @param int $cur Current category position.
     */
    public static function increment_category_positions($insert, $cur) {
        global $DB;
        $sql = 'UPDATE {block_onb_w_categories} SET position = position +1 WHERE position >= :insert_pos and position < :cur_pos';
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }

    /**
     * Decrements category positions between the $current category and $insert position, excluding the passed $current position.
     *
     * @param int $insert New category position.
     * @param int $cur Current category position.
     */
    public static function decrement_category_positions($insert, $cur) {
        global $DB;
        $sql = 'UPDATE {block_onb_w_categories} SET position = position -1 WHERE position > :cur_pos and position <= :insert_pos';
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }

    /**
     * Determines whether an existing link is updated or a new link is added to a category.
     *
     * @param object $fromform Form parameters passed from edit_link.php.
     */
    public static function edit_link($fromform) {
        global $DB;
        // Translates form data to new object for further processing.
        $link = new \stdClass();
        $link->name = $fromform->name;
        $link->category_id = $fromform->category_id;
        $link->url = $fromform->url;
        $link->description = $fromform->description;
        $link->timemodified = time();

        // Checks whether a new link is added.
        if ($fromform->id != -1) {
            $link->id = $fromform->id;
            $DB->update_record('block_onb_w_links', $link);
        } else {
            $link->timecreated = time();
            $link->id = $DB->insert_record('block_onb_w_links', $link);
        }
    }
}
