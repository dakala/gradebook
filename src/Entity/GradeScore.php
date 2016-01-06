<?php

/**
 * @file
 * Contains \Drupal\gradebook\Entity\GradeScore.
 */

namespace Drupal\gradebook\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gradebook\GradeScoreInterface;
use Drupal\user\UserInterface;

/**
 * Defines the grade score entity class.
 *
 * @ContentEntityType(
 *   id = "grade_score",
 *   label = @Translation("Grade score"),
 *   handlers = {
 *     "access" = "Drupal\gradebook\GradeScoreAccessControlHandler",
 *     "list_builder" = "Drupal\gradebook\GradeScoreListBuilder",
 *     "form" = {
 *       "default" = "Drupal\gradebook\GradeScoreForm",
 *       "add" = "Drupal\gradebook\GradeScoreForm",
 *       "edit" = "Drupal\gradebook\GradeScoreForm",
 *       "delete" = "Drupal\gradebook\Form\GradeScoreDeleteForm"
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "grade_score",
 *   data_table = "grade_score_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/gradebook/grade_score/{grade_score}",
 *     "delete-form" = "/admin/config/gradebook/grade_score/{grade_score}/delete",
 *     "edit-form" = "/admin/config/gradebook/grade_score/{grade_score}",
 *     "collection" = "/admin/config/gradebook/grade_score",
 *   },
 *   list_cache_tags = { "config:grade_score_list" }
 * )
 */
class GradeScore extends ContentEntityBase implements GradeScoreInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
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

    $fields['feedback'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Feedback'))
      ->setDescription(t('Feedback on the score.'))
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

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Student ID'))
      ->setDescription(t('The ID of the student who owns the score.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
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

    $fields['grade_item'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Grade item'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'grade_item')
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

    $fields['score'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Score'))
      ->setDescription(t('Awarded score for this activity.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -12,
        'settings' => array(
          'size' => 10,
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE);

    $fields['excluded'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Excluded score'))
      ->setDescription(t('If set, item is excluded when aggregating scores.'))
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

    $fields['overridden'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Overriden score'))
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
      ->setDescription(t('Weight of override when aggregating scores.'))
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
      ->setDescription(t('The language code of the grade score.'))
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
