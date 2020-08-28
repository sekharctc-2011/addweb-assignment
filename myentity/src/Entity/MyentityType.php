<?php

namespace Drupal\myentity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Myentity type entity.
 *
 * @ConfigEntityType(
 *   id = "myentity_type",
 *   label = @Translation("Myentity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\myentity\MyentityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\myentity\Form\MyentityTypeForm",
 *       "edit" = "Drupal\myentity\Form\MyentityTypeForm",
 *       "delete" = "Drupal\myentity\Form\MyentityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\myentity\MyentityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "myentity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "myentity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/myentity_type/{myentity_type}",
 *     "add-form" = "/admin/structure/myentity_type/add",
 *     "edit-form" = "/admin/structure/myentity_type/{myentity_type}/edit",
 *     "delete-form" = "/admin/structure/myentity_type/{myentity_type}/delete",
 *     "collection" = "/admin/structure/myentity_type"
 *   }
 * )
 */
class MyentityType extends ConfigEntityBundleBase implements MyentityTypeInterface {

  /**
   * The Myentity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Myentity type label.
   *
   * @var string
   */
  protected $label;

}
