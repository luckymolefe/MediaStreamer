<?php

if(file_exists('model/post_model.php') && is_file('model/post_model.php')) {
	require_once('model/post_model.php');
} else {
	echo "Failed to load neccessary file!.";
}

if(isset($_REQUEST['publish']) && $_REQUEST['publish'] == "true") {
	$filename = $_FILES['mediafile']['name'];
	$title_description = htmlentities(stripslashes(strip_tags(trim($_REQUEST['description']))));
	$uid = (int)$_REQUEST['sess_uid'];

	$array = explode(".", $filename); //strip uploaded file info
	$name = $array[0]; //get file name
	$file_ext = strtolower($array[1]); //get file extension

	$filetypes = array('mp4', 'mp3', 'wma'); //array list of types image files allowed.

	if(in_array($file_ext, $filetypes) && !empty($title_description)) {
		if(!is_dir('mediauploads')) { //check if directory exists, if !Not
			mkdir('mediauploads'); //then create new directory
		}
		$path = "mediauploads/";
		$fullpath = $path.$filename;
		if($file_ext == 'mp4') { //validate extension
			if (@move_uploaded_file($_FILES['mediafile']['tmp_name'], $fullpath)) {
				if($post_model->postMedia($uid, $filename, $title_description)) {
					$response['message'] = "success";
				} else {
					$response['message'] = "failed";
				}
			}
			else {
				$response['message'] = "failed";
				unlink($_FILES['mediafile']['tmp_name']);
			}
		}
		elseif($file_ext == 'mp3') {
			if(@move_uploaded_file($_FILES['mediafile']['tmp_name'], $fullpath)) {
				if($post_model->postMedia($uid, $filename, $title_description)) {
					$response['message'] = "success";
				} else {
					$response['message'] = "failed";
				}
			}
			else {
				unlink($_FILES['mediafile']['tmp_name']);
				$response['message'] = "failed";
			}
		}
		else {
			$response['message'] = "invalid";
		}
	}
	else {
		$response['message'] = "invalid";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['getposts']) && $_REQUEST['getposts'] == "true") {
	$posts = $post_model->getPosts(); //get all post
	if($posts) {
      foreach ($posts as $post) :
      	$user = $model->readProfile($post['user_id']);
        $firstname = $user['firstname'];
        $username = $user['username'];
          $added = ($post_model->checkPlaylistItem($_SESSION['auth']['uid'], $post['id'])) ? 1 : '';
          $liked = ($post_model->checkReaction($_SESSION['auth']['uid'], $post['id'])) ? 1 : '';
          $comments = ($post_model->getComments($post['id'])) ? count($post_model->getComments($post['id'])) : '';
          $path_parts = pathinfo($post['media_url']); //get the parts of the file
          $ext = strtolower($path_parts["extension"]);  //now get the file extension of the file
          $post_date = $post_model->timeDiff($post['created']); //format date for post
          (!empty($username)) ? $username : null; //if username isset show username, else null
  	?>
	      <li class="item">
	        <span class="fa fa-user fa-4x user-icon"></span>
	        <div class="content">
	          <div class="user-name"><?php echo $firstname; ?></div>
	          <div class="nick-name">@<?php echo $username; ?> <strong class="pull-right" title="<?php echo $post['created']; ?>"><?php echo $post_date; ?></strong></div>
	          <div class="post-text"><?php echo $post['description']; ?></div>
	          <div class="media">
	            <span class="mediaFileName"></span>
	            <center>
	              <?php if($ext == "mp3" || $ext == "wma") { ?>
	              <audio controls preload>
	                <source class="web-audio" src="mediauploads/<?php echo $post['media_url']; ?>" type="audio/mp3">
	              </audio>
	              <?php } else { ?>
	              <video preload>
	                <source class="web-video" src="mediauploads/<?php echo $post['media_url']; ?>" type="video/mp4">
	              </video>
	              <?php } ?>
	            </center>
	          </div>
	          <div class="bottom-bar">
	          	<span class="comment-panel"></span>
	            <span class="fa fa-comment comment" title="comments" data-uid="<?php echo $_SESSION['auth']['uid']; ?>" data-mediaid="<?php echo $post['id']; ?>" onclick="return addComment(this);"><sub><?php echo $comments; ?></sub></span>
	            <span class="fa fa-retweet" title="retweet"><sub></sub></span>
	            <span class="fa fa-bars addtolist" title="playlist" data-url="<?php echo $post['media_url']; ?>" data-uid="<?php echo $_SESSION['auth']['uid']; ?>" data-mid="<?php echo $post['id']; ?>" onclick="return addToList(this)"><sub><?php echo $added; ?></sub></span>
	            
	            <span class="fa fa-heart pull-right liked" title="like this" data-uid="<?php echo $_SESSION['auth']['uid']; ?>" data-mid="<?php echo $post['id']; ?>" onclick="return react(this)"><sub><?php echo $liked; ?></sub></span>
	            <span class="fa fa-play pull-right" title="streamed"><sub></sub></span>
	          </div>
	        </div>
	      </li>
	      <script type="text/javascript">
	      	$(function() {
	      		$('sub').each(function() {
	      			( $(this).text() >= 1) ? $(this).parent().css('color','#5bc0de') : null;
	      		});
				$('audio, video').prop("volume", 0.3); //setting audio volume for both Video and Audio
				$('video').hover(function(event) {
					if( event.type === "mouseenter" ) {
					  $(this).attr('controls', 'controls');
					}
					else if(event.type === "mouseleave") {
					  $(this).removeAttr('controls');
					}
				});
			});
	      </script>
  	<?php
      endforeach;
    }
    else {
  	?>
      <div class='message-alert'><center><span class='fa fa-warning'></span> No media files posted yet!.</center></div>
  	<?php
    }
}

// $_REQUEST['listFeeds'] = "true";
if(isset($_REQUEST['listFeeds']) && $_REQUEST['listFeeds'] == "true") { //list feeds for mobileApp
	if($data = $post_model->getMobilePosts()) {
		$response['feeds'] = $post_model->getMobilePosts();
	}
	else {
		$response['feeds'] = "Failed to get feeds.";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['playlistAdd']) && $_REQUEST['playlistAdd'] == "true") {
	$user_id = (int)trim($_REQUEST['user_id']);
	$media_id = (int)trim($_REQUEST['media_id']);
	$media_url = htmlentities(stripslashes(strip_tags(trim($_REQUEST['media_url']))));
	if(!empty($user_id) || !empty($media_id) || !empty($media_url)) {
		if($post_model->addToPlaylist($media_id, $user_id, $media_url)) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "Already added to playlist";
		}
	}
	else {
		$response['message'] = "Sorry there is missing information.";
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['playlistRemove']) && $_REQUEST['playlistRemove'] == "true") {
	//call function to remove item from DB
	$uid = (int)$_REQUEST['uid'];
	$mediaId = (int)$_REQUEST['media_id'];
	if(!empty($uid) || !empty($mediaId)) {
		if($post_model->removePlaylistItem($uid, $mediaId)) {
			$response['message'] = "OK";
		}
		else {
			$response['message'] = "failed";
		}
	}
	echo json_encode($response);
	exit();
}

if(isset($_REQUEST['like']) && $_REQUEST['like'] == "true") {
	$uid = (int)trim($_REQUEST['user_id']);
	$mid = (int)trim($_REQUEST['media_id']);
	if(!empty($uid) && !empty($mid)) {
		if($post_model->addReaction($uid, $mid)) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "Already reacted to this post";
		}
	}
	else {
		$response['message'] = "Sorry there is missing piece of information.";
	}
	echo json_encode($response);
	exit();
}

if(isset($_GET['getquery']) && $_GET['getquery'] == "true") { //filtering playlist on search
	$query = htmlentities(stripslashes(strip_tags(trim($_GET['query'])))); //sanitize query data
	if(!empty($query)) {
		$search_word = strtolower($query); //convert to loweCase
		$search_word = str_replace(" ", "%", $search_word);
		$founditems = $post_model->searchTracks($search_word); //pass value as parameter
		// $data = "";
		if($founditems == true && count($founditems) > 0) {
			foreach($founditems as $row) :
				$row['media_url'] = strtolower(stripslashes($row['media_url']));
				$emphasizedWord = "<strong style='color:blue'>".$search_word."</strong>";
				$trackname = str_replace($search_word, $emphasizedWord, $row['media_url']);
				// $data = $trackname;
				// $data['response'] = $trackname;
				$selectTrack = strip_tags($trackname);
				$info = pathinfo($selectTrack);
				$file_ext = $info['extension'];
		?>	
			<li onclick="selectItem('<?php echo $selectTrack; ?>','<?php echo $file_ext; ?>');">
				<span class='fa fa-info-circle'></span> <?php echo  str_replace("_", " ", $trackname) ?>
			</li>
		<?php
			endforeach;
		}
		else {
			echo "<li><span class='fa fa-times-circle'></span> No match found!.</li>";
		}
	}
	else {
		echo "<li><span class='fa fa-warning'></span> Please type something!.</li>";
	}
	// echo json_encode($data);
	exit();
}

if(isset($_REQUEST['comment']) && $_REQUEST['comment'] == "true") {
	$uid = (int)$_REQUEST['uid'];
	$media_id = (int)$_REQUEST['media_id'];
	$comment_data = htmlentities(stripslashes(strip_tags(nl2br(trim($_REQUEST['comment_data'])))));

	if(!empty($comment_data) && !empty($_SESSION['auth']['token'])) {
		if($post_model->saveComment($uid, $media_id, $comment_data)) {
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

?>