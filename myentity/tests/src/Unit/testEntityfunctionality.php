<?php

namespace Drupal\Tests\myentity\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\myentity\Form\Eventsmanager;


use Drupal\Core\Form\FormStateInterface;

class testEntityfunctionality extends UnitTestCase {
	

public function testTitleValidation()
{
	$titleTest = new Eventsmanager(FormStateInterface $form_state);
	$titleVals = $titleTest->validate_title('abcd'); //positive Test case
	$this->assertEquals($titleVals,true);
	$titleVals2 = $titleTest->validate_title(''); //Negetive test case
	$this->assertEquals($titleVals2,false);
	$titleVals3 = $titleTest->validate_title('Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem Ipsumsum'); //Negetive test case check length 25 character
	$this->assertEquals($titleVals3,false);
}


public function testvenueValidation()
{
	$titleTest = new Eventsmanager(FormStateInterface $form_state);
	$titleVals = $titleTest->validate_venue('abcd'); //positive Test case
	$this->assertEquals($titleVals,true);
	$titleVals2 = $titleTest->validate_venue(''); //Negetive test case
	$this->assertEquals($titleVals2,false);
	$titleVals3 = $titleTest->validate_venue('Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem Ipsumsum IpsumLorem IpsumLorem IpsumL'); //Negetive test case
	$this->assertEquals($titleVals3,false);
}

}
?>