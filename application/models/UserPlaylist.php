<?php

/**
 * PlaylistMusic
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class UserPlaylist extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'userplaylist';

	/**
	 * Puts a playlist to a user
	 * 
	 * @param int $pid Playist ID
	 * @param int $uid User ID
	 */
	public function linkPlaylistToUser($pid,$uid) {
		
		$row = $this->createRow();
	   	$row->PID = $pid;
	   	$row->UID = $uid;
        
        $row->save();
	}
	
	/**
	 * Unlinks a playlist from a user
	 * 
	 * @param int $pid Playist ID
	 */
	public function unlinkPlaylistFromUser($pid) {
		
		$this->delete(
        	$this->getAdapter()->quoteInto('PID = ?', $pid)
        );
	}
	
	/**
	 * returns the user of a playlist 
	 * 
	 * @param int $pid Playist ID
	 * @return int $uid User ID
	 */
	public function getUser($pid) {
		
		$row = $this->fetchRow($this->select()->where('PID = ?', $pid));
        
        return $row->UID;
	}
	
}

