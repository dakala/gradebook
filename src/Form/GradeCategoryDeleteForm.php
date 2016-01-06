<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeLetterDeleteForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Url;

/**
 * Builds the grade category deletion form.
 */
class GradeCategoryDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'grade_category_confirm_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.grade_category.collection');
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }
}
