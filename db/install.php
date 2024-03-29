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
 * Install file
 *
 * @package   assignfeedback_misconduct
 * @copyright 2017 Southampton Solent University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Install function for misconduct
 *
 * @return boolean Always true.
 */
function xmldb_assignfeedback_misconduct_install() {
    global $CFG;
    require_once($CFG->dirroot . '/mod/assign/adminlib.php');

    // Set the correct initial order for the plugins.
    $pluginmanager = new assign_plugin_manager('assignfeedback');
    $pluginmanager->move_plugin('file', 'down');

    return true;
}
