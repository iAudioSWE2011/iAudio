<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'Playlist.php';
require_once 'scripts/functions.php';

class PlaylistController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
		$playlist = new Playlist();
    	$session = new Session();
    	$sessionid = session_id();
        
    	$exists = $session->exists($sessionid);
		$this->view->sessionset = $exists;
		$this->view->playlists = $playlist->getPlaylists($session->getUserID($sessionid));
    }

    public function createAction()
    {
        $user = new User();
        $playlist = new Playlist();
    	$session = new Session();
        $sessionid = session_id();
               
    	$name = $this->_getParam('name');
    	
    	if($name == "")
    		$this->_redirect('/Playlist?savedPlaylist=empty');
    	else if($playlist->existsForUser($name, $session->getUserID(session_id())))
    		$this->_redirect('/Playlist?savedPlaylist=exists');
    	else 
    		$playlist->createPlaylist($name, $session->getUserID($sessionid));
        
        $this->_redirect('/Playlist?savedPlaylist=true');      
    }
    
	public function renameAction()
    {
        $user = new User();
        $playlist = new Playlist();
    	$session = new Session();
        $sessionid = session_id();
               
    	$id = $this->_getParam('id');
    	$newname = $this->_getParam('newname');
    	
    	if($newname == "")
    		$this->_redirect('/Playlist?renamePlaylist=empty');
    	else if($id == "")
    		$this->_redirect('/Playlist?renamePlaylist=noList');
    	else if($playlist->existsForUser($newname, $session->getUserID(session_id())))
    		$this->_redirect('/Playlist?renamePlaylist=exists');
    	else 
    		$playlist->renamePlaylist($id, $newname);
        
        $this->_redirect('/Playlist?renamePlaylist=true');      
    }
    
	public function deleteAction()
    {
        $playlist = new Playlist();
    	$session = new Session();
        $sessionid = session_id();
               
    	$id = $this->_getParam('id');
    	
    	if($id == "")
    		$this->_redirect('/Playlist?deletePlaylist=empty');
    	else 
    		$playlist->deletePlaylist($id);
        
        $this->_redirect('/Playlist?deletePlaylist=true');      
    }


}

