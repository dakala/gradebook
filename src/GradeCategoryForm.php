<?php

/**
 * @file
 * Contains \Drupal\grade_category\GradeCategorySetForm.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;

/**
 * Form controller for the grade category entity edit forms.
 */
class GradeCategoryForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    if ($entity->isNew()) {
      drupal_set_message(t('The %grade_category grade category has been created.', array('%grade_category' => $entity->label())));
    }
    else {
      drupal_set_message(t('Updated %grade_category grade category.', array('%grade_category' => $entity->label())));
    }

    $form_state->setRedirect(
      'entity.grade_category.canonical',
      array('grade_category' => $entity->id())
    );

  }

}
