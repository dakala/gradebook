<?php

/**
 * @file
 * Contains \Drupal\node\Controller\NodeViewController.
 */

namespace Drupal\grade_scale\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Controller\EntityViewController;

/**
 * Defines a controller to render a single grade scale.
 */
class GradeScaleViewController extends EntityViewController {

  /**
   * The _title_callback for the page that renders a single node.
   *
   * @param \Drupal\Core\Entity\EntityInterface $grade_scale
   *   The current node.
   *
   * @return string
   *   The page title.
   */
  public function title(EntityInterface $grade_scale) {
    return \Drupal::service('entity.repository')->getTranslationFromContext($grade_scale)->label();
  }

}
