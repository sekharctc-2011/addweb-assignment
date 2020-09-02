<?php

namespace Drupal\Tests\myentity\Unit;

use Drupal\Tests\UnitTestCase;
//use PHPUnit\Framework\TestCase;
use Drupal\myentity\Form\Eventsmanager;

use Drupal\Core\Url;



class testEntityfunctionality extends UnitTestCase {

	protected $unit;

	public function setUp()
	{
		$this->unit = new Eventsmanager();
	}
		

	public function testTitleValidation()
	{
		//$titleTest = new Eventsmanager();
		$titleVals = $this->unit->validate_title('abcd'); //positive Test case
		$this->assertEquals($titleVals,true);
		$titleVals2 = $this->unit->validate_title(''); //Negetive test case
		$this->assertEquals($titleVals2,false);
		$titleVals3 = $this->unit->validate_title('Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem Ipsumsum'); //Negetive test case check length 25 character
		$this->assertEquals($titleVals3,false);
	}


	public function testvenueValidation()
	{
		//$titleTest = new Eventsmanager();
		$titleVals = $this->unit->validate_venue('abcd'); //positive Test case
		$this->assertEquals($titleVals,true);
		$titleVals2 = $this->unit->validate_venue(''); //Negetive test case
		$this->assertEquals($titleVals2,false);
		$titleVals3 = $this->unit->validate_venue('Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem Ipsumsum IpsumLorem IpsumLorem IpsumL'); //Negetive test case
		$this->assertEquals($titleVals3,false);
	}

	
	public function tearDown()
	{
		unset($this->unit);
	}
}

?>