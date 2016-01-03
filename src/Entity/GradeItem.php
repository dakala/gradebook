<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeItem.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gradebook\GradeItemInterface;
use Drupal\user\UserInterface;

/**
 * Defines the grade item entity class.
 *
 * @ContentEntityType(
 *   id = "grade_item",
 *   label = @Translation("Grade item"),
 *   handlers = {
 *     "access" = "Drupal\gradebook\GradeItemAccessControlHandler",
 *     "list_builder" = "Drupal\gradebook\GradeItemListBuilder",
 *     "form" = {
 *       "default" = "Drupal\gradebook\GradeItemForm",
 *       "add" = "Drupal\gradebook\GradeItemForm",
 *       "edit" = "Drupal\gradebook\GradeItemForm",
 *       "delete" = "Drupal\gradebook\Form\GradeItemDeleteForm"
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "grade_item",
 *   data_table = "grade_item_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/gradebook/grade_item/{grade_item}",
 *     "delete-form" = "/admin/config/gradebook/grade_item/{grade_item}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_item/{grade_item}",
 *     "collection" = "/admin/config/gradebook/grade_item",
 *   },
 *   list_cache_tags = { "config:grade_item_list" }
 * )
 */
class GradeItem extends ContentEntityBase implements GradeItemInterface {

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
      ->setDescription(t('The ID of the grade item.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the grade item.'))
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
      ->setDescription(t('A description of the grade item.'))
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

    $fields['source'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Online grade item'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => -18,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['grade_valuation_type'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Grade valuation type'))
      ->setDescription(t('How the item is valuated.'))
      ->setSetting('unsigned', TRUE)
      ->setSetting('allowed_values', gradebook_grade_valuation_options())
      ->setDefaultValue(GRADE_ITEM_VALUATION_NUMERIC)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -17,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -17,
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

    $fields['lowest'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Lowest mark'))
      ->setDescription(t('The lowest mark (%age) possible for this item.'))
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
      ->setDescription(t('The highest mark (%age) possible for this item.'))
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
      ->setDescription(t('The pass mark (%age) for this item.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -11,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['multiplicator'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Multiplication factor'))
      ->setDescription(t('The grade multiplication factor.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -10,
        'settings' => array(
          'size' => 10,
        ),
      ));

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

    $fields['hidden'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Hidden until'))
    ->setDescription(t('If set, item is hidden until this date.'))
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
    ->setDescription(t('If set, item is locked after this date.'))
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
