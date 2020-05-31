<?php
	if(file_exists('controller.php') && is_file('controller.php')) {
		require_once('controller.php');
	} else {
		echo "Failed to include neccessary file!.";
	}
	$model->page_protected();
	$row = $model->readProfile($_SESSION['auth']['uid']);
	$row = (isset($_SESSION['auth']['uid'])) ? $model->readProfile($_SESSION['auth']['uid']) : null;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mediastreamer | Profile</title>
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<!-- // <script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script> -->
	<!-- // <script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script> -->
	<style type="text/css">
		/*.panel {
			margin-top: 50px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
		}
		.form-control {
			border-radius: 3px;
		}
		.btn {
			border-radius: 3px;
		}
		.lead {
			color: #777;
		}*/
		/*custom css*/
		.container-item {
			font-family: helvetica, arial;
			width: 600px;
			max-width: 100%;
			margin: 0 auto;
			margin-top: 50px;
			background: #fff;
			padding: 15px;
			border: thin solid #bce8f1;
			border-radius: 5px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
		}
		.heading {
			text-align: center;
			margin-top: -10px;
			font-size: 20px;
			/*background-color: #146eb4;*/
			color: #777;
			padding: 10px;
			border-radius: 5px 5px 0 0;
			margin: -15px -15px 10px -15px;
		}
		.form-control {
			width: 100%;
			margin-bottom: 12px;
		}
		.input-control:focus {
			border-color: #66afe9;
			outline: 0;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
			      box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
		}
		.input-control {
			max-width: 100%;
			width: 100%;
			margin: 0 auto;
			padding: 15px 0 10px 0;
			border: thin solid #bbb;
			border-radius: 3px;
			text-indent: 10px;
			color: #555;
			font-size: 0.98em;
			font-weight: bold;
			resize: none;
		}
		.input-label {
			font-weight: bold;
			color: #777;
		}
		.btn-submit {
			width: 150px;
			/*background: #146eb4; linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb); /*#c5d5cd*/
			/*color: #fff;  #77a8a8;*/
			padding: 15px;
			border: thin solid #46b8da;
			border-radius: 3px;
			font-size: 0.98em;
			font-weight: bold;
			color: #fff;
			background-color: #5bc0de;
			border-color: #46b8da;
		}
		.btn-submit:hover {
			background: #4285f4; /*#ded;
			color: #fff; /*#77a8a8;*/
			cursor: pointer;
		}
		.btn-submit:active {
			background: #146eb4; /*#d9ecd0;*/
			color: #fff;
			border: thin solid #c5d5cd;
		}
		.btn-submit.btn-block {
			width: 100%;
		}

		.btn-reset {
			width: 150px;
			background: #146eb4; /*linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb);*/ /*#d9ecd0;*/
			color: #fff; /*#77a8a8;*/
			padding: 12px;
			border: thin solid #d7d7d7;
			border-radius: 5px;
			font-weight: bold;
		}
		.btn-reset:hover {
			background: #4285f4;/*#ded;*/
			color: #fff; /*#77a8a8;*/
			cursor: pointer;
		}
		.btn-reset:active {
			background: transparent; /*#d9ecd0;*/
			color: #77a8a8;
			border: thin solid #c5d5cd;
		}
		hr {
			margin-top: 20px;
			margin-bottom: 20px;
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
			/*float: right;*/
		}
		.close:hover {
			color: #777;
		}
		#progress, #invalid, #failed, #success {
			color: #777;
			padding: 10px;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('#progress, #invalid, #failed, #success').hide();
			$('#update').click(function() {
				if($('input[type="text"]').val() == "") {
					return alert("Please enter firstname and lastname!");
				}
				if($('input[type="email"]').val() == "") {
					return alert("Please enter your email address!");
				}
				var firstname, lastname, username, email, uid;
				uid = $('#sess_uid').val().trim();
				firstname = $('#firstname').val().trim();
				lastname = $('#lastname').val().trim();
				username = $('#username').val().trim();
				email = $('#email').val().trim();
		        $.ajax({
		          type: "POST",
		          url: "controller.php",
		          data: {"profileupdate":"true","sess_uid":uid, "firstname":firstname, "lastname":lastname, "username":username, "email":email},
		          cache: false,
		          beforeSend: function() {
		            $('#progress').addClass('alert-info').html("<center><span class='fa fa-spinner fa-pulse'></span><strong> Updating...</strong></center>").show();
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
			          	setTimeout(function(){
			          		$('.layer').hide();
			          	}, 5000);
			        }
		          },
		          error: function() {
		            $('#progress').addClass('alert-danger').removeClass('alert-info').html("<center><span class='fa fa-warning'></span><strong>Error: 404 url not found.</strong></center>").show();
		          }
		        });
		    });
			$('.close').click(function() {
				$('.layer').html('').fadeOut('slow');
			});
		});
	</script>
</head>
<body>

	<div class="container-item popIn">
		<div type="button" class="close" title="Close"><span class="fa fa-close"></span></div> <!-- &times; -->
		<p class="heading"><span class="fa fa-user-circle fa-3x"></span><br>Profile Settings</p>
		<hr>
		<form action="contactdetails.php" method="POST" enctype="application/forms-url-encoded">
			<div class="form-control">
				<!-- <label class="input-label">Employee Firstname:</label> -->
				<input type="hidden" id="sess_uid" name="sess_uid" value="<?php echo $_SESSION['auth']['uid']; ?>">
				<input type="text" class="input-control" id="firstname" name="firstname" value="<?php echo $row['firstname']; ?>" placeholder="firstname" autofocus>
			</div>
			<div class="form-control">
				<!-- <label class="input-label">Employee Lastname:</label> -->
				<input type="text" class="input-control" id="lastname" name="lastname" value="<?php echo $row['lastname']; ?>" placeholder="lastname">
			</div>
			<div class="form-control">
				<!-- <label class="input-label">Employee Lastname:</label> -->
				<input type="text" class="input-control" id="username" name="username" value="<?php echo $row['username']; ?>" placeholder="username">
			</div>
			<div class="form-control">
				<!-- <label class="input-label">Email:</label> -->
				<input type="text" class="input-control" id="email" name="email" value="<?php echo $row['email']; ?>" placeholder="email">
			</div>
			<div class="form-control">
			<div>
				<button type="button" id="update" name="update" value="true" class="btn-block btn-submit"> <span class="fa fa-refresh"></span> Update</button>
			</div>
		</form>
	</div>
	<div>
		<div id="progress" class="alert"></div>
		<div id="success" class='alert alert-success'><center><span class='fa fa-check'></span> Profile updated successfully!...</center></div>
		<div id="invalid" class='alert alert-danger'><center><span class='fa fa-warning'></span> Please fill all required fields.</center></div>
		<div id="failed" class='alert alert-danger'><center><span class='fa fa-warning'></span> Sorry failed to update profile details!.</center></div>
	</div>
</body>
</html>