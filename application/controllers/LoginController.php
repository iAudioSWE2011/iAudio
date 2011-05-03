<?php

require 'User.php';
require 'Session.php';

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        session_start();
    }
    
    public function indexAction()
    {   	
    	$this->view->variable = $this->_getParam('Error');;
    }

    public function saveAction()
    {
        $user = new User();
        $session = new Session();
    	
    	$mail = $this->_getParam('mail');
    	$pw = $this->_getParam('pw');
    	
    	if($user->getCheckPassword($mail,$pw))
    	{
    		$session->saveSession(session_id());
    		$this->_redirect('/');
    	}
    	else
    		$this->_redirect('/Login?Error=true');
    	
        
    }


}

