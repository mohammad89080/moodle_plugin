<?php
/**
 * @package     local_greetings
 * @copyright   2022 mohammad <mohammad.bakkar89080@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// moodleform is defined in formslib.php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');
class local_greetings_message_form extends moodleform {

    public function definition() {
        $mform    = $this->_form; // Don't forget the underscore!

        $mform->addElement('textarea', 'message', get_string('yourmessage', 'local_greetings'));
        $mform->setType('message', PARAM_TEXT);
        $submitlabel = get_string('submit');
        $mform->addElement('submit', 'submitmessage', $submitlabel);
    }
}
//class simplehtml_form extends moodleform {
//// Add elements to form.
//    public function definition() {
//        global $CFG;
//
//        $mform = $this->_form; // Don't forget the underscore!
//
//        $mform->addElement('text', 'email', get_string('email')); // Add elements to your form.
//        $mform->setType('email', PARAM_NOTAGS);                   // Set type of element.
//        $mform->setDefault('email', 'Please enter email');        // Default value.
//
//    }
//// Custom validation should be added here.
//    function validation($data, $files) {
//        return array();
//    }
