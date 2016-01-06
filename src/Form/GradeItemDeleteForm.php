<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeLetterDeleteForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Url;

/**
 * Builds the grade item deletion form.
 */
class GradeItemDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'grade_item_confirm_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.grade_item.collection');
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }
}
