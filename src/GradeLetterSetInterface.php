<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradeLetterSetInterface.
 */

namespace Drupal\gradebook;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a grade letter set entity.
 */
interface GradeLetterSetInterface extends ConfigEntityInterface {

//  /**
//   * Resets the link weights in a grade letter set to match their current order.
//   *
//   * This function can be used, for example, when a new shortcut link is added
//   * to the set. If the link is added to the end of the array and this function
//   * is called, it will force that link to display at the end of the list.
//   *
//   * @return \Drupal\gradebook\GradeLetterSetInterface
//   *   The grade letter set.
//   */
//  public function resetLinkWeights();

  /**
   * Returns all the shortcuts from a grade letter set sorted correctly.
   *
   * @return \Drupal\gradebook\GradeLetterInterface[]
   *   An array of shortcut entities.
   */
  public function getGradeLetters();

}
