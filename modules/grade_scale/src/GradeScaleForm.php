<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleSetForm.
 */

namespace Drupal\grade_scale;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;

/**
 * Form controller for the grade scale entity edit forms.
 */
class GradeScaleForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    if ($entity->isNew()) {
      drupal_set_message(t('The %grade_scale grade scale has been created.', array('%grade_scale' => $entity->label())));
    }
    else {
      drupal_set_message(t('Updated %grade_scale grade scale.', array('%grade_scale' => $entity->label())));
    }

    $form_state->setRedirect(
      'entity.grade_scale.canonical',
      array('grade_scale' => $entity->id())
    );

  }

}
