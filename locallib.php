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

/**
 * Feedback class file
 * @package   assignfeedback_misconduct
 * @copyright 2017 Southampton Solent University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Misconduct feedback class
 */
class assign_feedback_misconduct extends assign_feedback_plugin {

    /**
     * Returns plugin name
     *
     * @return string
     */
    public function get_name() {
        return get_string('misconduct', 'assignfeedback_misconduct');
    }

    /**
     * Get misconduction entry
     *
     * @param int $gradeid
     * @return stdClass|false
     */
    public function get_misconduct($gradeid) {
        global $DB;
        return $DB->get_record('assignfeedback_misconduct', array('grade' => $gradeid));
    }

    /**
     * Form elements for grading an assignment
     *
     * @param stdClass $grade
     * @param MoodleQuickForm $mform
     * @param stdClass $data Formdata
     * @param int $userid
     * @return bool Returns true if the elements have been created
     */
    public function get_form_elements_for_user($grade, MoodleQuickForm $mform, stdClass $data, $userid) {
        if ($grade) {
            $misconduct = $this->get_misconduct($grade->id);
        }

        if ($misconduct) {
            if ($misconduct->misconduct != '0') {
                $check = $mform->addElement('checkbox', 'misconduct_check', get_string('check_label', 'assignfeedback_misconduct'));
                $mform->setDefault('misconduct_check', true);
            } else {
                $mform->addElement('checkbox', 'misconduct_check', get_string('check_label', 'assignfeedback_misconduct'));
            }

        } else {
            $mform->addElement('checkbox', 'misconduct_check', get_string('check_label', 'assignfeedback_misconduct'));
        }

        return true;
    }

    /**
     * Get the double marking grades from the database.
     *
     * @param stdClass $grade Grade item
     * @param stdClass $data Form data
     * @return boolean True if the feedback has been modified, else False.
     */
    public function is_feedback_modified(stdClass $grade, stdClass $data) {
        if ($grade) {
            $misconduct = $this->get_misconduct($grade->id);
        }

        if ($misconduct) {
            if ($misconduct->misconduct == $data->misconduct_check) {
                return false;
            }
        } else {
            return true;
        }
        return true;
    }

    /**
     * Save form data if the form has been modified
     *
     * @param stdClass $grade
     * @param stdClass $data Form data
     * @return bool|int Success on update or id for new record.
     */
    public function save(stdClass $grade, stdClass $data) {
        global $DB, $USER;
        $misconduct = $this->get_misconduct($grade->id);
        if ($misconduct) {
            if ($data->misconduct_check !== $misconduct->misconduct) {
                $misconduct->misconduct = ($data->misconduct_check != null ? 1 : 0);
            }
            return $DB->update_record('assignfeedback_misconduct', $misconduct);
        } else {
            $misconduct = new stdClass();
            $misconduct->assignment = $this->assignment->get_instance()->id;
            $misconduct->grade = $grade->id;
            $misconduct->misconduct = ($data->misconduct_check != null ? 1 : 0);
            $misconduct->userid = $USER->id;
            return $DB->insert_record('assignfeedback_misconduct', $misconduct) > 0;
        }
    }

    /**
     * Display the comment in the feedback table.
     *
     * @param stdClass $grade
     * @param bool $showviewlink Set to true to show a link to view the full feedback
     * @return string
     */
    public function view_summary(stdClass $grade, & $showviewlink) {
        $misconduct = $this->get_misconduct($grade->id);
        if ($misconduct) {
            if ($misconduct->misconduct == 1) {
                $misconducttext = "Yes";
                return format_text($misconducttext, FORMAT_HTML);
            }
        }
        return '';
    }
}
