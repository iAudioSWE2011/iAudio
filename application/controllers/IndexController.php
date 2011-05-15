<?php

require_once 'Session.php';
require_once 'scripts/functions.php';

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

        $tmpdir = $_SERVER["DOCUMENT_ROOT"] . "/tmp/" . $session->getUserID($sessionid) ."/";
        removeFilesInDir($tmpdir);
    }


}

