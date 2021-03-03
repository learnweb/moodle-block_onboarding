<?php

class block_onboarding_steps_testcase extends advanced_testcase {
    public function test_add_step() {
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = new \stdClass();
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));
    }

    public function test_update_step(){
        global $DB;

        $this->assertEquals(0, $DB->count_records('block_onb_w_categories'));

        $fromform = new \stdClass();
        $fromform->name = "Test Category";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category')));

        $fromform = new \stdClass();
        $fromform->id = 1;
        $fromform->name = "Test Category New";

        \block_onboarding\wiki_lib::edit_category($fromform);

        $this->assertTrue($DB->record_exists('block_onb_w_categories', array('name' => 'Test Category New')));
    }
}