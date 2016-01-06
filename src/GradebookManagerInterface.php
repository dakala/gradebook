<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradebookManagerInterface.
 */

namespace Drupal\gradebook;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides gradebook manager interface.
 */
interface GradebookManagerInterface {

  public function getGradebookActivityOptions();

  public function getGradebookEnabledEntities();

  public function isGradebookEnabled($entity);
}