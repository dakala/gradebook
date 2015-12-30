<?php

/**
 * @file
 * Contains \Drupal\gradebook\Form\GradeLetterSetDeleteForm.
 */

namespace Drupal\gradebook\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gradebook\GradeLetterSetStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Builds the grade letter set deletion form.
 */
class GradeLetterSetDeleteForm extends EntityDeleteForm {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The shortcut storage.
   *
   * @var \Drupal\gradebook\GradeLetterSetStorageInterface
   */
  protected $storage;

  /**
   * Constructs a GradeLetterSetDeleteForm object.
   */
  public function __construct(Connection $database, GradeLetterSetStorageInterface $storage) {
    $this->database = $database;
    $this->storage = $storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity.manager')->getStorage('grade_letter_set')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Find out how many users are directly assigned to this grade letter set, and
    // make a message.
    $number = $this->storage->countAssignedUsers($this->entity);
    $info = '';
    if ($number) {
      $info .= '<p>' . $this->formatPlural($number,
        '1 user has chosen or been assigned to this grade letter set.',
        '@count users have chosen or been assigned to this grade letter set.') . '</p>';
    }

    // Also, if a module implements hook_shortcut_default_set(), it's possible
    // that this set is being used as a default set. Add a message about that too.
    if ($this->moduleHandler->getImplementations('shortcut_default_set')) {
      $info .= '<p>' . t('If you have chosen this grade letter set as the default for some or all users, they may also be affected by deleting it.') . '</p>';
    }

    $form['info'] = array(
      '#markup' => $info,
    );

    return parent::buildForm($form, $form_state);
   }

}
