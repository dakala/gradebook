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
 * Form controller for the grade item data entity edit forms.
 */
class GradeItemDataForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    if ($entity->isNew()) {
      drupal_set_message(t('The %grade_item_data grade item data has been created.', array('%grade_item_data' => $entity->label())));
    }
    else {
      drupal_set_message(t('Updated %grade_item_data grade item data.', array('%grade_item_data' => $entity->label())));
    }

    $form_state->setRedirect(
      'entity.grade_item_data.canonical',
      array('grade_item_data' => $entity->id())
    );

  }

}
