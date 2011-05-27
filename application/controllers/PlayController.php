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
    		$count = $playlist->getCount($choose);
    		
    		$uploaddir = $_SERVER["DOCUMENT_ROOT"] . "/tmp/" . $uid ."/";
    		$old_filename = $uploaddir.$choose.'_'.$count.'.m3u';    		
    			
	    	if (file_exists($old_filename)) 
				unlink($old_filename);
				
			$count++;
			$playlist->setCount($choose, $count);
    		
    		$filename = $uploaddir.$choose.'_'.$count.'.m3u';
    		$musiclist = $playlistmusic->getMusicWithName($choose);	
				
		    $output = "";
		    
		    foreach($musiclist as $title)
		    {
		    	if($streamrate == "64")
		    	{
		    		$link = $music->getLink64($title["MID"]);
		    		$output = $output.$link."\n";

		    		$copy_from = $music->getLocation64($title["MID"]);
		    		$copy_to = str_replace("music", "iAudio/public", $copy_from);
		    		$copy_to = str_replace("upload", "tmp", $copy_to);
		    		
		    		copy($copy_from, $copy_to);
		    	}
		    	else if($streamrate == "128")
		    	{
		    		$link = $music->getLink128($title["MID"]);
		    		$output = $output.$link."\n";

		    		$copy_from = $music->getLocation128($title["MID"]);
		    		$copy_to = str_replace("upload", "tmp", $copy_from);
		    		$copy_to = str_replace("application", "public", $copy_to);
		    		
		    		copy($copy_from, $copy_to);
		    	}
		    	else
		    	{
		    		$link = $music->getLink192($title["MID"]);
		    		$output = $output.$link."\n";

		    		$copy_from = $music->getLocation192($title["MID"]);
		    		$copy_to = str_replace("upload", "tmp", $copy_from);
		    		$copy_to = str_replace("application", "public", $copy_to);
		    		
		    		copy($copy_from, $copy_to);
		    	}
		    }
    		
			$fh = fopen($filename, 'w') or die("can't open file");
			fwrite($fh, $output);
			fclose($fh);
    	
			$this->view->m3u = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$choose."_".$count.".m3u";
			$this->view->listid = $choose;
			$this->view->music = $musiclist;
    	}
        
    	$exists = $session->exists($sessionid);
		$this->view->sessionset = $exists;
		$this->view->playlists = $playlist->getPlaylists($uid);
		
    }
   
}

