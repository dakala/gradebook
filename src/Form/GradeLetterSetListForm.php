<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeLetterSetListForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;

/**
 * Builds the grade letter set list form.
 */
class GradeLetterSetListForm extends EntityForm {

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
    $form['grade_letter_sets'] = [
      '#tree' => TRUE,
      '#weight' => -20,
    ];

    $form['grade_letter_sets']['letters'] = [
      '#type' => 'table',
      '#header' => [
        t('Name'),
        t('Description'),
        t('Lowest'),
        t('Highest'),
        t('Weight'),
        t('Operations'),
      ],
      '#empty' => $this->t('No grade letters available. <a href=":grade-letter">Add a grade letter</a>', [
        ':grade-letter' => Url::fromRoute('grade_letter.letter_add', ['grade_letter_set' => $this->entity->id()])
          ->toString(),
      ]),
      '#attributes' => ['id' => 'grade_letter_sets'],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'grade-letter-weight',
        ],
      ],
    ];

    foreach ($this->entity->getGradeLetters() as $grade_letter) {
      $id = $grade_letter->id();

      $form['grade_letter_sets']['letters'][$id]['#attributes']['class'][] = 'draggable';
      $form['grade_letter_sets']['letters'][$id]['name'] = [
        '#type' => 'markup',
        '#markup' => $grade_letter->getTitle(),
      ];
      $form['grade_letter_sets']['letters'][$id]['description'] = [
        '#type' => 'markup',
        '#markup' => $grade_letter->getDescription(),
      ];
      $form['grade_letter_sets']['letters'][$id]['lowest'] = [
        '#type' => 'markup',
        '#markup' => $grade_letter->getLowest() . '%',
      ];
      $form['grade_letter_sets']['letters'][$id]['highest'] = [
        '#type' => 'markup',
        '#markup' => $grade_letter->getHighest() . '%',
      ];
      unset($form['grade_letter_sets']['letters'][$id]['name']['#access_callback']);
      $form['grade_letter_sets']['letters'][$id]['#weight'] = $grade_letter->getWeight();
      $form['grade_letter_sets']['letters'][$id]['weight'] = [
        '#type' => 'weight',
        '#title' => t('Weight for @title', ['@title' => $grade_letter->getTitle()]),
        '#title_display' => 'invisible',
        '#default_value' => $grade_letter->getWeight(),
        '#attributes' => ['class' => ['grade-letter-weight']],
      ];

      $links['edit'] = [
        'title' => t('Edit'),
        'url' => $grade_letter->toUrl(),
      ];
      $links['delete'] = [
        'title' => t('Delete'),
        'url' => $grade_letter->toUrl('delete-form'),
      ];
      $form['grade_letter_sets']['letters'][$id]['operations'] = [
        '#type' => 'operations',
        '#links' => $links,
        '#access' => $account->hasPermission('administer grade letters'),
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    return [
      'submit' => [
        '#type' => 'submit',
        '#value' => t('Save changes'),
        '#access' => (bool) Element::getVisibleChildren($form['grade_letter_sets']['letters']),
        '#submit' => ['::submitForm', '::save'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    foreach ($this->entity->getGradeLetters() as $grade_letter) {
      $weight = $form_state->getValue([
        'grade_letter_sets',
        'letters',
        $grade_letter->id(),
        'weight',
      ]);
      $grade_letter->setWeight($weight);
      $grade_letter->save();
    }
    drupal_set_message(t('The grade letter set has been updated.'));
  }

}
