<?php

namespace Drupal\myentity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\node\Entity\Node;

use Drupal\Core\Database\Database;

/**
 * Class Eventsmanager.
 */
class Eventsmanager extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'myentity.eventsmanager',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eventsmanager';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('myentity.eventsmanager');

    $db = Database::getConnection();

    $record = array();

    if ( isset($_GET['num']) ) {
      $record = $db->select('myentity_field_data', 'm')
          ->condition('id', $_GET['num'])
          ->fields('m')
          ->execute()
          ->fetchAssoc();
    }
    
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('Events Title'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required'=> TRUE,
      '#default_value'=> (isset($record['event_title']) && isset($_GET['num'])) ? $record['event_title']:'',
    ];
    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date'),
      '#description' => $this->t('Start date of the events'),
      '#required'=> TRUE,
      '#default_value' => (isset($record['start_date']) && isset($_GET['num'])) ? $record['start_date']:'',
    ];
    $form['end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('End Date'),
      '#description' => $this->t('End date of the events'),
      '#required'=> TRUE,
      '#default_value' => (isset($record['end_date']) && isset($_GET['num'])) ? $record['end_date']:'',
    ];
    $form['venue'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Venue'),
      '#description' => $this->t('Venue'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required'=> TRUE,
      '#default_value' => (isset($record['venue']) && isset($_GET['num'])) ? $record['venue']:'',
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Description'),
      '#maxlength' => 500,
      '#size' => 150,
      '#required'=> TRUE,
      '#default_value' => (isset($record['description__value']) && isset($_GET['num'])) ? $record['description__value']:'',
    ];

    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */

  public function validate_title($title){
    $valid_title = trim($title);
    if($valid_title != "" && strlen($title) <= 50 ){
      return true;
    }else{
      return false;
    }
  }

    /**
   * {@inheritdoc}
   */

  public function validate_venue($venue){
    $valid_venue = trim($venue);
    if($valid_venue != "" && strlen($valid_venue) <= 25 ){
      return true;
    }else{
      return false;
    }
  }

  /**
   * {@inheritdoc}
   */

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $title = $form_state->getValue('title');
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');
    $venue = $form_state->getValue('venue');
    $description = $form_state->getValue('description');

    if(!Eventsmanager::validate_title($title)){

      $form_state->setErrorByName('title', $this->t('Title can not be blank and not more than 50 character'));

    }

     if(!Eventsmanager::validate_venue($venue)){

      $form_state->setErrorByName('venue', $this->t('venue can not be blank and not more than 25 character'));

    }


//    if(preg_match('/[^A-Za-z]/', $title)) {
//      $form_state->setErrorByName('title', $this->t('Title must in characters without space'));
//    }

    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    /*
        $this->config('myassignment.eventsmanager')
          ->set('title', $form_state->getValue('title'))
          ->set('start_date', $form_state->getValue('start_date'))
          ->save();
    */
    $title = $form_state->getValue('title');
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');
    $venue = trim($form_state->getValue('venue'));
    $description = trim($form_state->getValue('description'));

    //check & update
    if (isset($_GET['num'])) {
        
        $field = array(
              'event_title'   => $title,
              'start_date' =>  $start_date,
              'end_date' =>  $end_date,
              'venue' => $venue,
              'description__value' => $description,
        );

        $db = Database::getConnection();

        $db->update('myentity_field_data')
          ->fields($field)
          ->condition('id', $_GET['num'])
          ->execute();

         \Drupal::messenger()->addmessage(t('Record Successfully updated'));

      }
      else{

        // EntityTypeManager
        $node = \Drupal::entityTypeManager()->getStorage('myentity')
        ->create(
          array(
            'type' => 'my_evnt_entity',
            'title'=>$title,
            'event_title' => $title,
            'start_date'=> $start_date,
            'end_date'=>$end_date,
            'venue'=>$venue,
            'description'=>$description,

          )
        );

      $node->save();

      \Drupal::messenger()->addmessage(t('New entity created.'));

      }
    

  }

}
