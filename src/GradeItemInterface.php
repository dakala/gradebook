<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleInterface.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a grade item entity.
 */
interface GradeItemInterface extends ContentEntityInterface, EntityOwnerInterface {


}
