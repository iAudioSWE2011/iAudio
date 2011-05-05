<?php
require_once '../TestHelper.php';
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class SettingsControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public $application;	// Zend_Application f�r Bootstrap
	public $mail = "test@iAudio.com";
	
	public function setUp() {
		$this->bootstrap = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		
		parent::setUp();
		
		session_start();
		$user = new User();
		$session = new Session();
		$session->saveSession(session_id(), $user->getIDByMail($this->mail));
		
		$this->dispatch('/Settings');
	}
 
	public function testController()
	{
	
		$this->dispatch('/Settings');
		$this->assertController('Settings');
	}
	
	public function tearDown()
	{
		$session = new Session();
		$session->deleteSession(session_id());

	}
}