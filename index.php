<?php

// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>;.

/**
 * @package     local_greetings
 * @copyright   2022 mohammad <mohammad.bakkar89080@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot. '/local/greetings/lib.php');
require_once($CFG->dirroot.'/local/greetings/simplehtmlForm.php');

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);

$PAGE->set_heading(get_string('pluginname', 'local_greetings'));
//$PAGE->navigation->add("kkkk");
echo $OUTPUT->header();
// Instantiate simplehtml_form
$mform = new simplehtml_form();

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    // Set default data (if any).
    $mform->set_data($toform);
    // Displays the form.
    $mform->display();
}
//
//$now = time();
//echo userdate($now);
//echo '<h2>Greetings, ' . fullname($USER) . '</h2>';
////echo get_string('greetingloggedinuser', 'local_greetings', fullname($USER));
//echo local_greetings_get_greeting($USER);
//echo '<h2>Greetings, user</h2>';
//echo get_string('greetinguser', 'local_greetings');
//if (isloggedin()) {
//    echo '<h2>Greetings, ' . fullname($USER) . '</h2>';
//
//} else {
//    echo '<h2>Greetings, user</h2>';
//}
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");



echo $OUTPUT->footer();
