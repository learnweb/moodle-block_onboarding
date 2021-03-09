<?php

class block_onboarding_steps_testcase extends advanced_testcase {
    public function test_add_step() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_s_steps'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 0;
        $fromform->name = "Test Step";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $this->assertTrue($DB->record_exists('block_onb_s_steps', array('name' => 'Test Step')));
    }

    public function test_update_step() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_s_steps'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 0;
        $fromform->name = "Test Step";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $this->assertTrue($DB->record_exists('block_onb_s_steps', array('name' => 'Test Step')));

        $step = $DB->get_record('block_onb_s_steps', array('name' => 'Test Step'));

        $fromform = new \stdClass();
        $fromform->id = $step->id;
        $fromform->position = 0;
        $fromform->name = "Test Step New";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $this->assertTrue($DB->record_exists('block_onb_s_steps', array('name' => 'Test Step New')));
    }

    public function test_update_step_position() {
        global $DB;
        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('block_onb_s_steps'));

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 0;
        $fromform->name = "Test Step 1";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->position = 1;
        $fromform->name = "Test Step 2";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $step1 = $DB->get_record('block_onb_s_steps', array('name' => 'Test Step 1'));
        $step2 = $DB->get_record('block_onb_s_steps', array('name' => 'Test Step 2'));

        $this->assertEquals(1, $step1->position);
        $this->assertEquals(2, $step2->position);

        $fromform = new \stdClass();
        $fromform->id = $step2->id;
        $fromform->position = 0;
        $fromform->name = "Test Step 2";
        $fromform->description = "Test Description";
        $fromform->achievement = False;

        \block_onboarding\steps_lib::edit_step($fromform);

        $step1 = $DB->get_record('block_onb_s_steps', array('name' => 'Test Step 1'));
        $step2 = $DB->get_record('block_onb_s_steps', array('name' => 'Test Step 2'));

        $this->assertEquals(2, $step1->position);
        $this->assertEquals(1, $step2->position);
    }
}
