<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeCategory.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gradebook\GradeCategoryInterface;
use Drupal\user\UserInterface;

/**
 * Defines the grade category entity class.
 *
 * @ContentEntityType(
 *   id = "grade_category",
 *   label = @Translation("Grade category"),
 *   handlers = {
 *     "access" = "Drupal\gradebook\GradeCategoryAccessControlHandler",
 *     "list_builder" = "Drupal\gradebook\GradeCategoryListBuilder",
 *     "form" = {
 *       "default" = "Drupal\gradebook\GradeCategoryForm",
 *       "add" = "Drupal\gradebook\GradeCategoryForm",
 *       "edit" = "Drupal\gradebook\GradeCategoryForm",
 *       "delete" = "Drupal\gradebook\Form\GradeCategoryDeleteForm"
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "grade_category",
 *   data_table = "grade_category_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/gradebook/grade_category/{grade_category}",
 *     "delete-form" = "/admin/config/gradebook/grade_category/{grade_category}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_category/{grade_category}",
 *     "collection" = "/admin/config/gradebook/grade_category",
 *   },
 *   list_cache_tags = { "config:grade_category_list" }
 * )
 */
class GradeCategory extends ContentEntityBase implements GradeCategoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($link_title) {
    $this->set('title', $link_title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDisplayName() {
    return $this->get('display_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDisplayName($display_name) {
    $this->set('display_name', $display_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDropLowest() {
    return $this->get('drop_lowest')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDropLowest($drop_lowest) {
    $this->set('drop_lowest', $drop_lowest);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getExcludeEmpty() {
    return $this->get('exclude_empty')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setExcludeEmpty($exclude_empty) {
    $this->set('exclude_empty', $exclude_empty);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeAggregationType() {
    return $this->get('grade_aggregation_type')->getEntity()->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeAggregationTypeId() {
    return $this->get('grade_aggregation_type')->getEntity()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setGradeAggregationType($grade_aggregation_type) {
    $this->set('grade_aggregation_type', $grade_aggregation_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->getEntityKey('uid');
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the grade category.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the grade category.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -20,
        'settings' => array(
          'size' => 40,
        ),
      ));

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDescription(t('A description of the grade category.'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'text_textfield',
        'weight' => -19,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['display_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display name'))
      ->setDescription(t('The label when displaying category totals.'))
      ->setSetting('max_length', 20)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -12,
        'settings' => array(
          'size' => 60,
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['parent'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Category parent'))
      ->setDescription(t('The parent of this category.'))
      ->setSetting('target_type', 'grade_category')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -15,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -15,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['grade_aggregation_type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Aggregation type'))
      ->setDescription(t('How to aggregate the scores for this category.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler_settings', ['target_bundles' => ['grade_aggregation_type' => 'grade_aggregation_type']])
      ->setDefaultValue(0)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -15,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -15,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['exclude_empty'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Exclude empty grades when aggregating scores.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => -18,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['drop_lowest'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Drop lowest number of grades entered when aggregating scores.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -12,
        'settings' => array(
          'size' => 10,
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['override_category'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Allow category overrides when aggregating scores.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => -18,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['grade_item_data'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Grade item data'))
      ->setDescription(t('Extra data required for aggregation of scores.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'grade_item_data')
      ->setDefaultValue(0)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -15,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -15,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The username of the content author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language'))
      ->setDescription(t('The language code of the grade item.'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'type' => 'hidden',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => -4,
      ));

    return $fields;
  }
}
