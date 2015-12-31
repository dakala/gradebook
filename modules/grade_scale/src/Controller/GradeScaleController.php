<?php

/**
 * @file
 * Contains \Drupal\grade_scale\Controller\GradeScaleController.
 */

namespace Drupal\grade_scale\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\grade_scale\GradeScaleInterface;

/**
 * Provides route responses for taxonomy.module.
 */
class GradeScaleController extends ControllerBase {

  /**
//   * Returns a form to add a new shortcut to a given set.
//   *
//   * @param \Drupal\grade_scale\GradeScaleInterface $shortcut_set
//   *   The shortcut set this shortcut will be added to.
//   *
//   * @return array
//   *   The shortcut add form.
//   */
//  public function addForm(GradeScaleInterface $shortcut_set) {
//    $shortcut = $this->entityManager()->getStorage('grade_scale')->create(array('shortcut_set' => $shortcut_set->id()));
//    return $this->entityFormBuilder()->getForm($shortcut, 'add');
//  }

//  /**
//   * Deletes the selected shortcut.
//   *
//   * @param \Drupal\grade_scale\GradeScaleInterface $shortcut
//   *   The shortcut to delete.
//   *
//   * @return \Symfony\Component\HttpFoundation\RedirectResponse
//   *   A redirect to the previous location or the front page when destination
//   *   is not set.
//   */
//  public function deleteShortcutLinkInline(ShortcutInterface $shortcut) {
//    $label = $shortcut->label();
//
//    try {
//      $shortcut->delete();
//      drupal_set_message($this->t('The shortcut %title has been deleted.', array('%title' => $label)));
//    }
//    catch (\Exception $e) {
//      drupal_set_message($this->t('Unable to delete the shortcut for %title.', array('%title' => $label)), 'error');
//    }
//
//    return $this->redirect('<front>');
//  }

}
