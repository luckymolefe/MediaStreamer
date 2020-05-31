<?php
//notification controller
if(file_exists('model/notification_model.php') && is_file('model/notification_model.php')) {
	require_once('model/notification_model.php');
} else {
	echo "Failed to load neccessary file!.";
}

if(isset($_REQUEST['notify']) && $_REQUEST['notify'] == "true") {
	$email = (isset($_REQUEST['email'])) ? (string)$_REQUEST['email'] : null;
	if($row = $notify_model->checkNotification($email)) {
		$user = $model->readProfile($row['follower_id']);
		$username = (!empty($user['username'])) ? '@'.$user['username'] : $user['firstname'].' '.$user['lastname'];
		$response['response'] = "OK";
		$response['message'] = '<i class="fa fa-info-circle" id="follower" data-followerID="'.$row['follower_id'].'"></i> '.$username. ' has followed you!.';
	}
	else {
		$response['message'] = "Failed to get notification.";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['messageUnset']) && $_REQUEST['messageUnset'] == "true") {
	$email = htmlentities(stripslashes(strip_tags(trim($_REQUEST['email']))));
	$follower_id = (isset($_REQUEST['followerId'])) ? (int)$_REQUEST['followerId'] : null;
	$notify_model->unsetNotification($follower_id, $email); //update notification message on close
	exit();
}

if(isset($_REQUEST['getfeednotify']) && $_REQUEST['getfeednotify'] == "true") {
	$uid = (!empty($_REQUEST['uid'])) ? (int)$_REQUEST['uid'] : null;
	$uid = htmlentities(stripslashes(strip_tags(trim($uid))));
	if($row = $notify_model->getFeedNotify($uid)) {
		$user = $model->readProfile($row['followed']);
		$username = (!empty($user['username'])) ? '@'.$user['username'] : $user['firstname'].' '.$user['lastname'];
		$response['response'] = "OK";
		$response['message'] = "<i class='fa fa-info-circle' id='follower'></i> ".$username." posted new media!.";
	}
	else {
		$response['message'] = "No notification available!.";
	}
	echo json_encode($response);
	exit();
}

if (isset($_REQUEST['setfeednotify']) && $_REQUEST['setfeednotify'] == "true") {
	$email = (!empty($_REQUEST['email'])) ? (int)$_REQUEST['email'] : null;
	$email = htmlentities(stripslashes(strip_tags(trim($email))));
	if($notify_model->setFeedNotify($email)) {
		$response['message'] = "OK";
	}
	else {
		$response['message'] = "failed";
	}
	echo json_encode($response);
	exit();
}
/*$_REQUEST['notifyFeedUnset'] = "true";
$_REQUEST['uid'] = 1;*/
if(isset($_REQUEST['notifyFeedUnset']) && $_REQUEST['notifyFeedUnset'] == "true") {
	//update notification after reading popup
	$uid = (!empty($_REQUEST['uid'])) ? (int)$_REQUEST['uid'] : null;
	if($notify_model->unsetFeedNotify($uid)) {
		$response['message'] = "OK";
	}
	else {
		$response['message'] = "failed";
	}
}

?>