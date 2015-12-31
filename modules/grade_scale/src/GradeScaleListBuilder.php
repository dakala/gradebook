<?php

/**
 * @file
 * Contains \Drupal\grade_scale\GradeScaleSetListBuilder.
 */

namespace Drupal\grade_scale;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Defines a class to build a listing of shortcut set entities.
 *
 * @see \Drupal\grade_scale\Entity\GradeScaleSet
 */
class GradeScaleListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = t('Name');
    $header['scales'] = t('Scales');
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

    $row['scales']['data'] = array(
      '#type' => 'markup',
      '#markup' => implode(', ', $entity->getScales()),
    );

    return $row + parent::buildRow($entity);
  }

}
