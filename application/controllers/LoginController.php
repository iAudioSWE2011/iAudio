<?php

require_once 'User.php';
require_once 'Session.php';

class LoginController extends Zend_Controller_Action
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
    	
    	$mail = $this->_getParam('mail');
    	$pw = $this->_getParam('pw');
    	
    	if($user->checkPasswordByMail($mail,$pw))
    	{
    		$session->saveSession(session_id(),$user->getIDByMail($mail));
    		$this->_redirect('/');
    	}
    	else
    		$this->_redirect('/Login?Error=true');
        
    }


}

