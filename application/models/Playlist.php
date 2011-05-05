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
		
		//Create Playlist
		$row = $this->createRow();
	   	$row->Name = $name;
        
        $pid = $row->save();
        
        //Link Playlist to User
        $link = new userplaylist();
        $link->linkPlaylistToUser($pid,$uid);
        
        return $pid;
	}
	
	/**
	 * Delete a playlist
	 * 
	 * @param String $name Name of Playlist
	 * @param int $uid User ID
	 * @return int ID of playlist
	 */
	public function deletePlaylist($name,$uid) {

		$playlist = $this->select()
				    ->from(array('p' => 'playlist'),
             			   array('PID' => 'ID', 'Name'))
             	    ->join(array('pm' => 'userplaylist'), 'pm.pid = p.pid')
             	    ->where('Name = ?', $name)
             	    ->where('UID = ?', $uid); 
		
        //Unlink Playlist from User
        $link = new userplaylist();
        $link->unlinkPlaylistFromUser($playlist->PID);
        
        //delete Playlist
        $this->delete(
        	$this->getAdapter()->quoteInto('ID = ?', $playlist->PID)
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

		$playlist = $this->select()
				    ->from(array('p' => 'playlist'),
             			   array('PID' => 'ID', 'Name'))
             	    ->join(array('pm' => 'userplaylist'), 'pm.pid = p.pid')
             	    ->where('Name = ?', $name)
             	    ->where('UID = ?', $uid); 
		
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

		$playlist = $this->select()
				    ->from(array('p' => 'playlist'),
             			   array('PID' => 'ID', 'Name'))
             	    ->join(array('pm' => 'userplaylist'), 'pm.pid = p.pid')
             	    ->where('Name = ?', $name)
             	    ->where('UID = ?', $uid); 
		
        if($playlist)
        {
        	return $playlist->PID;
        }
        
        return NULL;
	}
	
}

