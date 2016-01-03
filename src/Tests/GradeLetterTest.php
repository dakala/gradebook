<?php

/**
 * @file
 * Contains \Drupal\gradebook\Tests\GradeLetterSetsTest.
 */

namespace Drupal\gradebook\Tests;

use Drupal\gradebook\Entity\GradeLetterSet;

/**
 * Create, view, edit, delete, and change shortcut sets.
 *
 * @group shortcut
 */
class GradeLetterSetsTest extends ShortcutTestBase {

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  public static $modules = ['block'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalPlaceBlock('local_actions_block');
  }

  /**
   * Tests creating a grade letter set.
   */
  function testGradeLetterSetAdd() {
    $this->drupalGet('admin/config/user-interface/shortcut');
    $this->clickLink(t('Add grade letter set'));
    $edit = array(
      'label' => $this->randomMachineName(),
      'id' => strtolower($this->randomMachineName()),
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));
    $new_set = $this->container->get('entity.manager')->getStorage('grade_letter_set')->load($edit['id']);
    $this->assertIdentical($new_set->id(), $edit['id'], 'Successfully created a grade letter set.');
    $this->drupalGet('user/' . $this->adminUser->id() . '/shortcuts');
    $this->assertText($new_set->label(), 'Generated grade letter set was listed as a choice on the user account page.');
  }

  /**
   * Tests editing a grade letter set.
   */
  function testGradeLetterSetEdit() {
    $set = $this->set;
    $shortcuts = $set->getGradeLetters();

    // Visit the grade letter set edit admin ui.
    $this->drupalGet('admin/config/user-interface/shortcut/manage/' . $set->id() . '/customize');

    // Test for the page title.
    $this->assertTitle(t('List links') . ' | Drupal');

    // Test for the table.
    $element = $this->xpath('//div[@class="layout-content"]//table');
    $this->assertTrue($element, 'GradeLetter entity list table found.');

    // Test the table header.
    $elements = $this->xpath('//div[@class="layout-content"]//table/thead/tr/th');
    $this->assertEqual(count($elements), 3, 'Correct number of table header cells found.');

    // Test the contents of each th cell.
    $expected_items = array(t('Name'), t('Weight'), t('Operations'));
    foreach ($elements as $key => $element) {
      $this->assertEqual((string) $element[0], $expected_items[$key]);
    }

    // Look for test shortcuts in the table.
    $weight = count($shortcuts);
    $edit = array();
    foreach ($shortcuts as $shortcut) {
      $title = $shortcut->getTitle();

      // Confirm that a link to the shortcut is found within the table.
      $this->assertLink($title);

      // Look for a test shortcut weight select form element.
      $this->assertFieldByName('shortcuts[links][' . $shortcut->id() . '][weight]');

      // Change the weight of the grade_letter.
      $edit['shortcuts[links][' . $shortcut->id() . '][weight]'] = $weight;
      $weight--;
    }

    $this->drupalPostForm(NULL, $edit, t('Save changes'));
    $this->assertRaw(t('The grade letter set has been updated.'));

    \Drupal::entityManager()->getStorage('shortcut')->resetCache();
    // Check to ensure that the shortcut weights have changed and that
    // GradeLetterSet::.getGradeLetters() returns shortcuts in the new order.
    $this->assertIdentical(array_reverse(array_keys($shortcuts)), array_keys($set->getShortcuts()));
  }

  /**
   * Tests switching a user's own grade letter set.
   */
  function testGradeLetterSetSwitchOwn() {
    $new_set = $this->generateGradeLetterSet($this->randomMachineName());

    // Attempt to switch the default grade letter set to the newly created shortcut
    // set.
    $this->drupalPostForm('user/' . $this->adminUser->id() . '/shortcuts', array('set' => $new_set->id()), t('Change set'));
    $this->assertResponse(200);
    $current_set = shortcut_current_displayed_set($this->adminUser);
    $this->assertTrue($new_set->id() == $current_set->id(), 'Successfully switched own grade letter set.');
  }

  /**
   * Tests switching another user's grade letter set.
   */
  function testGradeLetterSetAssign() {
    $new_set = $this->generateGradeLetterSet($this->randomMachineName());

    \Drupal::entityManager()->getStorage('grade_letter_set')->assignUser($new_set, $this->shortcutUser);
    $current_set = shortcut_current_displayed_set($this->shortcutUser);
    $this->assertTrue($new_set->id() == $current_set->id(), "Successfully switched another user's grade letter set.");
  }

  /**
   * Tests switching a user's grade letter set and creating one at the same time.
   */
  function testGradeLetterSetSwitchCreate() {
    $edit = array(
      'set' => 'new',
      'id' => strtolower($this->randomMachineName()),
      'label' => $this->randomString(),
    );
    $this->drupalPostForm('user/' . $this->adminUser->id() . '/shortcuts', $edit, t('Change set'));
    $current_set = shortcut_current_displayed_set($this->adminUser);
    $this->assertNotEqual($current_set->id(), $this->set->id(), 'A grade letter set can be switched to at the same time as it is created.');
    $this->assertEqual($current_set->label(), $edit['label'], 'The new set is correctly assigned to the user.');
  }

  /**
   * Tests switching a user's grade letter set without providing a new set name.
   */
  function testGradeLetterSetSwitchNoSetName() {
    $edit = array('set' => 'new');
    $this->drupalPostForm('user/' . $this->adminUser->id() . '/shortcuts', $edit, t('Change set'));
    $this->assertText(t('The new set label is required.'));
    $current_set = shortcut_current_displayed_set($this->adminUser);
    $this->assertEqual($current_set->id(), $this->set->id(), 'Attempting to switch to a new grade letter set without providing a set name does not succeed.');
    $this->assertFieldByXPath("//input[@name='label' and contains(concat(' ', normalize-space(@class), ' '), ' error ')]", NULL, 'The new set label field has the error class');
  }

  /**
   * Tests renaming a grade letter set.
   */
  function testGradeLetterSetRename() {
    $set = $this->set;

    $new_label = $this->randomMachineName();
    $this->drupalGet('admin/config/user-interface/shortcut');
    $this->clickLink(t('Edit grade letter set'));
    $this->drupalPostForm(NULL, array('label' => $new_label), t('Save'));
    $set = GradeLetterSet::load($set->id());
    $this->assertTrue($set->label() == $new_label, 'GradeLetter set has been successfully renamed.');
  }

  /**
   * Tests unassigning a grade letter set.
   */
  function testGradeLetterSetUnassign() {
    $new_set = $this->generateGradeLetterSet($this->randomMachineName());

    $grade_letter_set_storage = \Drupal::entityManager()->getStorage('grade_letter_set');
    $grade_letter_set_storage->assignUser($new_set, $this->shortcutUser);
    $grade_letter_set_storage->unassignUser($this->shortcutUser);
    $current_set = shortcut_current_displayed_set($this->shortcutUser);
    $default_set = shortcut_default_set($this->shortcutUser);
    $this->assertTrue($current_set->id() == $default_set->id(), "Successfully unassigned another user's grade letter set.");
  }

  /**
   * Tests deleting a grade letter set.
   */
  function testGradeLetterSetDelete() {
    $new_set = $this->generateGradeLetterSet($this->randomMachineName());

    $this->drupalPostForm('admin/config/user-interface/shortcut/manage/' . $new_set->id() . '/delete', array(), t('Delete'));
    $sets = GradeLetterSet::loadMultiple();
    $this->assertFalse(isset($sets[$new_set->id()]), 'Successfully deleted a grade letter set.');
  }

  /**
   * Tests deleting the default grade letter set.
   */
  function testGradeLetterSetDeleteDefault() {
    $this->drupalGet('admin/config/user-interface/shortcut/manage/default/delete');
    $this->assertResponse(403);
  }

  /**
   * Tests creating a new grade letter set with a defined set name.
   */
  function testGradeLetterSetCreateWithSetName() {
    $random_name = $this->randomMachineName();
    $new_set = $this->generateGradeLetterSet($random_name, $random_name);
    $sets = GradeLetterSet::loadMultiple();
    $this->assertTrue(isset($sets[$random_name]), 'Successfully created a grade letter set with a defined set name.');
    $this->drupalGet('user/' . $this->adminUser->id() . '/shortcuts');
    $this->assertText($new_set->label(), 'Generated grade letter set was listed as a choice on the user account page.');
  }
}
