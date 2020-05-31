<?php
if(!isset($_GET['comment'])) {
	return false;
	exit();
}
else {
	$user_id = (isset($_GET['uid'])) ? (int)$_GET['uid'] : null;
	$media_id = (isset($_GET['media_id'])) ? (int)$_GET['media_id'] : null;
}
//Detect user agent
if( strstr($_SERVER['HTTP_USER_AGENT'], "Firefox") ){
	$margin_position = "-50px"; //positioning the comment-box popup by detecting user agent
	$top_position = "-100px";
}
else {
	$margin_position = "-45px";
	$top_position = "-85px";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>MediaStreamer | Comments</title>
		<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css"> -->
		<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
		<style type="text/css">
			.container-popup {
				position: absolute;
				background-color: rgba(255, 255, 255, 0.7);
				width: 300px;
				max-width: 100%;
				margin: 0 auto;
				margin-left: 88px;
				margin-top: <?php echo $margin_position; ?>;
				padding: 5px 15px 5px 5px;
				text-align: center;
				border-radius: 4px;
				box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
				font-family: arial, helvetica;
				font-size: 13px;
				z-index: 1;
			}
			.container-popup::after {
				content: ' ';
				width: 0;
				float: left;
				margin-left: -40px;
				margin-top: <?php echo $top_position; ?>;
				border: 18px solid;
				border-color: transparent rgba(200, 200, 200, 0.5) transparent transparent;
			}
			@media screen and (max-width: 480px) {
				.container-popup {
					width: auto;
				}
			}
			textarea {
				resize: none;
				text-indent: none;
			}
			.input-control:focus {
				border-color: #66afe9;
				outline: 0;
				-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
				      box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
			}
			.form-input {
				max-width: 100%;
				width: 100%;
				margin: 0 auto;
				padding: 15px 0 10px 0;
				border: thin solid #bbb;
				border-radius: 3px;
				text-indent: 10px;
				color: #555;
				font-weight: bold;
				resize: none;
			}
			.btn-comment {
				width: 150px;
				padding: 5px;
				border: thin solid #46b8da;
				border-radius: 5px;
				font-weight: bold;
				color: #fff;
				background-color: #4285f4;
				border-color: #46b8da;
				margin-top: 5px;
			}
			.btn-comment:hover {
				background: #5bc0de;
				cursor: pointer;
			}
			.btn-comment:active {
				background-color: #146eb4;
				color: #fff;
				border: thin solid #c5d5cd;
			}
			.btn-comment.btn-block {
				width: 100%;
			}
			.close-item {
				background-color: rgba(255, 255, 255, 0.8);
				color: #e42;
				border-radius: 2px;
				padding: 2px 3px 2px 3px;
				font-size: 15px;
				cursor: pointer;
				float: right;
				margin-top: -5px;
				margin-right: -30px;
				box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
			}
			.close-item:hover {
				background-color: #e42;
				color: #fff;
			}
			#viewComments {
				color: #146eb4;
				font-size: 12px;
				text-decoration: none;
				display: inline-block;
				float: right;
				margin-top: 33px;
				margin-right: -14px;
				font-size: 20px;
			}
			#viewComments:hover {
				color: #555;
			}
			#viewComments:active {
				color: #e42;
			}
			.popIn {
		    	animation: zoomIn .3s ease forwards;
			}
			@keyframes zoomIn {
			  	0%  { transform: scale(0); opacity: 0; }
			  	90%  { transform: scale(1.1);  opacity: 0.85; }
			  	100%{ transform: scale(1); opacity: 1; }
			}
		</style>
	</head>
	<body>
		<div class="container-popup popIn">
			<span class="fa fa-close close-item" title="Close" onclick="closePopup(this);"></span>
			<!-- <a href="javascript:void(0)" id="viewComments" title="view comments">view comments &raquo;</a> -->
			<form action="" method="POST" enctype="application/x-www-urlencoded">
				<a href="javascript:void(0)" id="viewComments" title="view comments" data-uid="<?php echo $user_id; ?>" data-mid="<?php echo $media_id; ?>" onclick="showComments(this)"> &raquo;</a>
				<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
				<input type="hidden" id="media_id" name="media_id" value="<?php echo $media_id; ?>">
				<div><textarea class="form-input" id="commentText" name="commentText" placeholder="comment here..."></textarea></div>
				<div><button type="button" id="post_comment" class="btn-comment btn-block" name="post_comment" value="true" onclick="postComment();">Comment <i class="fa fa-comments"></i></button></div>
			</form>
		</div>
	</body>
</html>