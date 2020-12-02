<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradeLetterAccessControlHandler.
 */

namespace Drupal\gradebook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the access control handler for the grade letter entity type.
 *
 * @see \Drupal\gradebook\Entity\GradeLetter
 */
class GradeLetterAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'update':
        return AccessResult::allowedIf($account->hasPermission('administer grade letters'))->cachePerPermissions()->addCacheableDependency($entity);

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('administer grade letters') && $entity->getGradeLetterSet() != 'default')->cachePerPermissions();

      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'administer grade letters');
  }


}
