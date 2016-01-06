<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradeScoreForm.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;

/**
 * Form controller for the grade score entity edit forms.
 */
class GradeScoreForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    if ($entity->isNew()) {
      drupal_set_message(t('The %grade_score grade score has been created.', array('%grade_score' => $entity->label())));
    }
    else {
      drupal_set_message(t('Updated %grade_score grade score.', array('%grade_score' => $entity->label())));
    }

    $form_state->setRedirect(
      'entity.grade_score.canonical',
      array('grade_score' => $entity->id())
    );

  }

}
