<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'scripts/functions.php';

class RegisterController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {   	
    	$this->view->variable = $this->_getParam('Error');
    }

    public function saveAction()
    {
        $user = new User();
        $session = new Session();
    	
        $name = $this->_getParam('name');
    	$mail = $this->_getParam('mail');
    	$pw = $this->_getParam('pw');
    	$pw_check = $this->_getParam('pw_check');
    	
    	
    	if(empty($name) OR empty($mail) OR empty($pw) OR !email_check($mail))
    		$this->_redirect('/Register?Error=true&name='.urlencode($name).'&mail='.urlencode($mail));
    	
    	else if ($pw != $pw_check)
    	{
    		$this->_redirect('/Register?Error=pw&name='.urlencode($name).'&mail='.urlencode($mail));
    	}
    	else if ($user->userExists($mail))
    	{
    		$this->_redirect('/Register?Error=exists&name='.urlencode($name));
    	}
    	else
    	{
   			
    		//Insert new User into Database
	    	$user->createNewUser($name, $mail, $pw);
	
	    	//Auto-Login of new Users
	    	$session->saveSession(session_id());
	    	$this->_redirect('/');
    	}
        
    }


}

