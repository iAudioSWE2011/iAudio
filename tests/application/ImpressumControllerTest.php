<?php
require_once '../TestHelper.php';
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ImpressumControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public $application;	// Zend_Application f�r Bootstrap
	
	public function setUp() {
		$this->bootstrap = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		
		parent::setUp();
		$this->dispatch('/Impressum');
	}
 
	public function testController()
	{
		$this->dispatch('/Impressum');
		$this->assertController('Impressum');
	}

}
?>