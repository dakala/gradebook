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

  /**
   * Returns all the shortcuts from a grade letter set sorted correctly.
   *
   * @return \Drupal\gradebook\GradeLetterSetInterface[]
   *   An array of grade letter set entities.
   */
  public function getGradeLetters();

}
