<?php
	if(file_exists('controller.php') && is_file('controller.php')) {
			require_once('controller.php');
	} else {
		echo "Failed to include neccessary file!.";
	}
	$model->page_protected();
	$_SESSION['auth']['uid'] = (isset($_SESSION['auth']['uid'])) ? $_SESSION['auth']['uid'] : null;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mediastreamer | Upload</title>
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<style type="text/css">
		.container-item {
			width: 600px;
			max-width: 100%;
			margin: 0 auto;
			margin-top: 80px;
			background: #fff;
			padding: 15px;
			border: thin solid #bce8f1;
			border-radius: 5px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
			font-family: helvetica, arial;
		}
		.heading {
			text-align: center;
			margin-top: -10px;
			font-size: 20px;
			color: #777;
			padding: 10px;
			/*text-align: center;*/
			border-radius: 5px 5px 0 0;
			margin: -15px -15px 10px -15px;
		}
		.form-control {
			width: 100%;
			margin-bottom: 12px;
		}
		.input-control {
			max-width: 100%;
			width: 100%;
			margin: 0 auto;
			padding: 10px 0 10px 0;
			border: thin solid #bbb;
			border-radius: 3px;
			text-indent: 5px;
			color: #777;
			font-size: 15px;
		}
		.input-control:focus {
			border-color: #66afe9;
			outline: 0;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
			      box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
		}
		.input-label {
			font-weight: bold;
			color: #777;
		}
		.btn-submit {
			width: 150px;
			color: #fff;
			background-color: #5bc0de;
			border-color: #46b8da;
			/*background: #146eb4; linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb); /*#c5d5cd*/
			color: #fff; /* #77a8a8;*/
			padding: 15px;
			border: thin solid #46b8da;
			border-radius: 3px;
			font-size: 0.98em;
			font-weight: bold;
		}
		.btn-submit.btn-block {
			width: 100%;
		}
		.btn-submit:hover {
			background: #4285f4; /*#ded;*/
			color: #fff; /*#77a8a8;*/
			cursor: pointer;
		}
		.btn-submit:active {
			background: #146eb4; /*#d9ecd0;*/
			color: #fff;
			border: thin solid #c5d5cd;
		}

		.btn-reset {
			width: 150px;
			background: #4285f4; /*linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb);*/ /*#d9ecd0;*/
			color: #fff; /*#77a8a8;*/
			padding: 15px;
			border: thin solid #d7d7d7;
			border-radius: 5px;
			font-weight: bold;
		}
		.btn-reset:hover {
			background: #146eb4;/*#ded;*/
			color: #fff; /*#77a8a8;*/
			cursor: pointer;
		}
		.btn-reset:active {
			background: transparent; /*#d9ecd0;*/
			color: #77a8a8;
			border: thin solid #c5d5cd;
		}
		hr {
			margin-top: 10px;
			margin-bottom: 15px;
			border: 0;
			border-top: 1px solid #eee;
		}
		.close {
			position: relative;
			left: 590px;
			top: -10px;
			color: #ccc;
			cursor: pointer;
			font-size: 1em;
			font-weight: bold;
		}
		.close:hover {
			color: #777;
		}
		#progress, #invalid, #failed, #success {
			color: #777;
			padding: 10px;
		}
		textarea {
			resize: none;
		}
	</style>
	<script type="text/javascript">
		function validateData() {
			var file_data = document.forms[0].mediafile.files[0];
			var title_description = document.forms[0].description.value;
			if(file_data == null) {
				alert("Please select file to upload!");
				return false;
			}
			if(title_description == null) {
				alert("Please type media title!");
				return false;
			}
		}
		$(function() {
			$('.close').click(function() {
				$('.layer').html('').fadeOut('slow');
			});
			$('#progress, #invalid, #failed, #success').hide();
			$('#publish').click(function() {
				var title_description = $('#description').val().trim();
				var file_data = document.forms[0].mediafile.files[0]; //[0].name;
				var sessuid = document.forms[0].sess_uid.value;

				var formdata = new FormData();
				formdata.append('publish', 'true');
				formdata.append('sess_uid', sessuid);
				formdata.append('mediafile', file_data);
				formdata.append('description', title_description);
				// console.log(file_data);

				$.ajax({
					url: "post_controller.php",
					type: "POST",
					data: formdata,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function() {
						$('#progress').addClass('alert-info').html("<center><span class='fa fa-spinner fa-pulse'></span><strong> Uploading media...</strong></center>").show();
					},
					success: function(data) {
						// alert(data);
						$('#progress').removeClass('alert-info').hide();
						var jason = JSON.parse(data);
						if(jason.message == "invalid") {
		          			$('#invalid').show();
			          	}
			          	if(jason.message == "failed") {
			          		$('#failed').show();
			          	}
						if(jason.message == "success") {
							$('#success').show();
							$('#description').val('');
							$('input[type=file]').val('');
							setFeedNotify(); //set notification status for your followers
							setTimeout(function(){
				          		$('.layer').hide();
				          	}, 2000);
						}
					},
					errror: function() {
						$('#progress').addClass('alert-danger').removeClass('alert-info').html("<center><span class='fa fa-warning'></span><strong>Error: 404 url not found.</strong></center>").show();
					}
				});
			});
		})
	</script>
</head>
<body>
	<div class="container-item popIn">
		<div type="button" class="close" title="Close"><span class="fa fa-close"></span></div> <!-- &times; -->
		<p class="heading">Media Upload</p>
		<hr>
		<form action="post_controller.php" method="POST" enctype="multipart/form-data">
			<div class="form-control">
				<textarea id="description" name="description" rows="3" class="input-control" placeholder="description title..." autofocus></textarea>
			</div>
			<div class="form-control">
				<input type="hidden" name="sess_uid" value="<?php echo $_SESSION['auth']['uid']; ?>">
				<label class="input-label"><small>(Acceptable media type formats MP3-Audio and MP4-Video only)</small></label>
				<input type="hidden" name="MAX_FILE_SIZE" value="50000000"><br> <!-- 2000000 -->
				<input type="file" class="input-control" name="mediafile">
			</div>
			<div class="form-control" align="center">
				<!-- <button type="reset" class="btn-reset">Clear</button> -->
				<button type="button" id="publish" name="publish" value="true" class="btn-submit btn-block" onclick="return validateData();"><span class="fa fa-cloud-upload"></span> Publish Post</button>
			</div>
		</form>
	
		<div>
			<div id="progress" class="alert"></div>
			<div id="success" class='alert alert-success'><center><span class='fa fa-check'></span> Media posted successfully!...</center></div>
			<div id="invalid" class='alert alert-danger'><center><span class='fa fa-warning'></span> Only MP4 video and MP3/WMA audio files accepted!.</center></div>
			<div id="failed" class='alert alert-danger'><center><span class='fa fa-warning'></span> Sorry failed to publish your post!.</center></div>
		</div>
	</div>
</body>
</html>