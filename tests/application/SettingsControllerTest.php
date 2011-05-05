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
	
	public function testChangeStreamrateSuccessful()
	{
		$user = new User();
		
		$streamrate_old = "128";
		$streamrate_new = "192";
		
		//change to new Rate
		$this->dispatch('/Settings/stream/streamingrate/'.$streamrate_new);
		$this->assertTrue($user->getStreamingRateByMail($this->mail) == $streamrate_new);
		
		//change back
		$this->dispatch('/Settings/stream/streamingrate/'.$streamrate_old);
		$this->assertTrue($user->getStreamingRateByMail($this->mail) == $streamrate_old);
	}
	
	public function testChangeMailSuccessful()
	{
		$user = new User();
		
		$mail_new = "test2@iAudio.com";
		$mail_check = "test2@iAudio.com";
		
		//change to new Mail
		$this->dispatch('/Settings/mail/mail_old/'.$this->mail.'/mail/'.$mail_new.'/mail_check/'.$mail_check);
		$this->assertTrue($user->userExists($mail_new));
		
		//change back
		$this->dispatch('/Settings/mail/mail_old/'.$mail_new.'/mail/'.$this->mail.'/mail_check/'.$this->mail);
		$this->assertTrue($user->userExists($this->mail));
	}
	
	public function testChangeMailUnsuccessfulMailInvalid()
	{
		$user = new User();
		
		$mail_new = "test2";
		$mail_check = "test2";
		
		//try change to new Mail
		$this->dispatch('/Settings/mail/mail_old/'.$this->mail.'/mail/'.$mail_new.'/mail_check/'.$mail_check);
		$this->assertFalse($user->userExists($mail_new));
	}
	
	public function testChangeMailUnsuccessfulMailMismatch()
	{
		$user = new User();
		
		$mail_new = "test2@iAudio.com";
		$mail_check = "test@iAudio.com";
		
		//try change to new Mail
		$this->dispatch('/Settings/mail/mail_old/'.$this->mail.'/mail/'.$mail_new.'/mail_check/'.$mail_check);
		$this->assertFalse($user->userExists($mail_new));
	}
	
	public function testChangeMailUnsuccessfulMailExists()
	{
		$user = new User();
		
		$mail_new = "tester2@iAudio.com";
		$mail_check = "tester2@iAudio.com";
		
		//try change to new Mail
		$this->dispatch('/Settings/mail/mail_old/'.$this->mail.'/mail/'.$mail_new.'/mail_check/'.$mail_check);
		$this->assertTrue($user->userExists($this->mail));
	}
	
	public function testChangePWSuccessful()
	{
		$user = new User();
		
		$pw_old = "test";
		$pw_new = "tester";
		$pw_check = "tester";
		
		//change to new Mail
		$this->dispatch('/Settings/password/pw_old/'.$pw_old.'/pw/'.$pw_new.'/pw_check/'.$pw_check);
		$this->assertTrue($user->checkPasswordByMail($this->mail, $pw_new));
		
		//change back
		$this->dispatch('/Settings/password/pw_old/'.$pw_new.'/pw/'.$pw_old.'/pw_check/'.$pw_old);
		$this->assertTrue($user->checkPasswordByMail($this->mail, $pw_old));
	}
	
	public function testChangePWUnsuccessfulOldPWWrong()
	{
		$user = new User();
		
		$pw_old = "tester";
		$pw_new = "tester2";
		$pw_check = "tester2";
		
		//try change to new Mail
		$this->dispatch('/Settings/password/pw_old/'.$pw_old.'/pw/'.$pw_new.'/pw_check/'.$pw_check);
		$this->assertFalse($user->checkPasswordByMail($this->mail, $pw_new));
	}
	
	public function testChangePWUnsuccessfulNewPWMismatch()
	{
		$user = new User();
		
		$pw_old = "test";
		$pw_new = "tester";
		$pw_check = "tester2";
		
		//try change to new Mail
		$this->dispatch('/Settings/password/pw_old/'.$pw_old.'/pw/'.$pw_new.'/pw_check/'.$pw_check);
		$this->assertFalse($user->checkPasswordByMail($this->mail, $pw_new));
	}
	
	public function tearDown()
	{
		$session = new Session();
		$session->deleteSession(session_id());

	}
}