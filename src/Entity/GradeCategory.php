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
  public function getLowest() {
    return $this->get('lowest')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLowest($lowest) {
    $this->set('lowest', $lowest);
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

    $fields['display_name'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Display name'))
      ->setDescription(t('The label when displaying category totals.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -12,
        'settings' => array(
          'size' => 60,
        ),
      ));

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

    $fields['aggregate_graded'] = BaseFieldDefinition::create('boolean')
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

    $fields['keep_highest'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Keep highest grades when aggregating scores.'))
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

    $fields['drop_lowest'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Drop lowest grades when aggregating scores.'))
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

    $fields['grade_display_type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Grade display'))
      ->setDescription(t('How to display the scores or marks e.g. Real, Letter or Percentage'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler_settings', ['target_bundles' => ['grade_display_type' => 'grade_display_type']])
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


    $fields['decimal_points'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Decimal points'))
      ->setDescription(t('The number of decimal points to show for this grade mark.'))
      ->setSetting('unsigned', TRUE)
      ->setSetting('allowed_values', range(0, 6))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -9,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['lowest'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Lowest mark'))
      ->setDescription(t('The lowest mark (%age) possible for this category.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -13,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['highest'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Highest mark'))
      ->setDescription(t('The highest mark (%age) possible for this category.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -12,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['pass'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Pass mark'))
      ->setDescription(t('The pass mark (%age) for this category.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -11,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['weight'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Weight'))
      ->setDescription(t('Weight override.'))
      ->setDefaultValue(0)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'integer',
        'weight' => 6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -6,
      ));

    $fields['hidden'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Hidden until'))
      ->setDescription(t('If set, category is hidden until this date.'))
      ->setDefaultValue(0)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'datetime_timestamp',
        'weight' => -8,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['locked'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Locked after'))
      ->setDescription(t('If set, category is locked after this date.'))
      ->setDefaultValue(0)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => -7,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'datetime_timestamp',
        'weight' => -7,
      ))
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }
}
