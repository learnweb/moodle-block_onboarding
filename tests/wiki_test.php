<?php

class block_onboarding_wiki_testcase extends advanced_testcase {
    public function test_add_category() {
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = \stdClass();
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
    }

    public function test_update_category(){
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = \stdClass();
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));

        $fromform = \stdClass();
        $fromform->id = 1;
        $fromform->name = "Test Category New";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category New')));
    }

    public function test_add_link() {
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_links'));

        $fromform = \stdClass();
        $fromform->name = "Test Link";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link')));
    }

    public function test_update_link(){
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_links'));

        $fromform = \stdClass();
        $fromform->name = "Test Link";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link')));

        $fromform = \stdClass();
        $fromform->id = 1;
        $fromform->name = "Test Link New";

        \block_onboarding\wiki_lib::edit_link($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_links', array('name' => 'Test Link New')));
    }
}