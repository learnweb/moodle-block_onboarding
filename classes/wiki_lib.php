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

class wiki_lib {

    public static function edit_category($fromform) {
        $category = new \stdClass();
        $category->name = $fromform->name;
        $category->position = $fromform->position + 1;

        if ($fromform->id != -1) {
            $category->id = $fromform->id;
            self::update_category($category);
        } else {
            self::add_category($category);
        }
    }

    public static function add_category($category) {
        global $DB;
        $initposition = $DB->count_records('block_onb_w_categories') + 1;
        $insertposition = $category->position;

        $category->position = $initposition;
        $category->timecreated = time();
        $category->timemodified = time();
        $category->id = $DB->insert_record('block_onb_w_categories', $category);

        if ($initposition != $insertposition) {
            self::increment_category_positions($insertposition, $initposition);
            $category->position = $insertposition;
            $category->timemodified = time();
            $DB->update_record('block_onb_w_categories', $category);
        }
    }

    public static function update_category($category) {
        global $DB;
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id' => $category->id));
        $curposition = $paramcategory->position;
        $insertposition = $category->position;

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

    public static function delete_category($categoryid) {
        global $DB;
        $paramcategory = $DB->get_record('block_onb_w_categories', array('id' => $categoryid));
        $curposition = $paramcategory->position;
        $categorycount = $DB->count_records('block_onb_w_categories');
        self::decrement_category_positions($categorycount, $curposition);
        $DB->delete_records('block_onb_w_categories', array('id' => $categoryid));

        // Deleting all links within the category.
        $DB->delete_records('block_onb_w_links', array('category_id' => $categoryid));
    }

    public static function edit_link($fromform) {
        global $DB;
        $link = new \stdClass();
        $link->name = $fromform->name;
        $link->category_id = $fromform->category_id;
        $link->url = $fromform->url;
        $link->description = $fromform->description;

        if ($fromform->id != -1) {
            $link->id = $fromform->id;
            self::update_link($link);
        } else {
            self::add_link($link);
        }
    }

    public static function delete_link($linkid) {
        global $DB;
        $DB->delete_records('block_onb_w_links', array('id' => $linkid));
    }

    public static function add_link($link) {
        global $DB;
        $link->timecreated = time();
        $link->timemodified = time();
        $link->id = $DB->insert_record('block_onb_w_links', $link);
    }

    public static function update_link($link) {
        global $DB;
        $link->timemodified = time();
        $DB->update_record('block_onb_w_links', $link);
    }


    public static function increment_category_positions($insert, $cur) {
        global $DB;
        $sql = 'UPDATE {block_onb_w_categories} SET position = position +1 WHERE position >= :insert_pos and position < :cur_pos';
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }

    public static function decrement_category_positions($insert, $cur) {
        global $DB;
        $sql = 'UPDATE {block_onb_w_categories} SET position = position -1 WHERE position > :cur_pos and position <= :insert_pos';
        $DB->execute($sql, ['cur_pos' => $cur,
            'insert_pos' => $insert]);
    }
}
