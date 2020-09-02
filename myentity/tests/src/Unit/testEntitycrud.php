<?php

namespace Drupal\Tests\myentity\Entitycrud;


use Drupal\Tests\BrowserTestBase;
//use PHPUnit\Framework\TestCase;
use Drupal\myentity\Form\Eventsmanager;

use Drupal\Core\Url;

/**
 * provide some basic test for entity create
 */
class testEntitycrud extends BrowserTestBase
{
	
	/**
   * Our module dependencies.
   *
   * @var string[]
   */
  public static $modules = ['myentity'];

	/**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }



	public function testMyentityCreate()
	{

		// Create the user with the appropriate permission.
	    $admin_user = $this->drupalCreateUser([
	      'access administration pages',
	    ]);
	    
	    // Start the session.
	    $assert = $this->assertSession();

	    // Login as our account.
	    $this->drupalLogin($admin_user);


		$settings_form_path = Url::fromRoute('myentity.eventsmanager');
		$this->drupalGet($settings_form_path);
		$this->assertResponse(200);

	    	// Post the form.
	    	$edit = array(
	            
	            'title'=>'All India meeting All India meeting All India',
	            'start_date'=> strtotime('2020-08-09'),
	            'end_date'=>strtotime('2020-08-12'),
	            'venue'=>'Market complex',
	            'description'=>'Lorem Ipsum Lorem Ipsum',

	          );

	    $this->drupalPostForm($settings_form_path, $edit, 'Save configuration');
	    $assert->statusCodeEquals(200);

	    $assert->pageTextContains('New entity created');

	    //check the default field value blankss
	    // Reload the page.
	    // $this->drupalGet($settings_form_path);
	    // $txt_venue = $assert->fieldExists('venue')->getValue();	    
	    // $this->assertTrue($txt_venue != 'Market complex');


	}

	public function testMyentityUpdate()
	{
		// Create the user with the appropriate permission.
	    $admin_user = $this->drupalCreateUser([
	      'access administration pages',
	    ]);
	    
	    // Start the session.
	    $assert = $this->assertSession();

	    // Login as our account.
	    $this->drupalLogin($admin_user);

	    $settings_form_path = Url::fromRoute('myentity.eventsmanager');

	    //CHeck with Invalid entity Id		
		$this->drupalGet(Url::fromRoute('myentity.eventsmanager', ['num' => 3] ));
		$this->assertResponse(200); //check path
		$assert->pageTextContains('No record found having id');
		
	}




	public function testMyentityDelete()
	{
		// Create the user with the appropriate permission.
	    $admin_user = $this->drupalCreateUser([
	      'access administration pages',
	    ]);
	    
	    // Start the session.
	    $assert = $this->assertSession();

	    // Login as our account.
	    $this->drupalLogin($admin_user);

	   
	    //CHeck with Invalid entity Id		
		$this->drupalGet(Url::fromRoute('myentity.delete_form', ['cid' => 1] ));
		$this->assertResponse(200); //check path
		$assert->pageTextContains('Only do this if you are sure!');
		
	}
}


?>