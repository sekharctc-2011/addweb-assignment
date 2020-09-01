<?php

namespace Drupal\myentity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class MyentityTableView.
 */
class MyentityTableView extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'myentity_table_view';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //Set Table Header
    $header_table = array(
      'event_title' => t('Event Title'),
      'start_date' => t('Start Date'),
      'end_date' => t('End Date'),
      'venue' => t('Venue'),
      'description' => t('Description'),
      'del' => t('operations'),
      'edit' => t('operations'),
    );

    $nids = \Drupal::entityQuery('myentity')
      ->condition('type', 'my_evnt_entity')
      ->sort('created', 'DESC')
      ->execute();

    $rows = array();

    foreach ($nids as $nid) {
      $entity = \Drupal::entityTypeManager()->getStorage("myentity")->load($nid);

      $delete = Url::FromUserInput('/myentity/form/delete/' . $nid);
      $edit = Url::FromUserInput('/admin/config/myentity/eventsmanager?num=' . $nid);


      $rows[$nid] = array(
          'event_title' => $entity->event_title[0]->value,
          'start_date' =>  date('Y-m-d', $entity->start_date[0]->value),
          'end_date' =>    date('Y-m-d', $entity->end_date[0]->value),
          'venue' =>       $entity->venue[0]->value,
          'description' => $entity->description[0]->value,

          'del' => \Drupal::l('Delete', $delete),
          'edit' => \Drupal::l('Edit', $edit),
      );

    }



    $form['myentity_table_list_view'] = [
      '#type' => 'tableselect',
      '#title' => $this->t('MyEntity Table List View'),
      '#weight' => '0',
      '#header' => $header_table,
      '#options' => $rows,
      '#empty' => 'No record found!',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete selected rows'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    // foreach ($form_state->getValues() as $key => $value) {
    //   \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
    // }

    $table_array = $form_state->getValue('myentity_table_list_view');

    $new_arr = array_filter(array_values($table_array));

    //1st methord fordeleting
    
    // $controller = \Drupal::entityTypeManager()->getStorage('myentity');
    // $entities = $controller->loadMultiple($new_arr);
    // $message = $controller->delete($entities);
    
    foreach($new_arr as $nid)
      {
        $entity = \Drupal::entityTypeManager()->getStorage("myentity")->load($nid);
        $entity->delete();

        \Drupal::messenger()->addmessage(t('Entiry deleted with id:- '.$nid));
      }

      if (count($new_arr) < 1) {
        \Drupal::messenger()->addmessage(t('No record selected'));
      }




  }

}
