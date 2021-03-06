<?php

require_once 'User.php';
require_once 'Session.php';
require_once 'scripts/functions.php';

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

    	removeFilesInDir($_SERVER["DOCUMENT_ROOT"] . "/tmp/" . $session->getUserID(session_id()) ."/");	
    	$session->deleteSession(session_id());
    	
    	$this->_redirect('/');
    }

}

