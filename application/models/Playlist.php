<?php

/**
 * Playlist
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once 'PlaylistMusic.php';

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
	   	$row->UID = $uid;
	   	$row->Count = 0;
        
        $pid = $row->save();
 
        return $pid;
	}
	
	/**
	 * Delete a playlist
	 * 
	 * @param int $id Playlist ID
	 */
	public function deletePlaylist($id) {
       
		$playlistmusic = new PlaylistMusic();
		
		$playlistmusic->deleteMusicPID($id);
		
        //delete Playlist
        $this->delete(
        	$this->getAdapter()->quoteInto('ID = ?', $id)
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

		$playlist = $this->fetchAll($this->select()->where('Name = ?', $name)->where('UID = ?', $uid)); 
		
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

		$playlist = $this->fetchRow($this->select()->where('Name = ?', $name)->where('UID = ?', $uid)); 
		
        if($playlist)
        {
        	return $playlist->ID;
        }
        
        return NULL;
	}
	
	/**
	 * Returns all Playlists of a User
	 * 
	 * @param int $uid User ID
	 * @return array: All Playlist of a User
	 */
	public function getPlaylists($uid) 
	{
        return $this->fetchAll($this->select()->where('UID = ?', $uid)->order('Name ASC'));
	}
	
	/**
	 * Renams a specific Playlist
	 * 
	 * @param String $name Name of Playlist
	 * @param int $id ID of Playlist
	 */
	public function renamePlaylist($id,$name) {

		$playlist = $this->fetchRow($this->select()->where('ID = ?', $id)); 
		
        $playlist->Name = $name;
        
        $playlist->save();
	}
	
	/**
	 * Gets the amount of times, the playlist was played
	 * 
	 * @param int $id ID of Playlist
	 */
	public function getCount($id) {

		$playlist = $this->fetchRow($this->select()->where('ID = ?', $id)); 
		
        return $playlist->Count;
	}
	
	/**
	 * Sets the amount of times, the playlist was played
	 * 
	 * @param int $id ID of Playlist
	 * @param int $count amount of Playlist starts
	 */
	public function setCount($id, $count) {

		$playlist = $this->fetchRow($this->select()->where('ID = ?', $id)); 
		
        $playlist->Count = $count;
        
        $playlist->save();
	}
	
}

