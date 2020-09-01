<?php

namespace Drupal\myentity\batch_delete;



/**
 * DeleteMyEntityBatch class defination
 */
class DeleteMyEntityBatch 
{
	
	function __construct()
	{
		# code...
	}

	public static function DeleteEntity($nids, &$context)
	{
		$message = "Delete myentity....";
		$results = array();

	

		foreach ($nids as $nid) {
			$entity = \Drupal::entityTypeManager()->getStorage("myentity")->load($nid);
			$results[] = $entity->delete();
		}

		$context['message'] = $message;
		$context['results'] = $results;


	}

	public function DeleteEntityFinishCallback($success, $results, $operations)
	{
		if ($success) {
      		$message = \Drupal::translation()->formatPlural(
        	count($results),
        	'One post processed.', '@count posts processed.'
      		);
    	}
    else {
      $message = t('Finished with an error.');
    }
    	drupal_set_message($message);

	}

}