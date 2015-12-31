<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeLetterSet.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\gradebook\GradeLetterSetInterface;

/**
 * Defines the GradeLetter set configuration entity.
 *
 * @ConfigEntityType(
 *   id = "grade_letter_set",
 *   label = @Translation("Grade letter set"),
 *   handlers = {
 *     "storage" = "Drupal\gradebook\GradeLetterSetStorage",
 *     "access" = "Drupal\gradebook\GradeLetterSetAccessControlHandler",
 *     "list_builder" = "Drupal\gradebook\GradeLetterSetListBuilder",
 *     "form" = {
 *       "default" = "Drupal\gradebook\GradeLetterSetForm",
 *       "add" = "Drupal\gradebook\GradeLetterSetForm",
 *       "edit" = "Drupal\gradebook\GradeLetterSetForm",
 *       "customize" = "Drupal\gradebook\Form\GradeLetterSetCustomize",
 *       "delete" = "Drupal\gradebook\Form\GradeLetterSetDeleteForm"
 *     }
 *   },
 *   config_prefix = "set",
 *   bundle_of = "grade_letter",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "customize-form" = "/admin/config/gradebook/grade_letter/manage/{grade_letter_set}/customize",
 *     "delete-form" = "/admin/config/gradebook/grade_letter/manage/{grade_letter_set}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_letter/manage/{grade_letter_set}",
 *     "collection" = "/admin/config/gradebook/grade_letter",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   }
 * )
 */
class GradeLetterSet extends ConfigEntityBundleBase implements GradeLetterSetInterface {

  /**
   * The machine name for the configuration entity.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the configuration entity.
   *
   * @var string
   */
  protected $label;

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    if (!$update && !$this->isSyncing()) {
      // Save a new grade letter set with links copied from the user's default set.
      $default_set = shortcut_default_set();
      // This is the default set, do not copy shortcuts.
      if ($default_set->id() != $this->id()) {
        foreach ($default_set->getGradeLetters() as $shortcut) {
          $shortcut = $shortcut->createDuplicate();
          $shortcut->enforceIsNew();
          $shortcut->grade_letter_set->target_id = $this->id();
          $shortcut->save();
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function preDelete(EntityStorageInterface $storage, array $entities) {
    parent::preDelete($storage, $entities);

    foreach ($entities as $entity) {
      $storage->deleteAssignedGradeLetterSets($entity);

      // Next, delete the shortcuts for this set.
      $shortcut_ids = \Drupal::entityQuery('grade_letter')
        ->condition('grade_letter_set', $entity->id(), '=')
        ->execute();

      $controller = \Drupal::entityManager()->getStorage('grade_letter');
      $entities = $controller->loadMultiple($shortcut_ids);
      $controller->delete($entities);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function resetLinkWeights() {
    $weight = -50;
    foreach ($this->getGradeLetters() as $shortcut) {
      $shortcut->setWeight(++$weight);
      $shortcut->save();
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeLetters() {
    $shortcuts = \Drupal::entityManager()->getStorage('grade_letter')->loadByProperties(array('grade_letter_set' => $this->id()));
    uasort($shortcuts, array('\Drupal\gradebook\Entity\GradeLetter', 'sort'));
    return $shortcuts;
  }

}
