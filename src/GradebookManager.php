<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradebookManager.
 */

namespace Drupal\gradebook;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides group manager service.
 */
class GradebookManager implements GradebookManagerInterface {
  use StringTranslationTrait;

  /**
   * Group settings config object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Entity manager service
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Database connection
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs the group manager service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Database\Connection $connection
   *   The current database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityManagerInterface $entity_manager, Connection $connection, TranslationInterface $string_translation) {
    $this->configFactory = $config_factory;
    $this->entityManager = $entity_manager;
    $this->connection = $connection;
    $this->stringTranslation = $string_translation;
  }

  /**
   * @inheritdoc
   */
  public function getGradebookGradeValuationOptions() {
    $options = [];
    $valuations = \Drupal::moduleHandler()->invokeAll('grade_valuation_info');
    foreach ($valuations as $key => $valuation) {
      $options[$key] = $valuation['label'];
    }
    asort($options);
    return $options;
  }

  /**
   * @inheritdoc
   */
  public function getGradebookActivityOptions() {
    $entities = $this->getGradebookEnabledEntities();
    if ($entities) {
      $entities = array_combine($entities, $entities);
    }
    return $entities;
  }

  /**
   * @inheritdoc
   */
  public function getGradebookEnabledEntities() {
    $entities = \Drupal::config('gradebook.settings')->get('gradebook_entity');
//  kint(\Drupal::config('gradebook.settings')->get());
    array_filter($entities);
//  kint($entities);
    return $entities;
  }

  /**
   * @inheritdoc
   */
  public function isGradebookEnabled($entity) {
    $entities = $this->getGradebookEnabledEntities();
    return count($entities) ? in_array($entity, $entities) : FALSE;
  }

}
