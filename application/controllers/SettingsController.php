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
		$this->view->sessionset = $exists;
 
    }

    public function streamAction()
    {
        $user = new User();
    	$session = new Session();
        
    	$uid = $session->getUserID(session_id());
        $streamrate = $this->_getParam('streamingrate');

        $user->setPasswordByID($uid, $streamrate);
        
        $this->_redirect('/Settings?saved=true');
        
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
        	$this->_redirect('/Settings?saved=true'); 
        }
        else
        	$this->_redirect('/Settings?oldpw=false');
        	 
        $this->_redirect('/Settings?saved=true');       
    }


}

