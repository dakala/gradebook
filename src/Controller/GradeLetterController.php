<?php

/**
 * @file
 * Contains \Drupal\gradebook\Controller\GradeLetterController.
 */

namespace Drupal\gradebook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\gradebook\GradeLetterSetInterface;
use Drupal\gradebook\GradeLetterInterface;

/**
 * Provides route responses for taxonomy.module.
 */
class GradeLetterController extends ControllerBase {

  /**
   * Returns a form to add a new grade letter to a given set.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $grade_letter_set
   *   The grade letter set this grade letter will be added to.
   *
   * @return array
   *   The grade letter add form.
   */
  public function addForm(GradeLetterSetInterface $grade_letter_set) {
    $grade_letter = $this->entityManager()->getStorage('grade_letter')->create(array('grade_letter_set' => $grade_letter_set->id()));
    return $this->entityFormBuilder()->getForm($grade_letter, 'add');
  }

  /**
   * The _title_callback for the entity.grade_letter_set.list_form route.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $grade_letter_set
   *   The current grade letter set.
   *
   * @return string
   *   The page title.
   */
  public function pageTitle(GradeLetterSetInterface $grade_letter_set) {
    return $this->t('Grade letters: @set', array('@set' => $grade_letter_set->label()));
  }
}
