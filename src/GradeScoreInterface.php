<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleInterface.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a grade score entity.
 */
interface GradeScoreInterface extends ContentEntityInterface, EntityOwnerInterface {

  public function getTitle();

  public function setTitle($title);

  public function getFeedback();

  public function setFeedback($feedback);


  public function getHidden();

  public function setHidden($hidden);

  public function getLocked();

  public function setLocked($locked);

  public function getWeight();

  public function setWeight($weight);

}
