<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'Playlist.php';
require_once 'Music.php';
require_once 'PlaylistMusic.php';
require_once 'scripts/functions.php';

class MusicController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
		$playlist = new Playlist();
		$music = new Music();
    	$session = new Session();
    	$sessionid = session_id();
        
    	$exists = $session->exists($sessionid);
		$this->view->sessionset = $exists;
		$this->view->playlists = $playlist->getPlaylists($session->getUserID($sessionid));
		$this->view->music = $music->getMusicfromUser($session->getUserID($sessionid));
    }
    
    public function uploadAction()
    {
		$zul_endungen = array('mp3');
		$wrong_extension = "false";
		$wrong_filesize = "false";
    	
    	$music = new Music();
    	$session = new Session();
    	$uid = $session->getUserID(session_id());
    	
    	$uploaddir = $_SERVER["DOCUMENT_ROOT"] . "/upload/";

    	//create folder if not exists
    	if(!is_dir($uploaddir))
    		mkdir($uploaddir,0777);
    	
    	$uploaddir = $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $uid ."/";

    	//create folder if not exists
    	if(!is_dir($uploaddir))
    		mkdir($uploaddir,0755);
    	
    	//handle each file
		foreach ($_FILES["files"]["error"] as $key => $error) 
		{
	
		   //only save files that were uploaded correctly
		   if ($error == UPLOAD_ERR_OK) 
		   {
		       
		   		$str_laenge = strlen($_FILES["files"]["name"][$key]);
				$punkt = strrpos($_FILES["files"]["name"][$key], ".");
				$datei_name = substr($_FILES["files"]["name"][$key], 0, $punkt);
				$datei_endung = substr($_FILES["files"]["name"][$key], $punkt+1,$str_laenge);
				
		   		if(in_array(strtolower($datei_endung),$zul_endungen))
				{
			   	   $filelocation = $uploaddir.$_FILES["files"]["name"][$key];
			   	   $iplocation = "http://".$_SERVER["HTTP_HOST"]."/upload/".$uid."/".$_FILES["files"]["name"][$key];
			   	
			   	   if (file_exists($filelocation))
			       {
			       		$filelocation = $_FILES["files"]["name"][$key];
					    
			       		$punkt = strrpos($filelocation,".");
						$str_teil1 = substr($filelocation,0,$punkt);
						$str_teil2 = strchr($filelocation,".");
						$i=1;
						$gibt='true';
						$filelocation = $uploaddir.$str_teil1.'['.$i.']'.$str_teil2;
						$iplocation = "http://".$_SERVER["HTTP_HOST"]."/upload/".$uid."/".$str_teil1.'['.$i.']'.$str_teil2;
						
						//do until index is new
						if (file_exists($filelocation))
						{
							while ($gibt == 'true')
							{
								$name = $str_teil1.'['.$i.']'.$str_teil2;
								$filelocation = $uploaddir.$name;
								$iplocation = "http://".$_SERVER["HTTP_HOST"]."/upload/".$uid."/".$name;
								if (file_exists($filelocation))
								{
									$gibt = 'true';
									$i++;	
								}
								else 
									$gibt = 'false';
								
							}
						}
			       }
			   	
			   	   move_uploaded_file(
			         $_FILES["files"]["tmp_name"][$key],
			         $filelocation
			       );
			       
			       chmod ("/somedir/somefile", 0644);
			       
			       $punkt = strrpos($_FILES["files"]["name"][$key],".");
				   $name = substr($_FILES["files"]["name"][$key],0,$punkt); 
			       
			       $music->addMusic($uid, $name, $iplocation, $iplocation, $iplocation, $filelocation, $filelocation, $filelocation);
				}
				else
					$wrong_extension = "true";
		   }
		   else
		   	$wrong_filesize = "true";
		}
    	
		if($wrong_filesize == "true")
			$this->_redirect('/Music?size=true');
		
		if($wrong_extension == "true")
			$this->_redirect('/Music?extension=true');
		
		$this->_redirect('/Music?upload=true');   
    }
    
	public function deleteAction()
    {
	
    	$music = new Music();
    	
    	$id = $this->_getParam('id');

    	if($id == "")
    		$this->_redirect('/Music?delete=empty');
    		
    	$music->deleteMusic($id);

		$this->_redirect('/Music?delete=true');   
    }


}

