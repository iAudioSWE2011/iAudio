<?php
require_once '../TestHelper.php';
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class RegisterControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public $application;	// Zend_Application f�r Bootstrap
	
	public function setUp() {
		$this->bootstrap = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		
		parent::setUp();
		$this->dispatch('/Register');
	}
	
	public function testController()
	{
		$this->dispatch('/Register');
		$this->assertController('Register');
	}
	
	public function testSuccessfulRegistration()
	{
		$savedUser = new User();
		$savedSession = new Session();
		
		$name = "Hochschultester";
		$mail = "test@hm.edu";
		$pw = "test123";
		$session = session_id();
		
		$this->dispatch('/Register/save/name/'.$name.'/mail/'.$mail.'/pw/'.$pw.'/pw_check/'.$pw);
		
		$this->assertTrue($savedUser->userExists($mail));
		$this->assertTrue($savedSession->exists($session));

        $savedSession->deleteSession($session);
		$savedUser->deleteUser($mail);
		
	}
	
	
}