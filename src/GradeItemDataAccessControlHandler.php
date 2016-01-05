<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeItemDatasetAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the grade item data entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeItemData
 */
class GradeItemDataAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if ($account->hasPermission('access grade item data') && $account->isAuthenticated() && $account->id() == $entity->getOwnerId()) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->cacheUntilEntityChanges($entity);
        }

      case 'update':
        if ($account->hasPermission('administer grade item data')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access grade item data')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade item data') && $entity == grade_scale_current_displayed_set($account))->cachePerPermissions()->cacheUntilEntityChanges($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade item data') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade item data')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade item data', 'customize grade item data'], 'AND'));
  }

}
