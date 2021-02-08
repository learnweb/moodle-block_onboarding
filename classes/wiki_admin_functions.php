<?php
// This file is part of a plugin for Moodle - http://moodle.org/
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
* functions to handle database calls when editing wiki content
*
* @package    block_onboarding
* @copyright  2021 Westfälische Wilhelms-Universität Münster
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

namespace block_onboarding;
defined('MOODLE_INTERNAL') || die();

class wiki_admin_functions
{

    /**
     * Beschreibung hinzufügen!
     */
    public static function increment_category_positions($insert, $cur)
    {
        global $DB;

        $sql = 'UPDATE {block_onb_w_categories}
            SET position = position +1
            WHERE position >= :insert_pos and position < :cur_pos';
        $DB->execute($sql, ['cur_pos' => $cur, 'insert_pos' => $insert]);
    }

    /**
     * Beschreibung hinzufügen!
     */
    public static function decrement_category_positions($insert, $cur)
    {
        global $DB;

        $sql = 'UPDATE {block_onb_w_categories}
                SET position = position -1
                WHERE position > :cur_pos and position <= :insert_pos';
        $DB->execute($sql, ['cur_pos' => $cur, 'insert_pos' => $insert]);
    }

    /**
     * Beschreibung hinzufügen!
     */
    public static function increment_link_positions($insert, $cur)
    {
        global $DB;

        $sql = 'UPDATE {block_onb_w_links}
            SET position = position +1
            WHERE position >= :insert_pos and position < :cur_pos';
        $DB->execute($sql, ['cur_pos' => $cur, 'insert_pos' => $insert]);
    }

    /**
     * Beschreibung hinzufügen!
     */
    public static function decrement_link_positions($insert, $cur)
    {
        global $DB;

        $sql = 'UPDATE {block_onb_w_links}
                SET position = position -1
                WHERE position > :cur_pos and position <= :insert_pos';
        $DB->execute($sql, ['cur_pos' => $cur, 'insert_pos' => $insert]);
    }
}