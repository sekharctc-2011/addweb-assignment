<?php

namespace Drupal\myentity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Myentity entities.
 *
 * @ingroup myentity
 */
interface MyentityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Myentity name.
   *
   * @return string
   *   Name of the Myentity.
   */
  public function getName();

  /**
   * Sets the Myentity name.
   *
   * @param string $name
   *   The Myentity name.
   *
   * @return \Drupal\myentity\Entity\MyentityInterface
   *   The called Myentity entity.
   */
  public function setName($name);

  /**
   * Gets the Myentity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Myentity.
   */
  public function getCreatedTime();

  /**
   * Sets the Myentity creation timestamp.
   *
   * @param int $timestamp
   *   The Myentity creation timestamp.
   *
   * @return \Drupal\myentity\Entity\MyentityInterface
   *   The called Myentity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Myentity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Myentity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\myentity\Entity\MyentityInterface
   *   The called Myentity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Myentity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Myentity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\myentity\Entity\MyentityInterface
   *   The called Myentity entity.
   */
  public function setRevisionUserId($uid);

}
