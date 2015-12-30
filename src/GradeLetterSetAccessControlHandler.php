<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradeLetterSetAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the grade letter set entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeLetterSet
 */
class GradeLetterSetAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'update':
        if ($account->hasPermission('administer shortcuts')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        if (!$account->hasPermission('access shortcuts')) {
          return AccessResult::neutral()->cachePerPermissions();
        }
        return AccessResult::allowedIf($account->hasPermission('customize grade letters') && $entity == shortcut_current_displayed_set($account))->cachePerPermissions()->cacheUntilEntityChanges($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade letters') && $entity->id() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade letters')->orIf(AccessResult::allowedIfHasPermissions($account, ['access grade letters', 'customize grade letters'], 'AND'));
  }

}
