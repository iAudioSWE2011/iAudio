<?php

/**
 * Session
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once 'Music.php';

class PlaylistMusic extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'playlistmusic';

	/**
	 * saveMusic
	 * 
	 * @param int $mid Music ID
	 * @param $pid $mid Playlist ID
	 * @param string $link128 Link zu 128kbit/s file
	 * @param string $link192 Link zu 192kbit/s file
	 */
	public function addMusic($mid,$pid) {
		
		$row = $this->createRow();
	   	$row->MID = $mid;
	   	$row->PID = $pid;
        
        $row->save();
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link64 Link zu 64kbit/s file
	 */
	public function getMusic($pid) {
		
		$music = $this->fetchAll($this->select()->where('PID = ?', $pid))->toArray();
        
        return $music;
	}
	
	/**
	 * getMusic
	 * 
	 * @param int $id File ID
	 * @return string $link64 Link zu 64kbit/s file
	 */
	public function getMusicWithName($pid) {
		
		$select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
		$select->setIntegrityCheck(false)
		       ->join('music', 'music.ID = playlistmusic.MID')
		       ->where('playlistmusic.PID = ?', $pid);
 
		$rows = $this->fetchAll($select);
        
        return $rows;
	}
	
	/**
	 * getMusic
	 */
	public function existsInPlaylist($pid,$mid) {
		
		$music = $this->fetchAll($this->select()->where('PID = ?', $pid)->where('MID = ?', $mid))->toArray();
        
        return count($music)>0;
	}
	
	/**
	 * deleteMusic
	 * 
	 * @param int $id Music ID
	 */
	public function deleteMusicMID($mid) {
		
	
			//delete Music
	        $this->delete(
	        	$this->getAdapter()->quoteInto('MID = ?', $mid)
	        );
	}
	
	/**
	 * deleteMusic
	 * 
	 * @param int $pid Playlist ID
	 */
	public function deleteMusicPID($pid) {

			//delete Music
	        $this->delete(
	        	$this->getAdapter()->quoteInto('PID = ?', $pid)
	        );
	}
	
}

