<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'Playlist.php';
require_once 'Music.php';
require_once 'PlaylistMusic.php';
require_once 'scripts/functions.php';

class PlayController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
		$user = new User();
    	$playlist = new Playlist();
		$playlistmusic = new PlaylistMusic();
		$music = new Music();
    	$session = new Session();
    	$sessionid = session_id();
    	$uid = $session->getUserID($sessionid);

    	
    	if(isset($_REQUEST["list"]))
    	{		    	
    		$choose =  $this->_getParam('list');
    		$streamrate = $user->getStreamingRateByID($uid);
    		
    		$uploaddir = $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $uid ."/";
    		$filename = $uploaddir.$choose.'.m3u';
    		
	    	if (file_exists($filename)) 
				unlink($filename);
				
		    $output = "";
		    
		    foreach($playlistmusic->getMusicWithName($choose) as $title)
		    {
		    	if($streamrate == "64")
		    	{
		    		$link = $music->getLink64($title["MID"]);
		    		$output = $output.$link."\n"; 
		    	}
		    	else if($streamrate == "128")
		    	{
		    		$link = $music->getLink128($title["MID"]);
		    		$output = $output.$link."\n"; 
		    	}
		    	else
		    	{
		    		$link = $music->getLink192($title["MID"]);
		    		$output = $output.$link."\n"; 
		    	}
		    }
    		
			$fh = fopen($filename, 'w') or die("can't open file");
			fwrite($fh, $output);
			fclose($fh);
    	
			$this->view->m3u = $filename;
    	}
        
    	$exists = $session->exists($sessionid);
		$this->view->sessionset = $exists;
		$this->view->playlists = $playlist->getPlaylists($uid);
		
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
    	
    	foreach($choose as $mid)
			$playlistmusic->addMusic($mid, $playlist);
			
		 $this->_redirect('/Choose?saved=true'); 
    }

}

