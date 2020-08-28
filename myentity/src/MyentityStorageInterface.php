<?php

namespace Drupal\myentity;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\myentity\Entity\MyentityInterface;

/**
 * Defines the storage handler class for Myentity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Myentity entities.
 *
 * @ingroup myentity
 */
interface MyentityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Myentity revision IDs for a specific Myentity.
   *
   * @param \Drupal\myentity\Entity\MyentityInterface $entity
   *   The Myentity entity.
   *
   * @return int[]
   *   Myentity revision IDs (in ascending order).
   */
  public function revisionIds(MyentityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Myentity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Myentity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\myentity\Entity\MyentityInterface $entity
   *   The Myentity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(MyentityInterface $entity);

  /**
   * Unsets the language for all Myentity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
