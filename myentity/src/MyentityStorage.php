<?php

namespace Drupal\myentity;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class MyentityStorage extends SqlContentEntityStorage implements MyentityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(MyentityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {myentity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {myentity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(MyentityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {myentity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('myentity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
