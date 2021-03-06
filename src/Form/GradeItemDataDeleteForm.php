<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeLetterDeleteForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Url;

/**
 * Builds the grade item data deletion form.
 */
class GradeItemDataDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'grade_item_data_confirm_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.grade_item_data.collection');
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }
}
