<?php

namespace Drupal\myentity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MyentityTypeForm.
 */
class MyentityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $myentity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $myentity_type->label(),
      '#description' => $this->t("Label for the Myentity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $myentity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\myentity\Entity\MyentityType::load',
      ],
      '#disabled' => !$myentity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $myentity_type = $this->entity;
    $status = $myentity_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Myentity type.', [
          '%label' => $myentity_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Myentity type.', [
          '%label' => $myentity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($myentity_type->toUrl('collection'));
  }

}
