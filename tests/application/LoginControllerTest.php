<?php
require_once '../TestHelper.php';
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class LoginControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public $application;	// Zend_Application f�r Bootstrap
	
	public function setUp() {
		$this->bootstrap = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		
		parent::setUp();
		$this->dispatch('/Login');
	}
	
	public function testController()
	{
		$this->dispatch('/Login');
		$this->assertController('Login');
	}
	
public function testSuccessfulLogin()
	{
		$savedSession = new Session();
		
		$mail = "posselt@hm.edu";
		$pw = "test123";
		$session = session_id();
		
		$this->dispatch('/Login/save/mail/'.$mail.'/pw/'.$pw);
		$this->assertController('Login');
		$this->assertAction('save');
		
		$this->assertTrue($savedSession->exists($session));

		$savedSession->deleteSession($session);
		
	}
	
	public function testUnsuccessfulLogin()
	{
		$savedSession = new Session();
		
		$mail = "posselt@hm.edu";
		$pw = "123test";
		$session = session_id();
		
		$this->dispatch('/Login/save/mail/'.$mail.'/pw/'.$pw);
		$this->assertController('Login');
		$this->assertAction('save');

		$this->assertFalse($savedSession->exists($session));
		
	}
}