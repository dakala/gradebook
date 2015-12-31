<?php

/**
 * @file
 * Contains \Drupal\grade_scale\Entity\GradeScale.
 */

namespace Drupal\grade_scale\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\grade_scale\GradeScaleInterface;
use Drupal\user\UserInterface;

/**
 * Defines the grade scale entity class.
 *
 * @ContentEntityType(
 *   id = "grade_scale",
 *   label = @Translation("Grade scale"),
 *   handlers = {
 *     "access" = "Drupal\grade_scale\GradeScaleAccessControlHandler",
 *     "list_builder" = "Drupal\grade_scale\GradeScaleListBuilder",
 *     "form" = {
 *       "default" = "Drupal\grade_scale\GradeScaleForm",
 *       "add" = "Drupal\grade_scale\GradeScaleForm",
 *       "edit" = "Drupal\grade_scale\GradeScaleForm",
 *       "delete" = "Drupal\grade_scale\Form\GradeScaleDeleteForm"
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "grade_scale",
 *   data_table = "grade_scale_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/gradebook/grade_scale/{grade_scale}",
 *     "delete-form" = "/admin/config/gradebook/grade_scale/{grade_scale}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_scale/{grade_scale}",
 *     "collection" = "/admin/config/gradebook/grade_scale",
 *   },
 *   list_cache_tags = { "config:grade_scale_list" }
 * )
 */
class GradeScale extends ContentEntityBase implements GradeScaleInterface {

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
  public function getScales() {
    $scales = [];
    foreach ($this->get('scales') as $scale) {
      if ($scale->value) {
        $scales[] = $scale->value;
      }
    }
    return $scales;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the grade scale.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the grade scale.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -10,
        'settings' => array(
          'size' => 40,
        ),
      ));

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDescription(t('A description of the grade scale.'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'text_textfield',
        'weight' => -9,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['scales'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Scales'))
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDescription(t('The scale items.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -8,
        'settings' => array(
          'size' => 60,
        ),
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
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language'))
      ->setDescription(t('The language code of the grade scale.'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'type' => 'hidden',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 10,
      ));

    return $fields;
  }


}
