<?php

/**
 * @file
 * Contains \Drupal\gradebook\GradebookManager.
 */

namespace Drupal\gradebook;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager service.
   * @param \Drupal\Core\Database\Connection $connection
   *   The current database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, Connection $connection, TranslationInterface $string_translation) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager; // @todo:
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
    if (!empty($entities)) {
      $entities = array_combine($entities, $entities);
    }
    return $entities;
  }

  /**
   * @inheritdoc
   */
  public function getGradebookEnabledEntities() {
    $entities = \Drupal::config('gradebook.settings')->get('gradebook_entity');
    return is_array($entities) && $entities ? array_filter($entities) : [];
  }

  /**
   * @inheritdoc
   */
  public function isGradebookEnabled($entity) {
    $entities = $this->getGradebookEnabledEntities();
    return count($entities) ? in_array($entity, $entities) : FALSE;
  }

}
