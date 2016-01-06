<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeScoreDeleteForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Url;

/**
 * Builds the grade score deletion form.
 */
class GradeScoreDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'grade_score_confirm_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.grade_score.list_form', array(
      'grade_score' => $this->entity->bundle(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }

}
