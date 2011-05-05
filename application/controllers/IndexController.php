<?php

require_once 'Session.php';

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }

    public function indexAction()
    {
        $session = new Session();
        $sessionid = session_id();
        
        $exists = $session->exists($sessionid); 
        $this->view->sessionset = $exists;      
    }


}

