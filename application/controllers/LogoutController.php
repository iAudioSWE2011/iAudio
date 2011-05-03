<?php

require 'User.php';
require 'Session.php';

class LogoutController extends Zend_Controller_Action
{

    public function init()
    {
        session_start();
    }
    
    public function indexAction()
    {
    	$session = new Session();
    	$session->deleteSession(session_id());
    	
    	$this->_redirect('/');
    }


}

