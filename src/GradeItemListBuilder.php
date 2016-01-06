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
 * Defines a class to build a listing of grade item entities.
 *
 * @see \Drupal\gradebook\Entity\GradeItem
 */
class GradeItemListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = t('Name');
    $header['source'] = t('Source');
    $header['grade_valuation_type'] = t('Valuation type');
    $header['grade_display_type'] = t('Display type');
    $header['multiplicator'] = t('Multiplicator');
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

    $row['source']['data'] = $entity->getSource() ? t('online') : t('offline');
    $row['grade_valuation_type']['data'] = $entity->getGradeValuationType();
    $row['grade_display_type']['data'] = $entity->getGradeDisplayType();
    $row['multiplicator']['data'] = $entity->getMultiplicator();

    return $row + parent::buildRow($entity);
  }

}
