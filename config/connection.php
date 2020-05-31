<?php
$basepath = $_SERVER['DOCUMENT_ROOT'].'/'.basename(dirname(__DIR__));
if(file_exists($basepath.'/config/credentials.php') && is_file($basepath.'/config/credentials.php')) {
	require_once($basepath.'/config/credentials.php');
} else {
	echo "Connection Failed to load logins file!";
}
// require_once('../config/credentials.php');
/**
* @author: LUCKY MOLEFE
* @description:
* @param: parameters are passed within the class itself
* @return: returns an object with connection to database.
*/
class DBConnect {
	// include('credentials.php');
	const DB_HOST = DB_HOST;
	const DB_NAME = DB_NAME;
	const DB_USERNAME = DB_USERNAME;
	const DB_PASSWORD = DB_PASSWORD;
	private $connect = null;
	
	public function __construct() {
		/*global $dbcon;
		$this->connect = $dbcon;*/
	}

	public function connect() {
		return $this->doConnect();
	}

	private function doConnect() {
		try {
			$this->connect = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
		}
		catch(PDOException $e) {
		  echo "Error: ".$e->getMessage();
		}
		return $this->connect;
	}

	/*public function __destruct() {
		$this->connect->close();
	}*/
}

/*$db = new DBConnect();
if($db->connect()) {
	echo "Successfully Connected!...";
}
else {
	echo "Failed to connect!";
}*/

?>