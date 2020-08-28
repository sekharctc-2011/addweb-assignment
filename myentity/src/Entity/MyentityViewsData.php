<?php

namespace Drupal\myentity\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Myentity entities.
 */
class MyentityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
