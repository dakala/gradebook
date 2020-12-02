<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleSetStorage.
 */

namespace Drupal\grade_scale;

use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a storage for grade scale entities.
 */
class GradeScaleStorage extends ConfigEntityStorage implements GradeScaleStorageInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a GradeScaleStorageController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_info
   *   The entity info for the entity type.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Component\Uuid\UuidInterface $uuid_service
   *   The UUID service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Cache\MemoryCache\MemoryCacheInterface $memory_cache
   *   The memory cache.
   * @param \Drupal\Core\Database\Connection $database
   *   The database.
   */
  public function __construct(
    EntityTypeInterface $entity_info,
    ConfigFactoryInterface $config_factory,
    UuidInterface $uuid_service,
    ModuleHandlerInterface $module_handler,
    LanguageManagerInterface $language_manager,
    MemoryCacheInterface $memory_cache,
    Connection $database
  ) {
    parent::__construct($entity_info, $config_factory, $uuid_service, $language_manager, $memory_cache);

    $this->moduleHandler = $module_handler;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_info) {
    return new static(
      $entity_info,
      $container->get('config.factory'),
      $container->get('uuid'),
      $container->get('module_handler'),
      $container->get('language_manager'),
      $container->get('entity.memory_cache'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAssignedGradeScales(GradeScaleInterface $entity) {
    // First, delete any user assignments for this set, so that each of these
    // users will go back to using whatever default set applies.
    $this->database->delete('grade_scale_users')
      ->condition('set_name', $entity->id())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function assignUser(GradeScaleInterface $grade_scale, $account) {
    $this->database->merge('grade_scale_users')
      ->key('uid', $account->id())
      ->fields(['set_name' => $grade_scale->id()])
      ->execute();
    drupal_static_reset('grade_scale_current_displayed_set');
  }

  /**
   * {@inheritdoc}
   */
  public function unassignUser($account) {
    $deleted = $this->database->delete('grade_scale_users')
      ->condition('uid', $account->id())
      ->execute();

    return (bool) $deleted;
  }

  /**
   * {@inheritdoc}
   */
  public function getAssignedToUser($account) {
    $query = $this->database->select('grade_scale_users', 'ssu');
    $query->fields('ssu', ['set_name']);
    $query->condition('ssu.uid', $account->id());

    return $query->execute()->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function countAssignedUsers(GradeScaleInterface $grade_scale) {
    return $this->database->query('SELECT COUNT(*) FROM {grade_scale_users} WHERE set_name = :name', [':name' => $grade_scale->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSet(AccountInterface $account) {
    // Allow modules to return a default shortcut set name. Since we can only
    // have one, we allow the last module which returns a valid result to take
    // precedence. If no module returns a valid set, fall back on the site-wide
    // default, which is the lowest-numbered shortcut set.
    //$suggestions = array_reverse($this->moduleHandler->invokeAll('shortcut_default_set', array($account)));
    $suggestions[] = 'default';
    $grade_scale = NULL;
    foreach ($suggestions as $name) {
      if ($grade_scale = $this->load($name)) {
        break;
      }
    }

    return $grade_scale;
  }

}
