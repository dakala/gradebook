<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleSetAccessControlHandler.
 */

namespace Drupal\grade_scale;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the shortcut set entity type.
 *
 * @see \Drupal\grade_scale\Entity\GradeScale
 */
class GradeScaleAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if ($account->hasPermission('access grade scales') && $account->isAuthenticated() && $account->id() == $entity->getOwnerId()) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);
        }

      case 'update':
        if ($account->hasPermission('administer grade scales')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access grade scales')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade scales') && $entity == grade_scale_current_displayed_set($account))->cachePerPermissions()->addCacheableDependency($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade scales') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade scales')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade scales', 'customize grade scales'], 'AND'));
  }

}
