<?php

//model to control application access level to database
if(file_exists('model/model.php') && is_file('model/model.php')) {
	require_once('model/model.php');
} else {
	echo "Failed to include requested neccessary file.";
}

/**
* 
*/
class Posts extends User {
	protected $uid;
	protected $urlpath;
	public $filedescription;
	private $conn = null;
	
	public function __construct() {
		parent::__construct();
		global $conn;
		$this->conn = $conn;
	}

	public function postMedia($uid, $fullpath, $description) {
		$this->uid = $uid;
		$this->urlpath = $fullpath;
		$this->filedescription = $description;
		return $this->savePost();
	}

	private function savePost() {
		$stmt = $this->conn->prepare("INSERT INTO media (user_id, media_url, description) VALUES (?, ?, ?)");
		$stmt->bindValue(1, $this->uid);
		$stmt->bindValue(2, $this->urlpath);
		$stmt->bindValue(3, $this->filedescription);
		try {
			$this->conn->beginTransaction();
			$results = $stmt->execute();
			$this->conn->commit();
			if($results) {
				$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} inserted record on media table."."\n");
				return true;
			}
			else {
				return false;
			}
		}
		catch(PDOException $e) {
			$this->conn->rollBack();
			echo "ERROR: ".$e->getMessage();
		}
	}

	public function addToPlaylist($mid, $uid, $url) {
		$this->uid = $uid;
		if(!$this->getPlaylistItem($this->uid, $mid)) { //check if
			$stmt = $this->conn->prepare("INSERT INTO playlist (media_id, user_id, media_url) VALUES (:mid, :uid, :media_url)");
			$stmt->bindParam(':mid', $mid, PDO::PARAM_INT);
			$stmt->bindParam(':uid', $this->uid, PDO::PARAM_INT);
			$stmt->bindParam(':media_url', $url, PDO::PARAM_INT);
			try{
				$this->conn->beginTransaction();
				$response = $stmt->execute();
				$this->conn->commit();
				if($response) {
					$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} inserted record on playlist table"."\n");
					return true;
				}
				else {
					return false;
				}
			}
			catch(PDOException $e) {
				$this->conn->rollBack();
				echo "ERROR: ".$e->getMessage();
			}
		}
		else {
			return false;
		}
	}

	public function addReaction($uid, $mid) {
		$this->uid = $uid;
		if(!$this->checkReaction($uid, $mid)) {
			$stmt = $this->conn->prepare("INSERT INTO likes (user_id, media_id) VALUES (:uid, :mid)");
			$stmt->bindParam(':uid', $this->uid, PDO::PARAM_INT);
			$stmt->bindParam(':mid', $mid, PDO::PARAM_INT);
			try{
				$this->conn->beginTransaction();
				$response = $stmt->execute();
				$this->conn->commit();
				if($response) {
					$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} inserted record on likes table."."\n");
					return true;
				}
				else {
					return false;
				}
			}
			catch(PDOException $e) {
				$this->conn->rollBack();
				echo "ERROR: ".$e->getMessage();
			}
		}
		else {
			return false;
		}
	}

	public function checkReaction($uid, $mid) {
		$this->uid = $uid;
		$stmt = $this->conn->prepare("SELECT media_id, user_id FROM likes WHERE user_id = ? AND media_id = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $mid, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getPlaylist($uid) {
		$this->uid = $uid;
		$stmt = $this->conn->prepare("SELECT * FROM playlist WHERE user_id = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function checkPlaylistItem($uid, $mid) {
		return $this->getPlaylistItem($uid, $mid);
	}

	private function getPlaylistItem($uid, $mid) {
		$this->uid = $uid;
		$stmt = $this->conn->prepare("SELECT media_id, user_id FROM playlist WHERE user_id = ? AND media_id = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $mid, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function removePlaylistItem($user_id, $media_id) {
		$this->uid = (int)$user_id;
		$stmt = $this->conn->prepare("DELETE FROM playlist WHERE user_id = ? AND media_id = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $media_id, PDO::PARAM_INT);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} deleted record from playlist table."."\n");
			return true;
		}
		else {
			return false;
		}
	}

	public function saveComment($user_id, $media_id, $comment_data) {
		$this->uid = (int)$user_id;
		$stmt = $this->conn->prepare("INSERT INTO comments (media_id, user_id, comments) VALUES (?, ?, ?)");
		$stmt->bindValue(1, $media_id);
		$stmt->bindValue(2, $this->uid);
		$stmt->bindValue(3, $comment_data);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} inserted record on comments table."."\n");
			return true;
		}
		else {
			return false;
		}
	}

	public function getComments($media_id) {
		$stmt = $this->conn->prepare("SELECT * FROM comments WHERE media_id = ?");
		$stmt->bindValue(1, $media_id, PDO::PARAM_INT);
		if($stmt->execute()) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function searchTracks($medianame) {
		$medianame = "%".$medianame."%";
		$stmt = $this->conn->prepare("SELECT * FROM playlist WHERE media_url LIKE ?");
		$stmt->bindValue(1, $medianame, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function getPosts() {
		$stmt = $this->conn->prepare("SELECT * FROM media ORDER BY created DESC");
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll();
		}
		else {
			return false;
		}
	}
	public function getMobilePosts() {
		$stmt = $this->conn->prepare("SELECT id, description FROM media ORDER BY created DESC");
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	/*public function checkNotification($email) {
		$this->email = (string)$email;
		$status = 0;
		$stmt = $this->conn->prepare("SELECT * FROM followers WHERE followed = ? AND message_status = ?");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(2, $status, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function unsetNotification($follower_id, $email) {
		$this->uid = $follower_id;
		$this->email = $email;
		$status = 1;
		$stmt = $this->conn->prepare("UPDATE followers SET message_status = ? WHERE follower_id = ? AND followed = ?");
		$stmt->bindValue(1, $status, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->email} changed message status on followers table."."\n"); //write changes to logfile
			return true;
		}
	}*/

	public function timeDiff($old_time) { //formats time stamps on posted items
		$difference = strtotime(date('Y-m-d')) - strtotime($old_time);
		$minutes_past = floor($difference / (60)); //get number of minutes
		$hours_past = floor($difference / (60 * 60)); //get number of hours
		$days_past = floor($difference / (60 * 60 * 24)); //get number of days
		/*calculating days*/
		if($days_past <= 0) {
			$day = "Today";
		}
		else if( $days_past == 1) {
			$day = "Yesterday";
		}
		else if( $days_past > 1 && $days_past < 7) {
			$day = $days_past." Days ago";
		}
		else if( $days_past >= 7 && $days_past <= 13) {
			$day = "1W";
		}
		else if( $days_past == 14) {
			$day = "2W";
		}
		else if( $days_past > 14 && $days_past < 30) {
			$day = "Weeks ago";
		}
		else if( $days_past == 30 || $days_past == 31) {
			$day = "1M";
		}
		else if( $days_past > 31) {
			$day = "Months ago";
		}
		else if( $days_past > 365) {
			$day = "Years ago";
		}
		return $day;
	}

}
$post_model = new Posts();

?>