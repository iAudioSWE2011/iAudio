<?php

/**
 * Session
 * 
 * @author Christian Posselt
 * @author Christian Mitterreoter
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

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
	 * deleteMusic
	 * 
	 * @param int $id Music ID
	 */
	public function deleteMusicMID($mid) {
		
		if(count($this->fetchAll($this->select()->where('MID = ?', $mid))->toArray())>0);
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
		
		if(count($this->fetchAll($this->select()->where('PID = ?', $pid))->toArray())>0);
			//delete Music
	        $this->delete(
	        	$this->getAdapter()->quoteInto('PID = ?', $pid)
	        );
	}
	
}

