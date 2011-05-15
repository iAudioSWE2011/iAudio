<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'Playlist.php';
require_once 'Music.php';
require_once 'PlaylistMusic.php';
require_once 'scripts/functions.php';

class ChooseController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
		$playlist = new Playlist();
		$playlistmusic = new PlaylistMusic();
		$music = new Music();
    	$session = new Session();
    	$sessionid = session_id();
    	$uid = $session->getUserID($sessionid);

    	
    	if(isset($_REQUEST["list"]))
    	{		    	
    		$choose =  $this->_getParam('list');
    		$this->view->choose = $playlistmusic->getMusicWithName($choose);
    		$this->view->playlist =  $choose;
    	}
        
    	$exists = $session->exists($sessionid);
		$this->view->sessionset = $exists;
		$this->view->playlists = $playlist->getPlaylists($uid);
		$this->view->music = $music->getMusicfromUser($uid);
		
		$tmpdir = $_SERVER["DOCUMENT_ROOT"] . "/tmp/" . $session->getUserID($sessionid) ."/";
        removeFilesInDir($tmpdir);
    }
    
    public function chooseAction()
    {
    	$playlist = new Playlist();
		$playlistmusic = new PlaylistMusic();
		$music = new Music();
    	$session = new Session();
    	$sessionid = session_id();
    	$uid = $session->getUserID($sessionid);
    	
    	$choose = $this->_getParam('selection');
    	$playlist = $this->_getParam('playlist');
    	
    	$playlistmusic->deleteMusicPID($playlist);
    	
    	for($i=1;$i<=count($choose);$i++)
    		$playlistmusic->addMusic($choose[$i-1], $playlist, $i);
			
		 $this->_redirect('/Choose?saved=true'); 
    }

}

