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
   * Returns a form to add a new shortcut to a given set.
   *
   * @param \Drupal\gradebook\GradeLetterSetInterface $grade_letter_set
   *   The grade letter set this shortcut will be added to.
   *
   * @return array
   *   The shortcut add form.
   */
  public function addForm(GradeLetterSetInterface $grade_letter_set) {
    $grade_letter = $this->entityManager()->getStorage('grade_letter')->create(array('grade_letter_set' => $grade_letter_set->id()));
    return $this->entityFormBuilder()->getForm($grade_letter, 'add');
  }

  /**
   * Deletes the selected grade_letter.
   *
   * @param \Drupal\gradebook\GradeLetterInterface $shortcut
   *   The shortcut to delete.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect to the previous location or the front page when destination
   *   is not set.
   */
  public function deleteShortcutLinkInline(GradeLetterInterface $shortcut) {
    $label = $shortcut->label();

    try {
      $shortcut->delete();
      drupal_set_message($this->t('The grade letter %title has been deleted.', array('%title' => $label)));
    }
    catch (\Exception $e) {
      drupal_set_message($this->t('Unable to delete the grade letter for %title.', array('%title' => $label)), 'error');
    }

    return $this->redirect('<front>');
  }

}
