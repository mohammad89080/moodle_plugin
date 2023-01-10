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
require_once($CFG->dirroot . '/local/greetings/lib.php');
require_once($CFG->dirroot . '/local/greetings/message_form.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

$allowpost = has_capability('local/greetings:postmessages', $context);
$deletepost = has_capability('local/greetings:deleteownmessage', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);

$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {
    require_sesskey();

    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost || $deletepost) {
        $params = array('id' => $id);

        // Users without permission should only delete their own post.
        if(!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }

        // TODO: Confirm before deleting.
        $DB->delete_records('local_greetings_message', $params);

        redirect($PAGE->url);
    }
}

$messageform = new local_greetings_message_form();

if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);

    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_message', $record);
    }
}

echo $OUTPUT->header();

if (isloggedin()) {
    echo $OUTPUT->heading(local_greetings_get_greeting($USER));
} else {
    echo get_string('greetinguser', 'local_greetings');
}

if ($allowpost) {
    $messageform->display();
}

if (has_capability('local/greetings:viewmessages', $context)) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
              FROM {local_greetings_message} m
         LEFT JOIN {user} u ON u.id = m.userid
          ORDER BY timecreated DESC";

    $messages = $DB->get_records_sql($sql);

    echo $OUTPUT->box_start('card-columns');

    foreach ($messages as $m) {
        echo html_writer::start_tag('div', array('class' => 'card'));
        echo html_writer::start_tag('div', array('class' => 'card-body'));
        echo html_writer::tag('p', format_text($m->message, FORMAT_PLAIN), array('class' => 'card-text'));
        echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), array('class' => 'card-text'));
        echo html_writer::start_tag('p', array('class' => 'card-text'));
        echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
        echo html_writer::end_tag('p');

        if ($deleteanypost || ($deletepost && $m->userid == $USER->id)) {
            echo html_writer::start_tag('p', array('class' => 'card-footer text-center'));
            echo html_writer::link(
                new moodle_url(
                    '/local/greetings/index.php',
                    array('action' => 'del', 'id' => $m->id, 'sesskey' => sesskey())
                ),
                $OUTPUT->pix_icon('t/delete', '') . get_string('delete')
            );
            echo html_writer::end_tag('p');
        }

        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
    }

    echo $OUTPUT->box_end();
}

echo $OUTPUT->footer();
//----------------------------------------------------------
//require_once('../../config.php');
//require_once($CFG->dirroot. '/local/greetings/lib.php');
//require_once($CFG->dirroot. '/local/greetings/message_form.php');
////require_once($CFG->dirroot.'/local/greetings/simplehtmlForm.php');
//
//$context = context_system::instance();
//
//$PAGE->set_context($context);
//$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
//$PAGE->set_pagelayout('standard');
//$PAGE->set_title($SITE->fullname);
//
//$PAGE->set_heading(get_string('pluginname', 'local_greetings'));
//require_login();
//if (isguestuser()) {
//    throw new moodle_exception('noguest');
//}
//$PAGE->navigation->add("kkkk");
////------------------------------------------------------
//$messageform = new local_greetings_message_form();
//$allowpost = has_capability('local/greetings:postmessages', $context);
//$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);
//$action = optional_param('action', '', PARAM_TEXT);
//if ($action == 'del') {
//    $id = required_param('id', PARAM_TEXT);
//
//    if ($deleteanypost) {
//        $params = array('id' => $id);
//
//        $DB->delete_records('local_greetings_message', $params);
//    }
//}
////if ($action == 'del') {
////    $id = required_param('id', PARAM_TEXT);
////
////    $DB->delete_records('local_greetings_message', array('id' => $id));
////}
//echo $OUTPUT->header();
//if ($allowpost) {
//    $messageform->display();
//}
////$messageform->display();
//if ($data = $messageform->get_data()) {
//    require_capability('local/greetings:postmessages', $context);
//    $message = required_param('message', PARAM_TEXT);
//
//    if (!empty($message)) {
//        $record = new stdClass;
//        $record->message = $message;
//        $record->timecreated = time();
//        $record->userid = $USER->id;
//        echo $record->userid ;
//
//        $DB->insert_record('local_greetings_message', $record);
//    }
//
//}
////$messages=$DB->get_records('local_greetings_message');
//$userfields = \core_user\fields::for_name()->with_identity($context);
//$userfieldssql = $userfields->get_sql('u');
//
//$sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
//          FROM {local_greetings_message} m
//     LEFT JOIN {user} u ON u.id = m.userid
//      ORDER BY timecreated DESC";
//
//$messages = $DB->get_records_sql($sql);
////print_r($messages);
////foreach ($messages as $massage)
////{
//////    echo '<p>' . $m->message . ', ' . $m->timecreated . '</p>';
////    echo "<h6 style='background-color:#EEE;font-size: 16px'>". $massage->message . ', ' . userdate($massage->timecreated) . "</h6>";
////}
//
//foreach ($messages as $m) {
//    echo html_writer::start_tag('div', array('class' => 'card'));
//    echo html_writer::start_tag('div', array('class' => 'card-body'));
////    echo html_writer::tag('p', $m->message, array('class' => 'card-text'));
//    echo html_writer::tag('p', format_text($m->message, FORMAT_PLAIN), array('class' => 'card-text'));
//    echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), array('class' => 'card-text'));
//    echo html_writer::start_tag('p', array('class' => 'card-text'));
//    echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
//    echo html_writer::end_tag('p');
//    echo html_writer::end_tag('div');
//    echo html_writer::end_tag('div');
//    if ($allowpost) {
//        if ($deleteanypost) {
//        echo html_writer::start_tag('p', array('class' => 'card-footer text-center'));
//        echo html_writer::link(
//            new moodle_url(
//                '/local/greetings/index.php',
//                array('action' => 'del', 'id' => $m->id)
//            ),
//            $OUTPUT->pix_icon('t/delete', '') . get_string('delete')
//        );
//        echo html_writer::end_tag('p');
//    }
//    }
////    if ($deleteanypost) {
////        echo html_writer::start_tag('p', array('class' => 'card-footer text-center'));
////        echo html_writer::link(
////            new moodle_url(
////                '/local/greetings/index.php',
////                array('action' => 'del', 'id' => $m->id)
////            ),
////            $OUTPUT->pix_icon('t/delete', '') . get_string('delete')
////        );
////        echo html_writer::end_tag('p');
////    }
//}
//
//echo $OUTPUT->box_end();
//
//
//
//
//
//
//echo $OUTPUT->footer();
