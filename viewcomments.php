<?php
if(!isset($_GET['view'])) {
	return false;
	exit();
}
if(file_exists('post_controller.php') && is_file('post_controller.php')) {
	require_once('post_controller.php');
} else {
	echo "Failed to load neccessary file!.";
}
$uid = (isset($_REQUEST['uid'])) ? (int)$_REQUEST['uid'] : null;
$media_id = (isset($_REQUEST['mediaid'])) ? (int)$_REQUEST['mediaid'] : null; //check if media_id is not null
$comments = $post_model->getComments($media_id); //pass media_id parameter and get comments for the current post

?>
<!DOCTYPE html>
<html>
	<head>
		<title>MediaStreamer | Search tracks</title>
		<style type="text/css">
			.container-view {
				background-color: rgba(255, 255, 255, 0.7);
				width: 650px;
				height: 400px;
				max-height: 400px;
				max-width: 100%;
				margin: 0 auto;
				margin-top: 50px;
				padding: 5px;
				border-radius: 4px;
				box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
				font-family: arial, helvetica, serif;
				font-size: 15px;
				z-index: 2;
			}
			.box-header {
				background: -webkit-linear-gradient(#46b8da, #10527B);
				text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.3);
				-webkit-background-clip: text;
				-webkit-text-fill-color: transparent;
				font-size: 25px;
				font-weight: bold;
				text-align: center;
			}
			.comments-list {
				width: 575px;
				max-width: 100%;
				height: 300px;
				max-height: 300px;
				overflow-y: auto;
				list-style-type: none;
				margin-left: -10px;
			}
			.comment-item {
				position: relative;
				background-color: #4285f4;
				color: #fff;
				padding: 10px;
				text-align: left;
				border-radius: 5px;
				margin-bottom: 8px;
				margin-left: 50px;
			}
			.comment-item:before {
				position: absolute;
				content: ' ';
				width: 0;
				top: 8px;
				left: -16px;
				border: 8px solid;
				border-color: transparent #4285f4 transparent transparent;
			}
			@media screen and (max-width: 580px) {
				.comments-list {
					margin-left: -40px;
				}
				.comment-item {
					margin-left: 15px;
				}
				.comment-item:before {
					display: none;
				}
				.user-profile {
					display: none;
				}
			}
			.user-profile {
				float: left;
				font-size: 30px;
				/*border: 2px solid #4285f4;*/
				color: #4285f4;
				/*border-radius: 5px;*/
				padding: 2px 6px;
			}
			.user-profile:hover {
				color: #fff;
			}
			.message-item {
				background-color: #4285f4;
				color: #fff;
				text-align: center;
				padding: 15px 10px;
				border-radius: 5px;
			}
			.remove-item {
				color: #e42;
				border-radius: 2px;
				padding: 2px 5px 2px 5px;
				font-size: 15px;
				cursor: pointer;
				float: right;
			}
			.remove-item:hover {
				background-color: #e42;
				color: #fff;
			}
			.popIn {
		    	animation: zoomIn .3s ease forwards;
			}
			@keyframes zoomIn {
			  	0%   { transform: scale(0); opacity: 0; }
			  	90%  { transform: scale(1.1);  opacity: 0.85; }
			  	100% { transform: scale(1); opacity: 1; }
			}
			.popOut {
				animation: zoomOut .3s ease forwards;
			}
			@keyframes zoomOut {
				0% 	 { transform: scale(1); opacity: 1; }
				100% { transform: scale(0); opacity: 0; }
			}
		</style>
		<script type="text/javascript">
			function closeItem() {
				$('.container-view').removeClass('popIn').addClass('popOut').delay(300).queue(function() {
					$('.layer').html('').hide();
				});
			}
		</script>
	</head>
	<body>
		<div class="container-view popIn">
			<span class="fa fa-close remove-item" title="Close" onclick="return closeItem();"></span>
			<h1 class="box-header">Comments</h1>
			<ul class="comments-list">
				<?php if($comments) {
					$allUsers = $model->getAllUsers();
					foreach($comments as $row) :
						foreach($allUsers as $all) {
							if($all['id'] === $row['user_id']) {
								$user = $model->readProfile($all['id']);
								$firstname = $user['firstname'];
								$lastname = $user['lastname'];
								$fullname = $firstname.' '.$lastname;
								$names = (!empty($user['username'])) ? '@'.$user['username'] : $fullname;
								// continue;
							}
						}
				?>
					<i class="fa fa-user-circle user-profile" title="<?php echo $names; ?>"></i>
					<li class="comment-item"> <?php echo $row['comments']; ?></li>
				<?php endforeach; } else { ?>
					<li class="message-item"><i class="fa fa-info-circle"></i> No comments for this post!.</li>
				<?php } ?>
			</ul>
		</div>
		<!-- some additional paragraph again some additional paragraph again some additional paragraph again -->
	</body>
</html>