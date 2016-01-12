<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeItemData.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gradebook\GradeItemDataInterface;
use Drupal\user\UserInterface;

/**
 * Defines the grade item entity class.
 *
 * @ContentEntityType(
 *   id = "grade_item_data",
 *   label = @Translation("Grade item data"),
 *   handlers = {
 *     "access" = "Drupal\gradebook\GradeItemDataAccessControlHandler",
 *     "list_builder" = "Drupal\gradebook\GradeItemDataListBuilder",
 *     "form" = {
 *       "default" = "Drupal\gradebook\GradeItemDataForm",
 *       "add" = "Drupal\gradebook\GradeItemDataForm",
 *       "edit" = "Drupal\gradebook\GradeItemDataForm",
 *       "delete" = "Drupal\gradebook\Form\GradeItemDataDeleteForm"
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "grade_item_data",
 *   data_table = "grade_item_data_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/gradebook/grade_item_data/{grade_item_data}",
 *     "delete-form" = "/admin/config/gradebook/grade_item_data/{grade_item_data}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_item_data/{grade_item_data}",
 *     "collection" = "/admin/config/gradebook/grade_item_data",
 *   },
 *   list_cache_tags = { "config:grade_item_data_list" }
 * )
 */
class GradeItemData extends ContentEntityBase implements GradeItemDataInterface {

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
  public function getHighest() {
    return $this->get('highest')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHighest($highest) {
    $this->set('highest', $highest);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecimalPoints() {
    return $this->get('decimal_points')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDecimalPoints($decimal_points) {
    $this->set('decimal_points', $decimal_points);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPass() {
    return $this->get('pass')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPass($pass) {
    $this->set('pass', $pass);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHidden() {
    return $this->get('hidden')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHidden($hidden) {
    $this->set('hidden', $hidden);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocked() {
    return $this->get('locked')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLocked($locked) {
    $this->set('locked', $locked);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->get('weight')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWeight($weight) {
    $this->set('weight', $weight);
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

    $fields['lowest'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Lowest mark'))
      ->setDescription(t('The lowest mark (%age) possible for this item.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -18,
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
        'weight' => -17,
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
        'weight' => -16,
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
        'weight' => -15,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['hidden'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Hidden until'))
      ->setDescription(t('If set, item is hidden until this date.'))
      ->setDefaultValue([])
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => -14,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'datetime_timestamp',
        'weight' => -14,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['locked'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Locked after'))
      ->setDescription(t('If set, item is locked after this date.'))
      ->setDefaultValue([])
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => -13,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'datetime_timestamp',
        'weight' => -13,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['weight'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Weight'))
      ->setDescription(t('Weight override.'))
      ->setDefaultValue(0)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'integer',
        'weight' => -12,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -12,
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
