<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradescoresetAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the grade category entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeItem
 */
class GradeScoreAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if ($account->hasPermission('access grade scores') && $account->isAuthenticated() && $account->id() == $entity->getOwnerId()) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);
        }

      case 'update':
        if ($account->hasPermission('administer grade scores')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access grade scores')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade scores') && $entity == grade_scale_current_displayed_set($account))->cachePerPermissions()->addCacheableDependency($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade scores') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade scores')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade scores', 'customize grade scores'], 'AND'));
  }

}
