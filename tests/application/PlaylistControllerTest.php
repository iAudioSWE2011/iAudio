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
		$session = new Session();
		$name = 'TestPlaylist';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->dispatch('Playlist/delete/name/'.$name);
	}
	
	
	public function testCreateNewPlaylistNoName()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = '';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertFalse($playlist->existsForUser($name, $session->getUserID(session_id())));
		
	}
	
	
	public function testNoDoublePlaylists()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$amount = count($playlist->getPlaylists($session->getUserID(session_id())));
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		$amountNew = count($playlist->getPlaylists($session->getUserID(session_id())));
		
		$this->assertTrue($amount == $amountNew);
		
		$this->dispatch('Playlist/delete/name/'.$name);
	}
	
	public function testDeletePlaylists()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/name/'.$name);
		
		$this->assertFalse($playlist->existsForUser($name, $session->getUserID(session_id())));
	
	}
	
	public function testRenamePlaylists()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = 'NewTestPlaylistName';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/rename/name/'.$name.'/newname/'.$newName);
		
		$this->assertFalse($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/name/'.$newName);
	
	}
	
	public function testRenamePlaylistsNoNewName()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = '';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/rename/name/'.$name.'/newname/'.$newName);
		
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertFalse($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/name/'.$name);
	
	}
	
	public function testRenamePlaylistsDoublicate()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = 'NewTestPlaylistName';
		
		$this->dispatch('Playlist/create/name/'.$name);
		$this->dispatch('Playlist/create/name/'.$newName);
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/rename/name/'.$name.'/newname/'.$newName);
		
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/name/'.$name);
		$this->dispatch('Playlist/delete/name/'.$newName);
	
	}
	
	
	public function tearDown()
	{
		$session = new Session();
		$session->deleteSession(session_id());

	}
}