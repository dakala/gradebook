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
 * Defines a class to build a listing of grade item data entities.
 *
 * @see \Drupal\gradebook\Entity\GradeItemData
 */
class GradeItemDataListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = t('Name');
    $header['lowest'] = t('Lowest');
    $header['highest'] = t('Highest');
    $header['pass'] = t('Pass');
    $header['hidden'] = t('Hidden');
    $header['locked'] = t('Locked');
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

    $row['lowest']['data'] = $entity->getLowest();
    $row['highest']['data'] = $entity->getHighest();
    $row['pass']['data'] = $entity->getPass();
    $row['hidden']['data'] = $entity->getHidden();
    $row['locked']['data'] = $entity->getLocked();

    return $row + parent::buildRow($entity);
  }

}
