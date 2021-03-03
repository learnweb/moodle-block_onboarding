<?php

class block_onboarding_wiki_testcase extends advanced_testcase {
    public function test_add_category() {
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
    }
}