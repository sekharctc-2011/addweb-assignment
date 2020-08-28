<?php

namespace Drupal\myentity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_crud\CustomArticleServices;
use Drupal\Core\Session\AccountProxyInterface;


/**
 * Class DisplayTableContrller.
 *
 * @package Drupal\custom_crud\Controller
 */
class DisplayTableController extends ControllerBase
{

	private $Customarticleservice;
	protected $currentUser;
	/**
	 * {@inheritDoc}
	 */

	public function __construct(CustomArticleServices $customarticleservice, AccountProxyInterface $current_user)
	{
		$this->Customarticleservice = $customarticleservice;
		$this->currentUser = $current_user;
	}

	/**
	 * {@inheritdoc}
	 *Here we need to inject the service custom/core
	 */

	public static function create(ContainerInterface $container)
	{
		return new static(
			$container->get('custom_crud.getall_articles'),
			$container->get('current_user')
		);
	}

	public function getContent()
	{
		// First we'll tell the user what's going on. This content can be found
		// in the twig template file: templates/description.html.twig.
		// @todo: Set up links to create nodes and point to devel module.

		$build = [
			'description' => [
				'#theme' => 'custom_crud_description',
				'#description' => 'Tablular data',
				'#attributes' => [],
			],
		];

		return $build;
	}

	/**
	 * @return string
	 * Return table data
	 */

	public function display()
	{
		/*Call services*/
		//dump(\Drupal::service('custom_crud.getall_articles')->GetAllArticles()); die();
		//dump($this->Customarticleservice->GetAllArticles()); die();

		//kint($this->currentUser()); die();

		$query = \Drupal::entityQuery('myentity')
		  ->condition('type', 'my_evnt_entity');
		$result = $query->execute();

		$storage = \Drupal::entityTypeManager()->getStorage('myentity');
		//$ids = \Drupal::entityQuery('myentity')->execute();
		$apiList = $storage->loadMultiple($result);

		dump($apiList);

		foreach ($apiList as $node) {
		  
		 
		  $title = $node['#values']['event_title']['x-default'];
		  
		}  

		//print_r($result);
		print_r($title);
		die();




		/*Create table header*/
		$header_table = array(
			'id' =>    t('SrNo'),
			'name' => t('Name'),
			'mobile' => t('MobileNumber'),
			//'email'=>t('Email'),
			'age' => t('Age'),
			'gender' => t('Gender'),
			//'website' => t('Web site'),
			'opt' => t('operations'),
			'opt1' => t('operations'),
		);

		/*Select records from datababase tables*/
		$db = \Drupal::database();
		$results = $db->select('custom_crud', 'm')
			->fields('m', ['id', 'name', 'mobile', 'email', 'age', 'gender', 'website'])
			->orderby('id', 'DESC')
			->execute()
			->fetchAll();

		/*print data in the table*/
		$rows = array();

		foreach ($results as $data) {
			$delete = Url::FromUserInput('/myentity/form/delete/' . $data->id);
			$edit = Url::FromUserInput('/myentity/form/mydata?num=' . $data->id);

			$rows[] = array(
				'id' =>    $data->id,
				'name' => $data->name,
				'mobile' => $data->mobile,
				'age' => $data->age,
				'gender' => $data->gender,

				'del' => \Drupal::l('Delete', $delete),
				'edit' => \Drupal::l('Edit', $edit),
			);
		}

		/*Dispaly Data in Table*/
		$form['table'] = [
			'#type' => 'table',
			'#header' => $header_table,
			'#rows' => $rows,
			'#empty' => 'No record found!',
		];

		return $form;


		//kint(get_class_methods($this->currentUser)); die(); //we can use dump

		// if($this->currentUser->hasPermission('can use custom crud form')){
		// 	return $form;
		// }

		// return array(
		// 	'#theme' => 'data_table',
		// 	'#items' => $rows,
		// 	'#title' => 'User data display table',
		// );
	}
}
