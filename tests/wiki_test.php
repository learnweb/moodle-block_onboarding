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

class block_onboarding_wiki_testcase extends advanced_testcase {
    public function test_add_category() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
        
        $this->expectException(\exception::class);
    }

    public function test_add_category_without_name() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;

        $this->expectException(\exception::class);
        \block_onboarding\wiki_lib::edit_category($fromform);

        //$this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
    }

    public function test_update_category() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));

        $link = $DB->get_record('block_onb_w_categories', array('name' => 'Test Category'));

        $fromform = new \stdClass();
        $fromform->id = $link->id;
        $fromform->position = 1;
        $fromform->name = "Test Category New";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category New')));
    }

    public function test_add_link() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_links'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->description = "Test Description";
        $fromform->category_id = 1;
        $fromform->url = "Test URL";
        $fromform->name = "Test Link";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link')));
    }

    public function test_add_link_without_name() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_links'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;

        \block_onboarding\wiki_lib::edit_category($fromform);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->description = "Test Description";
        $fromform->category_id = 1;
        $fromform->url = "Test URL";
        $fromform->name = "Test Link";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link')));
    }

    public function test_update_link() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_w_links'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->description = "Test Description";
        $fromform->category_id = 1;
        $fromform->url = "Test URL";
        $fromform->name = "Test Link";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link')));

        $link = $DB->get_record('block_onb_w_links', array('name' => 'Test Link'));

        $fromform = new \stdClass();
        $fromform->id = $link->id;
        $fromform->position = 1;
        $fromform->description = "Test Description";
        $fromform->category_id = 1;
        $fromform->url = "Test URL";
        $fromform->name = "Test Link New";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link New')));
    }
}
