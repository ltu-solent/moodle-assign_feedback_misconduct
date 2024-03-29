@mod @mod_assign @assignfeedback @assignfeedback_misconduct @sol @solassignfeedback
Feature: In an assignment, teachers can mark a submission for misconduct
  In order to identify misconduct submissions,
  As a teacher
  I need to add a misconduct "check" against their submissions.

  Background:
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
      | student2 | C1 | student |
    And the following "activity" exists:
      | activity                            | assign               |
      | course                              | C1                   |
      | name                                | Test assignment name |
      | assignsubmission_onlinetext_enabled | 1                    |
      | assignfeedback_misconduct_enabled   | 1                    |
    And the following "mod_assign > submissions" exist:
      | assign                | user      | onlinetext                   |
      | Test assignment name  | student1  | I'm the student1 submission  |
      | Test assignment name  | student2  | I'm the student2 submission  |

  @javascript
  Scenario: A teacher should be able to be mark a submission for misconduct
    Given I log in as "teacher1"
    And I am on the "Test assignment name" "assignfeedback_misconduct > View all submissions" page
    And I click on "Grade" "link" in the "Student 1" "table_row"
    And I set the field "Referred for academic misconduct" to "1"
    And I press "Save changes"
    Given I am on the "Test assignment name" "assignfeedback_misconduct > View all submissions" page
    Then "Student 1" row "Academic misconduct" column of "generaltable" table should contain "Yes"
    And "Student 2" row "Academic misconduct" column of "generaltable" table should not contain "Yes"
