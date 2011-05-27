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
    	
    	$uploaddir = "/var/www/music/upload/" . $uid ."/";
		$uploaddir = str_replace("public", "application", $uploaddir);
    	
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
			   	   $iplocation = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$_FILES["files"]["name"][$key];
			   	
			   	   if (file_exists($filelocation))
			       {
			       		$filelocation = $_FILES["files"]["name"][$key];
					    
			       		$punkt = strrpos($filelocation,".");
						$str_teil1 = substr($filelocation,0,$punkt);
						$str_teil2 = strchr($filelocation,".");
						$i=1;
						$gibt='true';
						$datei_name = $str_teil1.'['.$i.']';
						$filelocation = $uploaddir.$str_teil1.'['.$i.']'.$str_teil2;
						$iplocation = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$str_teil1.'['.$i.']'.$str_teil2;
						
						//do until index is new
						if (file_exists($filelocation))
						{
							while ($gibt == 'true')
							{
								$name = $str_teil1.'['.$i.']'.$str_teil2;
								$filelocation = $uploaddir.$name;
								$datei_name = $str_teil1.'['.$i.']';
								$iplocation = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$name;
								
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
			   	
			       $ID3 = mp3info($_FILES["files"]["tmp_name"][$key]);
			       
			       $artist = $ID3[artist];
			       $title = $ID3[title];
			       
			   	   move_uploaded_file(
			         $_FILES["files"]["tmp_name"][$key],
			         $filelocation
			       );


			       $punkt = strrpos($_FILES["files"]["name"][$key],".");
				   $name = substr($_FILES["files"]["name"][$key],0,$punkt);
				   
				   if($title == "")
				   		$title = $name;
				   		
				   if($artist == "")
				   		$artist = " unknown ";
	
				   	php_uname(); 	
				   		
			       	if(PHP_OS!="Linux")
			       		$music->addMusic($uid, $name, $artist, $title, $iplocation, $iplocation, $iplocation, $filelocation, $filelocation, $filelocation);
					else  
					{
						$ip64 = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$datei_name."_64.mp3";
						$ip128 = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$datei_name."_128.mp3";
						$ip192 = "http://".$_SERVER["HTTP_HOST"]."/tmp/".$uid."/".$datei_name."_192.mp3";
						$file64 = $uploaddir.$datei_name."_64.mp3";
						$file128 = $uploaddir.$datei_name."_128.mp3";
						$file192 = $uploaddir.$datei_name."_192.mp3";
						
						shell_exec("lame -b 64 '$filelocation' '$file64'");
						shell_exec("lame -b 128 '$filelocation' '$file128'");
						shell_exec("lame -b 192 '$filelocation' '$file192'");
						
						unlink($filelocation);
						
						$music->addMusic($uid, $name, $artist, $title, $ip64, $ip128, $ip192, $file64, $file128, $file192);
					}
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
