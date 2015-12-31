<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleInterface.
 */

namespace Drupal\grade_scale;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a shortcut set entity.
 */
interface GradeScaleInterface extends ContentEntityInterface, EntityOwnerInterface {


}
