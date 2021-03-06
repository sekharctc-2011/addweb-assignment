<?php

namespace Drupal\myentity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class Eventsmanager.
 */
class Eventsmanager extends ConfigFormBase {

  public function __construct() {
      
      
    }

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

    $record = array();

    if ( isset($_GET['num']) ) {
     

      $path = '/myentity/form/myentity_table_view';
      $url = Url::fromUserInput($path);
      
      $entity = \Drupal::entityTypeManager()->getStorage("myentity")->load($_GET['num']);

      if(!is_null($entity)){
        $event_title = $entity->event_title[0]->value;
        $start_date =  date('Y-m-d', $entity->start_date[0]->value);
        $end_date =    date('Y-m-d', $entity->end_date[0]->value);
        $venue =       $entity->venue[0]->value;
        $description = $entity->description[0]->value;
      }else{
        \Drupal::messenger()->addmessage(t('No record found having id- '.$_GET['num']));
        
        return new RedirectResponse(\Drupal::url('myentity.myentity_table_view'));
      }    

    }    

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('Events Title'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required'=> TRUE,
      '#default_value'=> (isset($event_title) && isset($_GET['num'])) ? $event_title:'',
    ];
    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date'),
      '#description' => $this->t('Start date of the events'),
      '#required'=> TRUE,
      '#default_value' => (isset($start_date) && isset($_GET['num'])) ? $start_date:'',
    ];
    $form['end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('End Date'),
      '#description' => $this->t('End date of the events'),
      '#required'=> TRUE,
      '#default_value' => (isset($end_date) && isset($_GET['num'])) ? $end_date:'',
    ];
    $form['venue'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Venue'),
      '#description' => $this->t('Venue'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required'=> TRUE,
      '#default_value' => (isset($venue) && isset($_GET['num'])) ? $venue:'',
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Description'),
      '#maxlength' => 500,
      '#size' => 150,
      '#required'=> TRUE,
      '#default_value' => (isset($description) && isset($_GET['num'])) ? $description:'',
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

    $title = $form_state->getValue('title');
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');
    $venue = trim($form_state->getValue('venue'));
    $description = trim($form_state->getValue('description'));

    //check & update
    if (isset($_GET['num'])) {

      //$path = '/my-entity-list';
      $path = '/myentity/form/myentity_table_view';
      $url = Url::fromUserInput($path);

      $entity = \Drupal::entityTypeManager()->getStorage("myentity")->load($_GET['num']);
      //$data = $entity->start_date->getValue();
      //print_r($data);
      $entity->event_title[0]->value = $title;
      $entity->start_date[0]->value = strtotime($start_date);
      $entity->end_date[0]->value = strtotime($end_date);
      $entity->venue[0]->value = $venue;
      $entity->description[0]->value = $description;
      $entity->save();

      \Drupal::messenger()->addmessage(t('Record Successfully updated'));
      $form_state->setRedirectUrl($url);

      }
      else{

        // EntityTypeManager
        $node = \Drupal::entityTypeManager()->getStorage('myentity')
        ->create(
          array(
            'type' => 'my_evnt_entity',
            'title'=>$title,
            'event_title' => $title,
            'start_date'=> strtotime($start_date),
            'end_date'=>strtotime($end_date),
            'venue'=>$venue,
            'description'=>$description,

          )
        );

      $node->save();

      \Drupal::messenger()->addmessage(t('New entity created.'));

      }


  }

}
