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
		$id = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->dispatch('Playlist/delete/id/'.$id);
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
		$id = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$amount = count($playlist->getPlaylists($session->getUserID(session_id())));
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		$amountNew = count($playlist->getPlaylists($session->getUserID(session_id())));
		
		$this->assertTrue($amount == $amountNew);
		
		$this->dispatch('Playlist/delete/id/'.$id);
	}
	
	public function testDeletePlaylists()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		
		$this->dispatch('Playlist/create/name/'.$name);
		
		$id = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/id/'.$id);
		
		$this->assertFalse($playlist->existsForUser($name, $session->getUserID(session_id())));
	
	}
	
	public function testRenamePlaylists()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = 'NewTestPlaylistName';
		
		$this->dispatch('Playlist/create/name/'.$name);
		$id = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/rename/id/'.$id.'/newname/'.$newName);
		
		$this->assertFalse($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/id/'.$id);
	
	}
	
	public function testRenamePlaylistsNoNewName()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = '';
		
		$this->dispatch('Playlist/create/name/'.$name);
		$id = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
	
		$this->dispatch('Playlist/rename/id/'.$id.'/newname/'.$newName);
		
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertFalse($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/id/'.$id);
	
	}
	
	public function testRenamePlaylistsDoublicate()
	{
		$playlist = new Playlist();
		$session = new Session();
		$name = 'TestPlaylist';
		$newName = 'NewTestPlaylistName';
		
		$this->dispatch('Playlist/create/name/'.$name);
		$this->dispatch('Playlist/create/name/'.$newName);
		$id1 = $playlist->getPlaylistID($name, $session->getUserID(session_id()));
		$id2 = $playlist->getPlaylistID($newName, $session->getUserID(session_id()));
		
		//does the playlist exists?
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/rename/name/'.$name.'/newname/'.$newName);
		
		$this->assertTrue($playlist->existsForUser($name, $session->getUserID(session_id())));
		$this->assertTrue($playlist->existsForUser($newName, $session->getUserID(session_id())));
		
		$this->dispatch('Playlist/delete/id/'.$id1);
		$this->dispatch('Playlist/delete/id/'.$id2);
	
	}
	
	
	public function tearDown()
	{
		$session = new Session();
		$session->deleteSession(session_id());

	}
}