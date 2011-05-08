<?php

/**
 * Playlist
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Playlist extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'playlist';

	/**
	 * Create a playlist
	 * 
	 * @param String $name Name of Playist
	 * @param int $uid User ID
	 * @return int ID of playlist
	 */
	public function createPlaylist($name,$uid) {
		
		$row = $this->fetchAll(null, "ID")->toArray();
	      
        $amount = count($row);
        $amount++;
		
		//Create Playlist
		$row = $this->createRow();
	   	
		$row->ID = $amount;
		$row->Name = $name;
	   	$row->UID = $uid;
        
        $pid = $row->save();
 
        return $amount;
	}
	
	/**
	 * Delete a playlist
	 * 
	 * @param String $name Name of Playlist
	 * @param int $uid User ID
	 * @return int ID of playlist
	 */
	public function deletePlaylist($name,$uid) {
       
        //delete Playlist
        $this->delete(
        	$this->getAdapter()->quoteInto('UID = ?', $uid)->quoteInto('name = ?', $name)
        );
	}
	
	/**
	 * Tests whether a Playlist with this name
	 * exists for a user
	 * 
	 * @param String $name Name of Playlist
	 * @param int $uid User ID
	 * @return boolean: true oder false
	 */
	public function existsForUser($name,$uid) {

		$playlist = $this->select()->where('Name = ?', $name)->where('UID = ?', $uid); 
		
        return count($playlist)>0;
	}
	
	/**
	 * Returns the ID of a specific Playlist
	 * 
	 * @param String $name Name of Playlist
	 * @param int $uid User ID
	 * @return int: ID of Playlist
	 */
	public function getPlaylistID($name,$uid) {

		$playlist = $this->select()->where('Name = ?', $name)->where('UID = ?', $uid); 
		
        if($playlist)
        {
        	return $playlist->PID;
        }
        
        return NULL;
	}
	
}

