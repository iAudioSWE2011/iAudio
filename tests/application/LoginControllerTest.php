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
		
		$mail = "test@iAudio.com";
		$pw = "test";
		$session = session_id();
		
		$this->dispatch('/Login/save/mail/'.$mail.'/pw/'.$pw);
		$this->assertController('Login');
		$this->assertAction('save');
		
		$this->assertTrue($savedSession->exists($session));

		$savedSession->deleteSession($session);
		
	}
	
	public function testUnsuccessfulLoginPW()
	{
		$savedSession = new Session();
		
		$mail = "test@iAudio.com";
		$pw = "testtest";
		$session = session_id();
		
		$this->dispatch('/Login/save/mail/'.$mail.'/pw/'.$pw);
		$this->assertController('Login');
		$this->assertAction('save');

		$this->assertFalse($savedSession->exists($session));
		
	}
	
	public function testUnsuccessfulLoginMail()
	{
		$savedSession = new Session();
		
		$mail = "testtest@iAudio.com";
		$pw = "test";
		$session = session_id();
		
		$this->dispatch('/Login/save/mail/'.$mail.'/pw/'.$pw);
		$this->assertController('Login');
		$this->assertAction('save');

		$this->assertFalse($savedSession->exists($session));
		
	}
}