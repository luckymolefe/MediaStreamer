<?php
$basepath = $_SERVER['DOCUMENT_ROOT'].'/'.basename(dirname(__DIR__));
date_default_timezone_set('Africa/Johannesburg'); //set the default timezone to match your country
session_start();
//model to control application access level to database
if(file_exists($basepath.'/config/connection.php') && is_file($basepath.'/config/connection.php')) {
	require_once($basepath.'/config/connection.php');
	$db = new DBConnect();
	$conn = $db->connect();
} else {
	echo "Model Failed to load connection script";
}


/**
* @author: Lucky Molefe
* @param:
* @return: returns an object
*/
class User {
	protected $uid;
	protected $firstname;
	protected $lastname;
	protected $email;
	protected $password;
	private $status;
	private $token;
	private $data = null;

	private $conn = null;
	
	public function __construct() {
		global $conn;
		if(class_exists('DBConnect')) {
			$this->conn = $conn;
		}
	}

	public function page_protected() {
		if(!isset($_SESSION['auth']['token'])) {
			header("Location: login");
		}
	}

	public function login($email, $password) { //login user if all validations went well
		$this->email = $email;
		$this->password = $password;
		if($this->authenticateUser()) {
			if($this->isUserActive($this->email)) {
				$hashedEmail = $this->hashkey($this->email);
				$obj = $this->recovery($hashedEmail);
				if($obj['email'] === $hashedEmail) {
					return "verify"; //if they identical then direct to verify
				}
				else {
					return "success"; //else authenticate user
				}
			}
			else {
				return "verify";
			}
		}
		else {
			return false;
		}
	}

	private function authenticateUser() { //authenticate user an create session token
		$stmt = $this->conn->prepare("SELECT id, email, password, status FROM users WHERE email = ? AND password = ?");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->hashkey($this->password), PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['auth']['token'] = $this->hashkey($row['email']);
			$_SESSION['auth']['uid'] = $row['id'];
			return true;
		}
		else {
			return false;
		}
	}

	public function logout() {
		unset($_SESSION['auth']['token']);
		unset($_SESSION['auth']['uid']);
		session_unset(); //unset session to logout user
		return true;
	}

	private function isUserExists($email) { //check if user account already exits, by email
		$this->email = $email;
		$stmt = $this->conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 0,1");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isUserActive($email) { //chec if user account is activated
		$this->email = $email;
		$stmt = $this->conn->prepare("SELECT email, status FROM users WHERE email = ? LIMIT 0,1");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row['status'] == "1") {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function register($firstname, $lastname, $email, $password) {
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->password = $password;
		$this->status = 0;
		if($this->isUserExists($this->email)) {
			return false; //else user exists;
		} 
		else { 
			return $this->registerUser();
		} //if user does not exists, call method & execute;
	}

	private function registerUser() {
		$stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password, status) VALUES (?, ?, ?, ?, ?)");
		$stmt->bindValue(1, $this->firstname, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->lastname, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(4, $this->hashkey($this->password), PDO::PARAM_STR);
		$stmt->bindValue(5, $this->status, PDO::PARAM_INT);
		try {
			$this->conn->beginTransaction();
			$stmt->execute();
			$lastID = $this->conn->lastInsertId();
			$this->conn->commit();
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->email} inserted record on users table."."\n");
			if($lastID > 0) {
				$this->uid = $lastID;
				$curDate = date('Y-m-d');
				$otpCode = $this->genOTPCode();
				$stmt = $this->conn->prepare("INSERT INTO recovery (id, email, data, expiryDate) VALUES (?, ?, ?, ?)");
				$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
				$stmt->bindValue(2, $this->hashkey($this->email), PDO::PARAM_STR);
				$stmt->bindValue(3, $otpCode, PDO::PARAM_INT); //insert OTP code to be compared later
				$stmt->bindValue(4, $curDate, PDO::PARAM_STR);
				if($stmt->execute()) {
					$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->email} inserted record on recovery table."."\n");
					//then call the template to send message
					if(!is_dir('temp')) { //directory doesn't exist create new one, else don't create another.
						mkdir('temp'); //create new directory
					}
					$fp = fopen("temp/activation.html", 'w');  //temporary email written to file
					  fwrite($fp, $this->OTP_emplate($this->firstname, $this->lastname, $otpCode) );
					fclose($fp);
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		catch(PDOException $e) {
			$this->conn->rollBack();
			echo "Error: ".$e->getMessage();
		}
	}

	public function verify($otpCode, $token) {
		//verify OTP code sent to user via email
		//update users login table first
		$data = $this->recovery($token);
		if($data['data'] == $otpCode) { //if code matches execute below
			foreach($this->getAllUsers() as $row) {
				if($this->hashkey($row['email']) == $token) {
					$email = $row['email']; //search for matching email, to token, then return email
					break;
				}
			}
			$this->status = 1;
			$stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE email = ?");
			$stmt->bindValue(1, $this->status, PDO::PARAM_INT); //then update user status
			$stmt->bindValue(2, $email, PDO::PARAM_STR);
			if($stmt->execute()) {
				$this->log(date('Y-m-d H:i:s')."\t"."user: {$email} changed status on user table."."\n");
				$stmt = $this->conn->prepare("DELETE FROM recovery WHERE email = ?");
				$stmt->bindValue(1, $token, PDO::PARAM_STR); //then delete user verification data
				if($stmt->execute()) {
					$this->log(date('Y-m-d H:i:s')."\t"."user: {$email} deleted record from recovery table."."\n");
					return true;
				}
				else {
					return false;
				}
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}	
	}

	public function isVerified($token) {
		if( !is_bool($this->recovery($token)) ) {
			return true; //return true since user, still in recovery, not activated
		}
		else {
			return false; //user no longer in recovery, user activated account
		}
	}

	public function getAllUsers() {
		$stmt = $this->conn->prepare("SELECT * FROM users");
		// $stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function readProfile($sess_uid) {
		$this->uid = $sess_uid;
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? OR email = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->uid, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function updateProfile($sess_uid, $firstname, $lastname, $email, $username) {
		$this->uid = (int)$sess_uid;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->username = $username;
		return $this->updateUser();
	}

	private function updateUser() {
		$stmt = $this->conn->prepare("UPDATE users SET firstname = ?, lastname = ?, username = ?, email = ? WHERE id = ?");
		$stmt->bindValue(1, $this->firstname, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->lastname, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->username, PDO::PARAM_STR);
		$stmt->bindValue(4, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(5, $this->uid, PDO::PARAM_INT);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} updated profile data on users table"."\n");
			return true;
		}
		else {
			return false;
		}
	}

	public function addFollower($uid, $email) {
		$this->uid = (int)$uid;
		$this->email = $email;
		return $this->followUser();
	}

	private function followUser() {
		$stmt = $this->conn->prepare("INSERT INTO followers (follower_id, followed) VALUES (:myId, :email)");
		$stmt->bindValue(':myId', $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} inserted new record on followers table"."\n");
			return true;
		}
		else {
			return false;
		}
	}

	public function getFollowers($uid, $email) {
		$this->uid = (int)$uid;
		$this->email = $email;
		$stmt = $this->conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND followed = ?");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return true; //$stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function changePassword($sess_uid, $password) {
		$this->uid = $sess_uid;
		$this->password = $password;
		return $this->updatePassword();
	}

	private function updatePassword() {
		$stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
		$stmt->bindValue(1, $this->hashkey($this->password), PDO::PARAM_STR);
		$stmt->bindValue(2, $this->uid, PDO::PARAM_INT);
		if($stmt->execute()) {
			$this->log(date('Y-m-d H:i:s')."\t"."user: {$this->uid} changed their password on users table"."\n"); //write changes to logfile
			return true;
		}
		else {
			return false;
		}
	}

	public function recovery($email) {
		$this->email = $email; //$this->hashkey($email);
		$stmt = $this->conn->prepare("SELECT * FROM recovery WHERE email = ?");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	private function hashkey($data) {
		$this->data = $data;
		return sha1($this->data);
	}

	private function genOTPCode() {
		return rand(10000, 90000);
	}

	public function OTP_emplate($firstname, $lastname, $otpCode) {
		$username = $firstname." ".$lastname;
		$curYear = date('Y-m-d');
		//generate OPT template to send to user
		$htmldata = <<<HTMLDATA
		<title>Account Activation</title>
		<div class="email-background" style="background-color: #eee; padding: 10px;">
			<div class="email-container" style="max-width: 500px;background-color: #fff;font-family: sans-serif;margin: 0 auto;overflow: hidden;border-radius: 5px;text-align: center;">
				<h1 style="color: #72bcd4;">Account Activation</h1>

				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Hello {$username}, welcome to our site.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					To have full access to the awesome application please take a moment to activate your account as this also is to confirm
					your email address as well.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Feel free to contact us <a href="mailto:support@company.com">support@company.com</a> if you have any trouble in the process of activating your account.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Validation code: <span style="background-color: #82b74b;color: #fff;padding: 2px 10px">{$otpCode}</span>
				</p>
				<p style="margin: 20px;display: inline-block;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">Thank You for registering with us.</p>
				<!-- div class="cta" style="margin: 20px;font-weight: bolder;">
					<a href="" style="text-decoration: none;display: inline-block;background-color: #82b74b;color: #fff; transition: all .5s;padding: 10px 20px 10px;border-radius: 5px;border: solid 1px #eee;">Activate account</a>
				</div -->
			</div>
			<div class="footer" style="background-color: none;padding: 20px;font-size: 10px;font-family: sans-serif;text-align: center;">
				<a href="#">123 Str, City</a> | <a href="#">Visit Us Here</a><br>
				<span>&copy;{$curYear} All rights reserved.</span>
			</div>
		</div>
HTMLDATA;
		return $htmldata;
	}
	//call function to log actions
	public function log($value) {
		$this->data = $value;
		return $this->writeToLog();
	}
	//creates and writes to logfile
	protected function writeToLog() {
		define('DIRPATH', 'applogs');
		define('FILENAME', 'logfile.txt');
		$filepath = DIRPATH.'/'.FILENAME;
		// $filepath = "../applogs/logfile.txt";
		if(is_dir(DIRPATH) || is_file($filepath)) {
			$fp = fopen($filepath, 'ab');  //temporary email written to file
				if($fp==false) { $this->logErrors(date('Y-m-d H:i:s')."\t"."Failed to open and write data on AppLogs/logfile.txt."."\n"); exit(); } //if fails
				flock($fp, LOCK_EX);
					fwrite($fp, $this->data, strlen($this->data)); //, strlen($this->data)
				flock($fp, LOCK_UN);
			fclose($fp);
		}
		else {
			$this->logErrors(date('Y-m-d H:i:s')."\t"."Failed to open path AppLogs/logfile.txt."."\n");
			return false;
		}
	}
	//write logfile report on occured writing errors
	private function logErrors($data) {
		$filename = "applogs/error_log.txt";
		$file = fopen($filename, 'ab');
			fwrite($file, $data);
		fclose($file);
	}

}
$model = new User();
// echo $model->verify($code=80354,"05466a50bd124b90fbf94e75a5832197a499cc54");
// $model->log(date('Y-m-d H:i:s')."\t"."Testing data for logfile."."\n");

?>