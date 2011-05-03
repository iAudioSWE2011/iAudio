<?php

/**
 * Session
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Session extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'session';

	/**
	 * Tests whether user is logged in with this session
	 * 
	 * @param int $session sessionID
	 */
	public function exists($session) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('sessionid = ?', $session));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			return true;
		}
		
		return false;
	}

	/**
	 * save session
	 * 
	 * @param string $session sessionID
	 */
	public function saveSession($session) {
		
		$row = $this->createRow();
	   	$row->sessionid = $session;
        
        $row->save();
	}
	
	/**
	 * delete session
	 * 
	 * @param string $session sessionID
	 */
	public function deleteSession($session) {
		
		$this->delete(
        	$this->getAdapter()->quoteInto('sessionid = ?', $session)
        );

	}
	
}

