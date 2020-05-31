<?php
//notification model to control application access level to database
if(file_exists('model/post_model.php') && is_file('model/post_model.php')) {
	require_once('model/post_model.php');
} else {
	echo "Failed to include neccessary file.";
}
/**
* @author: Lucky Molefe
* @param:
* @return: returns an object
*/
class Notification extends Posts {
	private $conn = null;

	public function __construct() {
		parent::__construct();
		global $conn;
		$this->conn = $conn;
	}

	public function checkNotification($email) {
		$this->email = (string)$email;
		$status = 1;
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
		$status = 2;
		$stmt = $this->conn->prepare("UPDATE followers SET message_status = ? WHERE follower_id = ? AND followed = ?");
		$stmt->bindValue(1, $status, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->email} changed message status on followers table."."\n"); //write changes to logfile
			return true;
		}
	}

	public function setFeedNotify($email) {
		$this->email = $email;
		$post_status = 2;
		$stmt = $this->conn->prepare("UPDATE followers SET post_status = ? WHERE followed = ?");
		$stmt->bindValue(1, $post_status, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->email} changed post status on followers table."."\n"); //write changes to logfile
			return true;
		}
		else {
			return false;
		}
	}

	public function getFeedNotify($uid) {
		$this->uid = (int)$uid;
		$post_status = 1;
		$stmt = $this->conn->prepare("SELECT * FROM followers WHERE follower_id = :uid AND post_status = :postStatus");
		$stmt->bindValue(':uid', $this->uid, PDO::PARAM_STR);
		$stmt->bindValue(':postStatus', $post_status, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function unsetFeedNotify($uid) {
		$this->uid = $uid;
		$post_status = 2;
		$stmt = $this->conn->prepare("UPDATE followers SET post_status = ? WHERE follower_id = ?");
		$stmt->bindValue(1, $post_status, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->uid, PDO::PARAM_INT);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} changed post status on followers table."."\n"); //write changes to logfile
			return true;
		}
		else {
			 return false;
		}
	}

}
$notify_model = new Notification();
/*$row=$notify_model->getFeedNotify('1');
echo $row['followed'];*/

?>