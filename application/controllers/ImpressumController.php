<?php

require_once 'Session.php';

class ImpressumController extends Zend_Controller_Action
{

    public function init()
    {
        date_default_timezone_set('Europe/Berlin');
    	session_start();
    }
    
    public function indexAction()
    {	
    }


}

