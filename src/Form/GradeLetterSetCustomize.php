<?php

/**
 * @file
 * Contains \Drupal\shortcut\Form\GradeLetterSetCustomize.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Builds the grade letter set list form.
 */
class GradeLetterSetCustomize extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\gradebook\GradeLetterSetInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $account = \Drupal::currentUser();

    $form = parent::form($form, $form_state);
    $form['grade_letters'] = array(
      '#tree' => TRUE,
      '#weight' => -20,
    );

    $form['grade_letters']['links'] = array(
      '#type' => 'table',
      '#header' => array(t('Name'), t('Lowest'), t('Highest'), t('Weight'), t('Operations')),
      '#empty' => $this->t('No grade letters available. <a href=":grade-letter">Add a grade letter</a>', array(':grade-letter' => $this->url('grade_letter.letter_add', array('grade_letter_set' => $this->entity->id())))),
      '#attributes' => array('id' => 'grade_letters'),
      '#tabledrag' => array(
        array(
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'grade-letter-weight',
        ),
      ),
    );

    foreach ($this->entity->getGradeLetters() as $shortcut) {
      $id = $shortcut->id();

      $form['grade_letters']['links'][$id]['#attributes']['class'][] = 'draggable';
      $form['grade_letters']['links'][$id]['name'] = array(
        '#type' => 'markup',
        '#markup' => $shortcut->getTitle(),
      );
      $form['grade_letters']['links'][$id]['lowest'] = array(
        '#type' => 'markup',
        '#markup' => $shortcut->getLowest() . '%',
      );
      $form['grade_letters']['links'][$id]['highest'] = array(
        '#type' => 'markup',
        '#markup' => $shortcut->getHighest() . '%',
      );
      unset($form['grade_letters']['links'][$id]['name']['#access_callback']);
      $form['grade_letters']['links'][$id]['#weight'] = $shortcut->getWeight();
      $form['grade_letters']['links'][$id]['weight'] = array(
        '#type' => 'weight',
        '#title' => t('Weight for @title', array('@title' => $shortcut->getTitle())),
        '#title_display' => 'invisible',
        '#default_value' => $shortcut->getWeight(),
        '#attributes' => array('class' => array('grade-letter-weight')),
      );

      $links['edit'] = array(
        'title' => t('Edit'),
        'url' => $shortcut->urlInfo(),
      );
      $links['delete'] = array(
        'title' => t('Delete'),
        'url' => $shortcut->urlInfo('delete-form'),
      );
      $form['grade_letters']['links'][$id]['operations'] = array(
        '#type' => 'operations',
        '#links' => $links,
        '#access' => $account->hasPermission('administer grade letters'),
      );
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    // Only includes a Save action for the entity, no direct Delete button.
    return array(
      'submit' => array(
        '#type' => 'submit',
        '#value' => t('Save changes'),
        '#access' => (bool) Element::getVisibleChildren($form['grade_letters']['links']),
        '#submit' => array('::submitForm', '::save'),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    foreach ($this->entity->getGradeLetters() as $shortcut) {
      $weight = $form_state->getValue(array('grade_letter_sets', 'grade_letters', $shortcut->id(), 'weight'));
      $shortcut->setWeight($weight);
      $shortcut->save();
    }
    drupal_set_message(t('The grade letter set has been updated.'));
  }

}
