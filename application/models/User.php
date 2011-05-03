<?php

/**
 * User
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class User extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'user';

	/**
	 * Holt den Namen eines User per ID
	 * 
	 * @param int $id UserID
	 */
	public function getIDByNameAndMail($name,$mail) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('Name = ?', $name)->where('Mail = ?', $mail));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			return $row->ID;
		}
		
		return NULL;
	}

	/**
	 * Holt den Namen eines User per ID
	 * 
	 * @param int $id UserID
	 */
	public function getNameByID($id) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('id = ?', $id));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			return $row->Name;
		}
		
		return NULL;
	}
	
	/**
	 * Speichert einen neuen Namen unter einer ID
	 * 
	 * @param int $id UserID
	 * @param string $name Name des Users
	 */
	public function setNameByID($id, $name) {
		
		
        $row = $this->fetchRow(
	        	$this->select()->where('id = ?', $id)
	           );
	      
        $row->Name = $name;
        
        $row->save();
	}
	
	/**
	 * Holt das Passwort eines User per ID
	 * 
	 * @param int $id UserID
	 */
	public function getPasswordByID($id) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('id = ?', $id));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			return $row->PW;
		}
		
		return NULL;
	}
	
	/**
	 * Speichert ein neues PW unter einer ID
	 * 
	 * @param int $id UserID
	 * @param string $pw neues PW
	 */
	public function setPasswordByID($id, $pw) {
		
		$pw_md5 = md5($pw);
		
        $row = $this->fetchRow(
	        	$this->select()->where('id = ?', $id)
	           );
	      
        $row->PW = $pw_md5;
        
        $row->save();
	}
	
	/**
	 * Holt das Passwort eines User per ID
	 * 
	 * @param string $mail Email des Users
	 * @param string $pw eingegebenes Passwort
	 */
	public function getCheckPassword($mail, $pw) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('Mail = ?', $mail));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			$savedPW = $row->PW;
		}
		
		$givenPW = md5($pw);
		
		if($savedPW == $givenPW)
			return true;
			
		return false;
	}
	
	/**
	 * Holt die Mailadresse eines User per ID
	 * 
	 * @param int $id UserID
	 */
	public function getMailByID($id) {
		
		// Zeile suchen, in der die ID gefunden wird
		$row = $this->fetchRow($this->select()->where('id = ?', $id));
		
		// Es wurde ein Eintrag gefunden
		if($row) {
			return $row->Mail;
		}
		
		return NULL;
	}
	
	/**
	 * Speichert eine neue Mail unter einer ID
	 * 
	 * @param int $id UserID
	 * @param string $pw neue Mailadresse
	 */
	public function setMailByID($id, $mail) {
		
        $row = $this->fetchRow(
	        	$this->select()->where('id = ?', $id)
	           );
	      
        $row->Mail = $mail;
        
        $row->save();
	}
	
	/**
	 * Legt einen neuen User an
	 * 
	 * @param string $name Name des Users
	 * @param string $mail Mailadresse
	 * @param string $pw Passwort
	 */
	public function createNewUser($name, $mail, $pw) {
		
        $row = $this->fetchRow(
	        	$this->select()
	           );
	      
        $amount = count($row);
        
	   	$row = $this->createRow();
	   	$row->ID = $amount+1;
	   	$row->Name = $name;
	   	$row->Mail = $mail;
	   	$row->PW = md5($pw);
        
        $row->save();
	}
	
}

