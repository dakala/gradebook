<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleSetListBuilder.
 */

namespace Drupal\gradebook;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Defines a class to build a listing of grade category entities.
 *
 * @see \Drupal\gradebook\Entity\GradeCategory
 */
class GradeCategoryListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    // @todo: add responsive priority
    $header['name'] = t('Name');
    $header['display_name'] = t('Display name');
    $header['grade_aggregation_type'] = t('Aggregation type');
    $header['exclude_empty'] = t('Exclude empty');
    $header['drop_lowest'] = t('Drop lowest items');
    $header['author'] = t('Author');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    if (isset($operations['edit'])) {
      $operations['edit']['title'] = t('Edit');
    }

    $operations['view'] = array(
      'title' => t('View'),
      'url' => $entity->urlInfo(),
    );

    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $langcode = $entity->language()->getId();
    $uri = $entity->urlInfo();
    $options = $uri->getOptions();
    $options += ($langcode != LanguageInterface::LANGCODE_NOT_SPECIFIED && isset($languages[$langcode]) ? array('language' => $languages[$langcode]) : array());
    $uri->setOptions($options);
    $row['name']['data'] = array(
      '#type' => 'link',
      '#title' => $entity->label(),
      '#url' => $uri,
    );
    $row['display_name'] = $entity->getDisplayName();
    $row['grade_aggregation_type'] = $entity->getGradeAggregationType();
    $row['exclude_empty'] = $entity->getExcludeEmpty() ? t('Yes') : t('No');
    $row['drop_lowest'] = $entity->getDropLowest();
    $row['author']['data'] = array(
      '#theme' => 'username',
      '#account' => $entity->getOwner(),
    );

    return $row + parent::buildRow($entity);
  }

}
