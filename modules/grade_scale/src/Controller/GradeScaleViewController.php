<?php

/**
 * @file
 * Contains \Drupal\node\Controller\NodeViewController.
 */

namespace Drupal\grade_scale\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Controller\EntityViewController;

/**
 * Defines a controller to render a single node.
 */
class GradeScaleViewController extends EntityViewController {

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $grade_scale, $view_mode = 'full', $langcode = NULL) {
    $build = parent::view($grade_scale, $view_mode, $langcode);

    foreach ($grade_scale->uriRelationships() as $rel) {
      // Set the node path as the canonical URL to prevent duplicate content.
      $build['#attached']['html_head_link'][] = array(
        array(
          'rel' => $rel,
          'href' => $grade_scale->url($rel),
        ),
        TRUE,
      );

      if ($rel == 'canonical') {
        // Set the non-aliased canonical path as a default shortlink.
        $build['#attached']['html_head_link'][] = array(
          array(
            'rel' => 'shortlink',
            'href' => $grade_scale->url($rel, array('alias' => TRUE)),
          ),
          TRUE,
        );
      }
    }

    return $build;
  }

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
    return $this->entityManager->getTranslationFromContext($grade_scale)->label();
  }

}
