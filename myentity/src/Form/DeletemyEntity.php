<?php

namespace Drupal\myentity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

/**
 * Class DeleteForm.
 *
 * @package Drupal\mydata\Form
 */
class DeletemyEntity extends ConfirmFormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_entity';
  }

  public $cid;
  public function getQuestion() {
    return t('Do you want to delete %cid?', array('%cid' => $this->cid));
  }
 public function getCancelUrl() {
    return new Url('myentity.eventsmanager');
}
public function getDescription() {
    return t('Only do this if you are sure!');
  }
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {

     $this->id = $cid;
    return parent::buildForm($form, $form_state);
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = \Drupal::entityTypeManager()->getStorage('myentity')->load($this->id);
	  $entity->delete();

	  \Drupal::messenger()->addmessage(t('entity deleted.'));

	  $form_state->setRedirect('myentity.eventsmanager');
  }
}
