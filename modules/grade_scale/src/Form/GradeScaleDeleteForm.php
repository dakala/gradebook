<?php

/**
 * @file
 * Contains \Drupal\grade_scale\Form\GradeScaleSetDeleteForm.
 */

namespace Drupal\grade_scale\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\grade_scale\GradeScaleStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Builds the grade scale set deletion form.
 */
class GradeScaleDeleteForm extends EntityDeleteForm {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The shortcut storage.
   *
   * @var \Drupal\grade_scale\GradeScaleStorageInterface
   */
  protected $storage;

  /**
   * Constructs a GradeScaleDeleteForm object.
   */
  public function __construct(Connection $database, GradeScaleStorageInterface $storage) {
    $this->database = $database;
    $this->storage = $storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity.manager')->getStorage('grade_scale')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return parent::buildForm($form, $form_state);
  }

}
