<?php

class block_onboarding_experiences_testcase extends advanced_testcase {
    public function test_add_experience() {
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = \stdClass();
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
    }

    public function test_update_experience(){
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
}