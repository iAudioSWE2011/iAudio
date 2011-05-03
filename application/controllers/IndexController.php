<?php

require 'Session.php';

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
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

