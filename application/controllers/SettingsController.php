<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'scripts/functions.php';

class SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
    	$user = new User();
    	$session = new Session();
    	
    	$sessionid = session_id();
        $exists = $session->exists($sessionid);
    	$uid = $session->getUserID(session_id());
    	
    	$this->view->currentRate = $user->getStreamingRateByID($uid);
    	$this->view->currentMail = $user->getMailByID($uid);
		$this->view->sessionset = $exists;
		
		$tmpdir = $_SERVER["DOCUMENT_ROOT"] . "/tmp/" . $session->getUserID($sessionid) ."/";
        removeFilesInDir($tmpdir);
    }

    public function streamAction()
    {
        $user = new User();
    	$session = new Session();
        
    	$uid = $session->getUserID(session_id());
        $streamrate = $this->_getParam('streamingrate');

        $user->setStreamingRateByID($uid, $streamrate);
        
        $this->_redirect('/Settings?savedStream=true');      
    }
    
	public function mailAction()
    {
        $user = new User();
    	$session = new Session();
        
    	$uid = $session->getUserID(session_id());
    	$oldMail = $this->_getParam('mail_old');
        $newMail = $this->_getParam('mail');
        $newMail_check = $this->_getParam('mail_check');
        
        if(($oldMail == $newMail) AND ($newMail == $newMail_check))
        	$this->_redirect('/Settings?savedMail=true');
        else if(empty($newMail) OR empty($newMail_check) OR !email_check($newMail))
         	$this->_redirect('/Settings?invalid=true');
        else if($newMail != $newMail_check)
         	$this->_redirect('/Settings?newMail=false');       
        else if ($user->userExists($newMail))
    		$this->_redirect('/Settings?registered=true');
     	else
        	$user->setMailByID($uid, $newMail);
        
        $this->_redirect('/Settings?savedMail=true');       
    }
    
	public function passwordAction()
    {
        $user = new User();
    	$session = new Session();
        
    	$uid = $session->getUserID(session_id());
    	$pw_old = $this->_getParam('pw_old');
        $pw = $this->_getParam('pw');
        $pw_check = $this->_getParam('pw_check');

        if($pw!=$pw_check)
        	$this->_redirect('/Settings?pwchange=false'); 
        else if($user->checkPasswordByID($uid, $pw_old))
        {
        	$user->setPasswordByID($uid, $pw);
        	$this->_redirect('/Settings?savedPW=true'); 
        }
        else
        	$this->_redirect('/Settings?oldpw=false');
        	 
        $this->_redirect('/Settings?savedPW=true');       
    }


}

