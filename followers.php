<?php
if(!isset($_GET['groupadd'])) {
	return false;
	exit();
}
if(file_exists('post_controller.php') && is_file('post_controller.php')) {
	require_once('post_controller.php');
} else {
	echo "Failed to load neccessary file!.";
}
	//$users = array("Lucky Molefe", "Chris Laudon", "Naomi Rosenburg", "Bernedette Liebenburg", "Kristine Van Rooyen", "Christine Demarko");
$users = $model->getAllUsers();
$uid = (isset($_SESSION['auth']['uid'])) ? $_SESSION['auth']['uid'] : null;
?>
<!DOCTYPE html>
<html>
<head>
	<title>MediaStreamer | Followers</title>
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<style type="text/css">
		.container-followers {
			width: 600px;
			max-width: 100%;
			height: 370px;
			margin: 0 auto;
			background: -webkit-radial-gradient(#46b8da, #10325E);
			padding: 15px;
			border-radius: 5px;
			font-family: arial, helvetica;
			margin-top: 50px;
		}
		
		h1 {
			text-align: center;
			color: #fff;
		}
		.followers {
			/*max-width: 100%;*/
			max-height: 280px;
			overflow-y: auto;
			color: #fff;
		}
		.follow-item {
			background: -webkit-linear-gradient(#46b8da, #ccc);
			padding: 10px;
			margin-bottom: 5px;
			border-radius: 5px;
			list-style-type: none;
			color: #fff;
		}
		.follow-item:hover {
			background: -webkit-linear-gradient(#d7d7d7, #46b8da);
			cursor: pointer;
		}
		.close-item {
			background-color: rgba(255, 255, 255, 0.8);
			color: #e42;
			border-radius: 2px;
			padding: 2px 3px 2px 3px;
			font-size: 15px;
			cursor: pointer;
			float: right;
			margin-top: -8px;
			margin-right: -8px;
			box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
		}
		.close-item:hover {
			background-color: #e42;
			color: #fff;
		}
		.follow {
			font-size: 30px;
			color: #999;
		}
		.follow:hover {
			color: #0b0;
		}
		.followed {
			font-size: 25px;
			color: #4285f4;
		}
		hr.ruler {
			border-top: none;
			border-color: #4285f4;
			height: 0;
			-webkit-box-sizing: content-box;
			 -moz-box-sizing: content-box;
			      box-sizing: content-box;
		}
		.message-item {
			background-color: #4285f4;
			color: #fff;
			text-align: center;
			padding: 15px 10px;
			border-radius: 5px;
		}

		@media screen and (max-width: 680px) {
			.container-followers {
				width: 500px;
			}
			.notification-bar {
				left: 100px;
			}
		}
		@media all and (max-width: 530px) {
			.container-followers {
				width: 400px;
			}
			.notification-bar {
				left: 0px;
			}
		}
		@media all and (max-width: 430px) {
			.container-followers {
				width: 250px;
			}
			.follow {
				font-size: 25px;
			} 
			.notification-bar {
				width: 250px;
			}
		}
		.popIn {
		    	animation: zoomIn .3s ease forwards;
		}
		@keyframes zoomIn {
		  	0%   { transform: scale(0); opacity: 0; }
		  	90%  { transform: scale(1.1);  opacity: 0.85; }
		  	100% { transform: scale(1); opacity: 1; }
		}
	</style>
	<script type="text/javascript">
		function closeWindow() {
			$('.layer').html('').fadeOut('slow');
		}
		function followUser(action) {
			var email = action.dataset.email;
			var myID = "<?php echo $uid; ?>";
			$('#notification-bar').removeClass('popOut').addClass('popIn'); //reset to default, to re-use the popup
			$.post("controller.php", {"follow":"true", "uid":myID, "email":email}, function(data) {
				$('#notification-bar').html('<span class="fa fa-close notify-quit" title="Close" onclick="closeMsgPop()"></span>');
				var jason = JSON.parse(data);
				if(jason.response == "OK") {
					action.classList.remove('fa-user-plus','follow');
					action.classList.add('fa-users','followed');
					action.removeAttribute('onclick');
					$('#notification-bar').prepend(jason.message).show();
				} else {
					$('#notification-bar').prepend(jason.message).show();
				}
			});
		}
	</script>
</head>
<body>
	<div class="container-followers popIn">
		<span class="fa fa-close close-item" title="Close" onclick="closeWindow();"></span>
		<h1>Followers list</h1>
		<hr class="ruler">
		<div class="followers">
		<?php if(count($users) > 0) { 
			foreach($users as $user) : 
				if($user['id'] == $uid) { continue; }
		?>
			<li class="follow-item">
				<span class="fa fa-user"></span> 
					<?php echo $user['firstname'].' '.$user['lastname']; ?>
				<?php if(!$model->getFollowers($uid, $user['email'])) { ?>
					<span class="fa fa-user-plus follow pull-right" title="follow user" data-email="<?php echo $user['email']; ?>" onclick="followUser(this)"></span>
				<?php } else { ?>
					<span class="fa fa-users followed pull-right" title="followed"></span>
				<?php } ?>
			</li>
		<?php endforeach; } else { ?>
			<li class="message-item"><i class="fa fa-info-circle"></i> No followers!.</li>
		<?php } ?>
		<div>
	</div>
</body>
</html>