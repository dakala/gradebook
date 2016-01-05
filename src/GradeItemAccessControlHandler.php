<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeItemsetAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the grade item entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeItem
 */
class GradeItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if ($account->hasPermission('access grade items') && $account->isAuthenticated() && $account->id() == $entity->getOwnerId()) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->cacheUntilEntityChanges($entity);
        }

      case 'update':
        if ($account->hasPermission('administer grade items')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access grade items')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade items') && $entity == grade_scale_current_displayed_set($account))->cachePerPermissions()->cacheUntilEntityChanges($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade items') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade items')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade items', 'customize grade items'], 'AND'));
  }

}
