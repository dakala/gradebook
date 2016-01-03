<?php

/**
 * @file
 * Contains \Drupal\grade_item\GradeItemSetForm.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;

/**
 * Form controller for the grade item entity edit forms.
 */
class GradeItemForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    if ($entity->isNew()) {
      drupal_set_message(t('The %grade_item grade item has been created.', array('%grade_item' => $entity->label())));
    }
    else {
      drupal_set_message(t('Updated %grade_item grade item.', array('%grade_item' => $entity->label())));
    }

    $form_state->setRedirect(
      'entity.grade_item.canonical',
      array('grade_item' => $entity->id())
    );

  }

}
