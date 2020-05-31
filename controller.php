<?php
$basepath = $_SERVER['DOCUMENT_ROOT'].'/'.basename(dirname(__DIR__));
if(file_exists('model/model.php') && is_file('model/model.php')) {
	require_once('model/model.php');
} else {
	echo "Failed to load neccessary file!.";
}

if(isset($_POST['login']) && $_POST['login'] == "true") {
	sleep(1);
	$email = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$password = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	$remember_me = ($_POST['remember'] == "on") ? htmlentities(stripslashes(strip_tags(trim($_POST['remember'])))) : 'off';
	if(!empty($email) && !empty($password)) {
		//get method to login
		$results = $model->login($email, $password);
		if($results === "verify") {
			$response['message'] = "verify";
			$response['url'] = "verify";
		}
		elseif($results === "success") {
			//quickly process login form cookies
			if($remember_me == "on") {
				//if remember checkbox is checked, then setcookie
				setcookie("email", $email, time() + (10 * 356 * 24 * 60 * 60));
                setcookie("password", $password, time() + (10 * 356 * 24 * 60 * 60));
			} else {
				//else if remember was unchecked then, unset/erase cookie
				if(!empty($_COOKIE['email'])) { //unset email cookie
					setcookie("email", "", time() - (10 * 356 * 24 * 60 * 60));
				}
				if(!empty($_COOKIE['password'])) { //unset password cookie
	                setcookie("password", "", time() - (10 * 356 * 24 * 60 * 60));
                }
			}
			//then process message
			$response['message'] = "success";
			$response['url'] = "home";
		}
		else {
			$response['message'] = "invalid";
		}
	}
	else {
		$response['message'] = "failed";
	}
	echo json_encode($response);
	// echo $response;
	exit();
}

if(isset($_POST['appLogin']) && $_POST['appLogin'] == "mobile") {
	$email = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$password = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	if(!empty($email) && !empty($password)) {
		$results = $model->login($email, $password);
		if($results) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "failed";
		}
	}
	else {
		$response['message'] = "null";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['logout']) && $_REQUEST['logout'] == "true") {
	sleep(1);
	if($model->logout()) {
		$response['url'] = "login";
		// header("Location: login.php");
	}
	echo json_encode($response);
	exit();
}

if(isset($_POST['signup']) && $_POST['signup'] == "true") {
	sleep(1);
	$firstname = htmlentities(trim($_POST['firstname']));
	$lastname = htmlentities(trim($_POST['lastname']));
	$email = htmlentities(trim($_POST['email']));
	$password = htmlentities(trim($_POST['password']));
	$password_confirm = htmlentities(trim($_POST['password_confirm']));
	if($password != $password_confirm) {
		$response['message'] = "invalid";
	}
	if(!empty($firstname) && !empty($lastname) && !empty($password) && !empty($password_confirm)) {
		//execute the code to call method to save data
		// $results = $model->register($firstname, $lastname, $email, $password);
		if($model->register($firstname, $lastname, $email, $password)) {
			$response['message'] = "success";
			$response['url'] = "login";
			$response['inbox'] = "temp/activation.html";
			/*if(is_dir('temp') && file_exists('temp/activation.html')) { //directory exist open inside it.
				$filename = "temp/activation.html"; //open dir temp to get the file.
				echo "<script>window.open('".$filename."','_blank')</script>"; //automatically opens new mail, as temp file.
			}*/
		}
		else {
			$response['message'] = "failed";
		}
	}
	else {
		$response['message'] = "failed";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['verify']) && $_REQUEST['verify'] == "true") {
	sleep(1);
	$verificationCode = (int)trim($_REQUEST['verification_code']);
	$token = $_REQUEST['token'];
	if(!empty($verificationCode)) {
		//process verififcation code, call DB to match code
		if($model->verify($verificationCode, $token)) {
			$response['message'] = "success";
			$response['url'] = "home";
			unlink('temp/activation.html');
		}
		else {
			$response['message'] = "invalid";
		}
	}
	else {
		$response['message'] = "null";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['resend']) && $_REQUEST['resend'] == "true") {
	$token = $_REQUEST['token'];
	if($model->recovery($token)) {
		if(is_dir('temp') && file_exists('temp/activation.html')) { //directory exist open inside it.
			$filename = "temp/activation.html"; //open dir temp to get the file.
			echo "<script>window.open('".$filename."','_blank')</script>"; //automatically opens new mail, as temp file.
		}
		echo "<script>window.location.href = 'verify.php?action=success'</script>";
	}
	else {
		echo "Failed to resend code. Please try again";
	}
	exit();
}

if(isset($_GET['isVerified']) && $_GET['isVerified'] == "true") {
	$token = trim($_GET['token']);
	if(!$model->isVerified($token)) {
		$response['message'] = "true";
		$response['url'] = "home";
	}
	else {
		$response['message'] = "false";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['changepassword']) && $_REQUEST['changepassword'] == "true") {
	sleep(1);
	$data[] = (int)$_REQUEST['sess_uid'];
	$data[] = trim($_REQUEST['password']);
	$data[] = trim($_REQUEST['password_confirm']);
	if(!empty($data[1]) || !empty($data[2])) {
		if($data[1] != $data[2]) {
			$response['message'] = "invalid";
		}
		if($model->changePassword($data[0], $data[2])) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "failed";
		}
	}
	else {
		$response['message'] = "failed";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['profileupdate']) && $_REQUEST['profileupdate'] == "true") {
	sleep(1);
	$data[] = (int)$_REQUEST['sess_uid'];
	$data[] = htmlentities(stripslashes(strip_tags(trim($_REQUEST['firstname']))));
	$data[] = htmlentities(stripslashes(strip_tags(trim($_REQUEST['lastname']))));
	$data[] = htmlentities(stripslashes(strip_tags(trim($_REQUEST['email']))));
	$data[] = htmlentities(stripslashes(strip_tags(trim($_REQUEST['username']))));
	if(!empty($data[1]) || !empty($data[2]) || !empty($dta[3])) {
		if($model->updateProfile($data[0], $data[1], $data[2], $data[3], $data[4])) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "failed";
		}
	}
	else {
		$response['message'] = "invalid";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['follow']) && $_REQUEST['follow'] == "true") {
	$uid = (int)$_REQUEST['uid'];
	$email = htmlentities(stripslashes(strip_tags(trim($_REQUEST['email']))));
	if(!empty($uid) || !empty($email)) {
		if($model->addFollower($uid, $email)) {
			$response['response'] = "OK";
			$response['message'] = "<i class='fa fa-info-circle'></i> Followed!...";
		}
		else {
			$response['message'] = "<i class='fa fa-warning'></i> Failed to follow";
		}
	}
	else {
		$response['message'] = "<i class='fa fa-warning'></i> Failed to follow";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['getCode']) && $_REQUEST['getCode'] == "true" && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
    sleep(1); //for development mode
    function genRndString($length = 5, $chars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	    if($length > 0)
	    {
	        $len_chars = (strlen($chars) - 1);
	        $the_chars = $chars{rand(0, $len_chars)};
	        for ($i = 1; $i < $length; $i = strlen($the_chars))
	        {
	            $r = $chars{rand(0, $len_chars)};
	            if ($r != $the_chars{$i - 1}) $the_chars .=  $r;
	        }

	        return $the_chars;
	    }
	}
	#$captcha = genRndString(); //call the generated code
    function getCaptchaCode() {
        // require_once('keygen.php');
        // $captcha = genRndString(); //SHA1(microtime());
        $capCode = substr(genRndString(), 0,6);
        $_SESSION['capKeyCode'] = $capCode; //set generated code in a session
        // images/defaults/capt_image.gif
        $newImage = imagecreatefromjpeg("backgrounds/cap_bg.jpg");
        $txtColor = imagecolorallocate($newImage, 50, 50, 50);
        imagestring($newImage, 5, 5, 5, $capCode, $txtColor);
        header("Content-type: image/jpeg");
        ob_start();
        // return base64_encode(imagejpeg($newImage));
        imagejpeg($newImage);
        $outputBuffer = ob_get_clean();
        $base64 = base64_encode($outputBuffer);
        // return $base64;
        // return '<img src="data:image/jpeg;base64,'.$base64.'" />';
        $arrayData['url'] = '<img src="data:image/jpeg;base64,'.$base64.'" />'; //add captcha image to an array
        $arrayData['keyGen'] = $_SESSION['capKeyCode']; //set key in an array from a session
        ob_flush();
        return json_encode($arrayData); //return array as json data
    }
    echo getCaptchaCode(); //call function
    exit();
}


?>