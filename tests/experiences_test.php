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

class block_onboarding_experiences_testcase extends advanced_testcase {

    public function test_add_experience() {
        global $DB;
        $this->resetAfterTest(true);

        $this->setAdminUser();
        global $USER;

        $this->assertEquals(0, $DB->count_records('block_onb_e_exps'));

        $course = new \stdClass();
        $course->name = "Test Course";
        $course->timecreated = time();
        $course->timemodified = time();
        $course_id = $DB->insert_record('block_onb_e_courses', $course);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->user_id = $USER->id;
        $fromform->course_id = $course_id;
        $fromform->name = "Test Experience";

        \block_onboarding\experiences_lib::edit_experience($fromform);

        $this->assertTrue($DB->record_exists('block_onb_e_exps', array('name' => 'Test Experience')));
    }

    public function test_update_experience() {
        global $DB;
        $this->resetAfterTest(true);

        $this->setAdminUser();
        global $USER;

        $this->assertEquals(0, $DB->count_records('block_onb_e_exps'));

        $course = new \stdClass();
        $course->name = "Test Course";
        $course->timecreated = time();
        $course->timemodified = time();
        $course_id = $DB->insert_record('block_onb_e_courses', $course);

        $fromform = new \stdClass();
        $fromform->id = -1;
        $fromform->user_id = $USER->id;
        $fromform->course_id = $course_id;
        $fromform->name = "Test Experience";

        \block_onboarding\experiences_lib::edit_experience($fromform);

        $this->assertTrue($DB->record_exists('block_onb_e_exps', array('name' => 'Test Experience')));

        $experience = $DB->get_record('block_onb_e_exps', array('name' => 'Test Experience'));

        $fromform = new \stdClass();
        $fromform->id = $experience->id;
        $fromform->user_id = $USER->id;
        $fromform->course_id = $course_id;
        $fromform->name = "Test Experience New";

        \block_onboarding\experiences_lib::edit_experience($fromform);

        $this->assertTrue($DB->record_exists('block_onb_e_exps', array('name' => 'Test Experience New')));
    }
}
