<?php
if(!isset($_GET['loadplaylist'])) {
	return false;
	exit();
}
if(file_exists('post_controller.php') && is_file('post_controller.php')) {
	require_once('post_controller.php');
} else {
	echo "Failed to load neccessary file!.";
}
$sess_uid = (isset($_SESSION['auth']['uid'])) ? $_SESSION['auth']['uid'] : null;
$tracklist = $post_model->getPlaylist($sess_uid);
?>
<!DOCTYPE html>
<html>
<head>
	<title>MediaStreamer | Search tracks</title>
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<style type="text/css">
		.container-item {
			/*background: -webkit-radial-gradient(#b8a9c9, #5c4084);*/
			background: -webkit-radial-gradient(#10527B, #10325E);
			width: 600px;
			height: 350px;
			max-width: 100%;
			margin: 0 auto;
			padding: 15px;
			text-align: center;
			border-radius: 4px;
			margin-top: 100px;
			font-family: helvetica;
		}
		.box-header {
			background: -webkit-linear-gradient(#f5f5f5, #aaa);
			text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.3);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			font-size: 25px;
			font-weight: bold;
		}
		.input-control {
			width: 500px;
			max-width: 100%;
			margin: 0 auto;
			border: thin solid #ccc;
			border-radius: 5px;
			padding: 12px 10px;
			color: #777;
			outline: 0;
			font-size: 1em;
		}
		.autocomplete {
			position: absolute;
			top: 224px;
			/*left: 500px;*/
			width: 498px;
			max-width: 100%;
			margin: 0 auto;
			margin-left: 40px;
			background-color: rgba(200, 200, 200, 0.5); /*#f5f5f5;*/
			padding: 10px;
			border-radius: 0 0 4px 4px;
			border-left: thin solid #fff;
			border-right: thin solid #fff;
			border-bottom: thin solid #fff;
			max-height: 180px;
			overflow-y: auto;
			list-style-type: none;
			text-align: justify;
			display: none;
			z-index: 2;
		}
		.autocomplete > li {
			background-color: #8d9db6;
			color: #fff;
			padding: 8px;
			margin-bottom: 5px;
			font-family: helvetica;
			margin-left: -10px;
			margin-right: -10px;
		}
		.autocomplete > li:hover {
			background-color: #bccad6;
			color: #777;
			cursor: pointer;
		}
		@media screen and (max-width: 580px) {
			.autocomplete {
				margin-left: 0px;
			}
		}
		.loader {
			color: #fff;
			font-size: 1.5em;
		}
		.close-locator {
			position: absolute;
			top: 0px;
			margin-left: 285px;
			background: -webkit-linear-gradient(#f5f5f5, #aaa);
			text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.3);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			font-weight: bold;
			cursor: pointer;
		}
		.close-locator:hover {
			text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.6);
		}
		.tracklist {
			position: relative;
			background: transparent;
			height: 250px;
			overflow-y: auto;
			z-index: 0;
		}
		.list-item {
			width: 95%;
			background-color: rgba(0, 0, 0, 0.6);
			color: #fff;
			border-radius: 4px;
			margin-bottom: 6px;
			padding: 10px 2px;
			text-indent: 3px;
			text-align: left;
			cursor: pointer;
		}
		.list-item > a {
			text-decoration: none;
			color: #fff;
		}
		.list-item:hover {
			background-color: rgba(150, 150, 150, 0.6);
			color: #fff;
		}
		.list-item:hover > .icon-play {
			color: #0b0;
		}
		.message-item {
			width: 95%;
			background-color: rgba(0, 0, 0, 0.6);
			color: #fff;
			border-radius: 4px;
			margin-bottom: 6px;
			padding: 10px 2px;
			text-indent: 3px;
			text-align: center;
		}
		.remove-item {
			position: relative;
			top: -8px;
			background-color: #e42;
			color: #fff;
			border-radius: 2px;
			padding: 2px 5px 2px 2px;
			font-size: 15px;
			cursor: pointer;
			float: right;
		}
		.remove-item:hover {
			background-color: #fff;
			color: #e42;
		}
		.slideOut {
		    animation: slide 2s ease forwards;
		}
		@keyframes slide {
		  	0%  { transform: translateX(0px) }
		  	100%{ transform: translateX(-600px); opacity:0; }
		}

	</style>
	<script type="text/javascript">
		$('#tracksearch').focus();
		$(document).ready(function() {
			$('#tracksearch').on('keyup', function() {
				var urldata = $('#tracksearch').val().trim();
				if(urldata == "") {
					return $('.autocomplete').hide();
				}
				$.ajax({
					type: "GET",
					url: "post_controller.php",
					data: {"getquery":"true","query":urldata},
					// dataType: "json",
					cache: false,
					beforeSend: function() {
						$('.autocomplete').html("<center><span class='loader fa fa-spinner fa-pulse fa-1x'></span></center>").show();
					},
					success: function(data) {
						// var jason = JSON.parse(data);
						$('.autocomplete').html(data).show();
					}
				});
			});
		});
		//on click item from loaded playlist run function to play selected track
		function actionSelect(mediatype, selectedTrack) {
			var medianame = selectedTrack.dataset.mediadata;
			var elem = document.getElementById('mediastream');
			if(mediatype == "mp3" || mediatype == "wma") { //if audio then add following element
				elem.innerHTML = "<span style='color:#10527B;font-family:arial;font-size: 1.2em;'><i class='fa fa-spinner fa-pulse'></i> loading media...</span>";
				setTimeout(function() {
					elem.innerHTML = '<audio controls="true" autoplay="false" preload="auto"><source src="mediauploads/'+medianame+'"/></audio>';
				}, 3000);
			}
			else if(mediatype == "mp4") { //if video then do following
				elem.innerHTML = '<video controls="true" autoplay="false" preload="auto"><source src="mediauploads/'+medianame+'"/></video>';
			}
			callMediaPlayer(); //call mediaplayer to open and load track
			$('.layer').html('').fadeOut(); //then hide element clear layer
		}
		//on select/click item from search/filter, run this
		function selectItem(trackname, mediatype) {
			$('.tracklist > ul').html('<li class="list-item" data-mediadata="'+trackname+'" onclick="actionSelect(\''+mediatype+'\',this)"><i class="fa fa-play"></i> '+trackname+'</li>');
			$('.autocomplete').html('').hide();
		}
		
		$('.close-locator').click(function() {
			$('.layer').fadeOut('fast'); //close the search/filter overlay window
		});
		//onclick remove icon from playlist, run function
		function removeItem(action) {
			var remove = confirm('Remove this item?');
		    if(remove == false) {
		    	return false;
		    }
			var userID = action.dataset.uid;
			var mediaID = action.dataset.mediaid;
			action.parentNode.classList.add("slideOut");
			setTimeout(function() {
				$.post("post_controller.php", {"playlistRemove":"true","uid":userID,"media_id":mediaID}, function(data) {
					var jasondata = JSON.parse(data);
					if(jasondata.message == "OK") {
						action.parentNode.remove();
						alert("Item removed successfully from playlist!");
					} else {
						alert("Failed to removed item from playlist, try again!");
					}
				});
			}, 2500);
		}
	</script>
</head>
<body>
	<div class="container-item popIn">
		<span class="fa fa-time fa-2x close-locator" title="Close">&times;</span>
		<h1 class="box-header">Filter myPlaylist</h1>
		<input type="text" name="tracksearch" id="tracksearch" class="input-control" autocomplete="off" placeholder="Type track name...">
		<ul class="autocomplete"></ul>
		<div class="tracklist">
			<ul>
				<?php if($tracklist) { foreach($tracklist as $trackname) : $info = pathinfo($trackname['media_url']); ?>
					<li class="list-item" data-mediadata="<?php echo $trackname['media_url']; ?>" ondblclick="actionSelect('<?php echo $info['extension']; ?>',this)">
						<i class="fa fa-play icon-play"></i> <?php echo $trackname['media_url'] = str_replace("_",  " ", $trackname['media_url']); ?>
						<i class="fa fa-close remove-item" title="remove from list" data-mediaid="<?php echo $trackname['media_id']; ?>" data-uid="<?php echo $trackname['user_id']; ?>" onclick="return removeItem(this);"></i>
					</li>
				<?php endforeach; } else { ?>
					<li class="message-item"><i class="fa fa-info-circle"></i> No tracks in playlist</li>
				<?php }  ?>
			</ul>
		</div>
	</div>
</body>
</html>