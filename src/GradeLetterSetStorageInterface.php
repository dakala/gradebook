<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradeLetterSetStorageInterface.
 */

namespace Drupal\gradebook;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an interface for grade_letter_set entity storage classes.
 */
interface GradeLetterSetStorageInterface extends ConfigEntityStorageInterface {

  /**
   * Assigns a user to a particular grade letter set.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $shortcut_set
   *   An object representing the grade letter set.
   * @param $account
   *   A user account that will be assigned to use the set.
   */
  public function assignUser(GradeLetterSetInterface $shortcut_set, $account);

  /**
   * Unassigns a user from any grade letter set they may have been assigned to.
   *
   * The user will go back to using whatever default set applies.
   *
   * @param $account
   *   A user account that will be removed from the grade letter set assignment.
   *
   * @return bool
   *   TRUE if the user was previously assigned to a grade letter set and has been
   *   successfully removed from it. FALSE if the user was already not assigned
   *   to any set.
   */
  public function unassignUser($account);

  /**
   * Delete shortcut sets assigned to users.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $entity
   *   Delete the user assigned sets belonging to this grade_letter.
   */
  public function deleteAssignedGradeLetterSets(GradeLetterSetInterface $entity);

  /**
   * Get the name of the set assigned to this user.
   *
   * @param \Drupal\user\Entity\User
   *   The user account.
   *
   * @return string
   *   The name of the grade letter set assigned to this user.
   */
  public function getAssignedToUser($account);

  /**
   * Get the number of users who have this set assigned to them.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $shortcut_set
   *   The shortcut to count the users assigned to.
   *
   * @return int
   *   The number of users who have this set assigned to them.
   */
  public function countAssignedUsers(GradeLetterSetInterface $shortcut_set);

  /**
   * Gets the default grade letter set for a given user account.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account whose default grade letter set will be returned.
   *
   * @return \Drupal\gradebook\GradeLetterSetInterface
   *   An object representing the default grade letter set.
   */
  public function getDefaultSet(AccountInterface $account);

}
