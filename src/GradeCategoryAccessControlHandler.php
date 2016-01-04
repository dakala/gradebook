<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeCategoriesetAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the shortcut set entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeItem
 */
class GradeCategoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if ($account->hasPermission('access grade categories') && $account->isAuthenticated() && $account->id() == $entity->getOwnerId()) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->cacheUntilEntityChanges($entity);
        }

      case 'update':
        if ($account->hasPermission('administer grade categories')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access grade categories')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade categories') && $entity == grade_scale_current_displayed_set($account))->cachePerPermissions()->cacheUntilEntityChanges($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade categories') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade categories')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade categories', 'customize grade categories'], 'AND'));
  }

}
