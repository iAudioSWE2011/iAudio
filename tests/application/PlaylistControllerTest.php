<?php
require_once '../TestHelper.php';
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class PlaylistControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
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
		
		$this->dispatch('/Playlist');
	}
 
	public function testController()
	{	
		$this->dispatch('/Playlist');
		$this->assertController('Playlist');
	}
	
	public function testCreateNewPlaylist()
	{
		$playlist = new Playlist();
		$userplaylist = new UserPlaylist();
		$session = new Session();
		
		$name = 'TestPlaylist';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
	}
	
	public function tearDown()
	{
		$session = new Session();
		$session->deleteSession(session_id());

	}
}