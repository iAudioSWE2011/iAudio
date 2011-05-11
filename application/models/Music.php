<?php

/**
 * Session
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once 'PlaylistMusic.php';

class Music extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'music';

	/**
	 * saveMusic
	 * 
	 * @param int $uid User ID
	 * @param varchar(50) $name Name des Songs
	 * @param string $link64 Link zu 64kbit/s file
	 * @param string $link128 Link zu 128kbit/s file
	 * @param string $link192 Link zu 192kbit/s file
	 */
	public function addMusic($uid,$name,$artist, $title,$link64,$link128,$link192,$location64,$location128,$location192) {
		
		$row = $this->createRow();
	   	$row->UID = $uid;
	   	$row->Name = $name;
	   	$row->Artist = $artist;
	   	$row->Title = $title;
	   	$row->Link64 = $link64;
	   	$row->Link128 = $link128;
	   	$row->Link192 = $link192;
	   	$row->Location64 = $location64;
	   	$row->Location128 = $location128;
	   	$row->Location192 = $location192;
        
        $row->save();
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link64 Link zu 64kbit/s file
	 */
	public function getLink64($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Link64;
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link128 Link zu 128kbit/s file
	 */
	public function getLink128($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Link128;
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link192 Link zu 192kbit/s file
	 */
	public function getLink192($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Link192;
	}
	
	
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link64 Link zu 64kbit/s file
	 */
	public function getLocation64($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Location64;
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link128 Link zu 128kbit/s file
	 */
	public function getLocation128($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Location128;
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link192 Link zu 192kbit/s file
	 */
	public function getLocation192($id) {
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
        
        return $link->Location192;
	}
	

	
	/**
	 * getMusic
	 * 
	 * @param int $uid User ID
	 * @return array Music files from user
	 */
	public function getMusicfromUser($uid) {
		
		$link = $this->fetchAll($this->select()->where('UID = ?', $uid))->toArray();
        
        return $link;
	}
	
	/**
	 * deleteMusic
	 * 
	 * @param int $id File ID
	 */
	public function deleteMusic($id) {
		
		$playlistmusic = new PlaylistMusic();
		
		$playlistmusic->deleteMusicMID($id);
		
		$link = $this->fetchRow($this->select()->where('ID = ?', $id));
		unlink($link->Location64);
		unlink($link->Location128);
		unlink($link->Location192);
		
		//delete Music
        $this->delete(
        	$this->getAdapter()->quoteInto('ID = ?', $id)
        );
	}
	
}

