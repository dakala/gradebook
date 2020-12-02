<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeItem.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gradebook\GradeItemInterface;
use Drupal\user\EntityOwnerTrait;
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
 *   field_ui_base_route = "entity.grade_item.collection",
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

  use EntityOwnerTrait;

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
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSource($source) {
    $this->set('source', $source);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeValuationType() {
    return $this->get('grade_valuation_type')->getEntity()->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeValuationTypeId() {
    return $this->get('grade_valuation_type')->getEntity()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setGradeValuationType($grade_valuation_type) {
    $this->set('grade_valuation_type', $grade_valuation_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeDisplayType() {
    return $this->get('grade_display_type')->getEntity()->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeDisplayTypeId() {
    return $this->get('grade_display_type')->getEntity()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setGradeDisplayType($grade_display_type) {
    $this->set('grade_display_type', $grade_display_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeItemData() {
    return $this->get('grade_item_data')->getEntity()->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getGradeItemDataId() {
    return $this->get('grade_item_data')->getEntity()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setGradeItemData($grade_item_data) {
    $this->set('grade_item_data', $grade_item_data);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiplicator() {
    return $this->get('multiplicator')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMultiplicator($multiplicator) {
    $this->set('multiplicator', $multiplicator);
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
        'weight' => -19,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'text_textfield',
        'weight' => -19,
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
        'weight' => -19,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'inline_entity_form_complex',
        'weight' => -19,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'allow_new' => TRUE,
          'allow_existing' => TRUE,
        ),
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

    $fields['grade_valuation_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Grade valuation type'))
      ->setDescription(t('How the item is valuated.'))
      ->setSetting('max_length', 20)
      ->setSetting('allowed_values', \Drupal::service('gradebook.manager')->getGradebookGradeValuationOptions())
      ->setDefaultValue(GRADE_ITEM_VALUATION_NUMERIC)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -17,
      ))
      ->setDisplayConfigurable('view', TRUE)
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
        'weight' => -16,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -16,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['multiplicator'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Multiplication factor'))
      ->setDescription(t('Multiply all grades by this factor.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -15,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -15,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['plusfactor'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Plus factor'))
      ->setDescription(t('Add this factor to all grades.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -14,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -14,
        'settings' => array(
          'size' => 10,
        ),
      ));

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The username of the content author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 0,
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
        'weight' => 0,
      ));

    return $fields;
  }
}
