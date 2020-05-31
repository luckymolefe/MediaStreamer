<?php

?>
<!DOCTYPE html>
<html>
<head>
	<title>Mediastreamer | Register</title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>
	<style type="text/css">
		.panel {
			margin-top: 50px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
		}
		.form-control {
			border-radius: 2px;
		}
		.btn {
			border-radius: 2px;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('#progress, #invalid, #failed').hide();
			$('#hiddenKey').val('');

			$('#signup').click(function() {
				if($('input[type="text"]').val() == "") {
					return alert("Please enter firstname and lastname!");
				}
				if($('input[type="email"]').val() == "" && $('input[type="password"]').val() == "") {
					return alert("Please enter email and password!");
				}
				if($('#password').val() != $('#passwordConfirm').val()) {
					return alert("Your password do not match!");
				}
				if($('#captcha_confirm').val().trim() == "") {
					return alert("Please enter captcha code!");
				}
				if($('#hiddenKey').val().trim() != $('#captcha_confirm').val().trim()) {
					return alert("Invalid captcha code, try again!");
				}
				var firstname, lastname, email, password, passwordConfirm;
				firstname = $('#firstname').val().trim();
				lastname = $('#lastname').val().trim();
				email = $('#email').val().trim();
				password = $('#password').val().trim();
				passwordConfirm = $('#passwordConfirm').val().trim();
		        $.ajax({
		          type: "POST",
		          url: "controller.php",
		          data: {"signup":"true", "firstname":firstname, "lastname":lastname, "email":email, "password":password, "password_confirm":passwordConfirm},
		          cache: false,
		          beforeSend: function() {
		            $('#progress').addClass('alert-info').html("<center><span class='fa fa-spinner fa-pulse'></span><strong> Signing up...</strong></center>").show();
		          },
		          success: function(data) {
		          	$('#progress').removeClass('alert-info').hide();
		          	var jason = JSON.parse(data);
		          	if(jason.message == "invalid") {
		          		$('#invalid').show();
		          	}
		          	if(jason.message == "failed") {
		          		$('#failed').show();
		          	}
		          	if(jason.message == "success") {
		          		window.open(jason.inbox, '_blank');
			          	window.open(jason.url, '_self');
			        }
		          },
		          error: function() {
		            $('#progress').addClass('alert-danger').removeClass('alert-info').html("<center><span class='fa fa-warning'></span><strong>Error: 404 url not found.</strong></center>").show();
		          }
		        });
		    });
			//onPageLoad re-load captcha code
			$.post("controller.php", {"getCode":"true"}, function(loadData) {
				$('#captchaCode').html('<i class="fa fa-spinner fa-pulse"></i> Loading...');
				setTimeout(function () {
		    		var jsonData = JSON.parse(loadData);
		    		$('#captchaCode').html(jsonData['url']);
		    		$('#hiddenKey').val(jsonData['keyGen']);
	    		}, 2000);
	    	}); //load captcha keyCode, on page load

			//onclick reload captcha code
	    	$('#refresh').click(function() {
	    		$('#hiddenKey').val('');
	    		$('#captchaCode').html('<i class="fa fa-spinner fa-pulse"></i> Loading...');
	    		$.post("controller.php", {"getCode":"true"}, function(loadData) {
					// setTimeout(function () {
			    		var jsonData = JSON.parse(loadData);
			    		$('#captchaCode').html(jsonData['url']);
			    		$('#hiddenKey').val(jsonData['keyGen']);
		    		// }, 2000);
		    	}); //load captcha keyCode, on page load
	    	});
		});
	</script>
</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-info">
						<div class="panel-body">
							<div class="lead text-center">REGISTER</div>
							<hr>
							<form action="" method="POST" enctype="application/forms-url-encoded">
								<div class="form-group">
									<input type="text" class="form-control input-lg" id="firstname" name="firstname" placeholder="firstname" autofocus>
								</div>
								<div class="form-group">
									<input type="text" class="form-control input-lg" id="lastname" name="lastname" placeholder="lastname">
								</div>
								<div class="form-group">
									<input type="email" class="form-control input-lg" id="email" name="email" placeholder="email">
								</div>
								<div class="form-group">
									<input type="password" class="form-control input-lg" id="password" name="password" placeholder="password">
								</div>
								<div class="form-group">
									<input type="password" class="form-control input-lg" id="passwordConfirm" name="password_confirm" placeholder="confirm password">
								</div>
								<div class="form-group">
									<center>
										<label class="visible-xs visible-sm">Captcha Code:</label><!-- class="visible-xs visible-sm" -->
										<span id="captchaCode"></span>
										<input type="hidden" name="capt_gen" id="hiddenKey">
										<button type="button" id="refresh" title="reload" class="btn btn-sm btn-primary"><span class="fa fa-refresh"></span></button>
										<input type="text" id="captcha_confirm" class="form-control input-lg" name="captcha_confirm" placeholder="Enter captcha code here...">
									</center>
								</div>
								<!-- <div class="form-group">
									<input type="text" class="form-control input-lg" name="captcha_confirm" placeholder="Enter captcha code here...">
								</div> -->
								<div class="form-group">
									<button type="button" id="signup" name="signup" value="true" class="btn btn-lg btn-block btn-info">Signup</button>
								</div>
								<hr>
								<div class="form-group">
									<div class="text-center">
										<strong>Already have an account?</strong> | 
										<a href="login">Login here</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				  <div id="progress" class="alert"></div>
				  <div id="invalid" class='alert alert-danger'><center><span class='fa fa-warning'></span> Password does not match!.</center></div>
				  <div id="failed" class='alert alert-danger'><center><span class='fa fa-warning'></span> Sorry failed to register details!.</center></div>
				</div>
			</div>
		</div>
	</body>
</html>