<?php

require_once 'User.php';
require_once 'Session.php';

class LogoutController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {
    	$session = new Session();
    	$session->deleteSession(session_id());
    	
    	$this->_redirect('/');
    }


}

