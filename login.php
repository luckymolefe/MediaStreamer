<?php

?>
<!DOCTYPE html>
<html>
<head>
	<title>Mediastreamer | Login</title>
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
		.text-muted:hover {
			color: #555;
		}
		/*test bar loading*/
		#loader {
			position: absolute;
			top: 120%;
			left: 40%;
			display: none;
			color: #46b8da;
		}
		.loading {
			width: 400px; /*set size of loader-bar length*/
			max-width: 85vw;
			height: 4px;
			position: absolute;
			bottom: 20vh;
			left: 50%;
			border-radius: 4px;
			background: #ddd; /*rgba(100, 100, 100, 0.5);*/
			transform: translate(-50%, -50%);
			overflow: hidden;
			display: none;
			font-family: arial, helvetica;
		}
		.loading:after {
			content: '';
			display: block;
			width: 1px; /*control the size of a loading bar starting point to end point*/
			height: 4px;
			background: #46b8da; /*#4285fa;*/ /*#ccc;*/
			animation: load 15s linear;
		}
		@keyframes load {
			0% {
				width: 0;
			}
			10% {
				width: 5%;
			}
			20% {
				width: 15%;
			}
			30% {
				width: 25%;
			}
			40% {
				width: 30%;
			}
			50% {
				width: 44%;
			}
			60% {
				width: 50%;
			}
			70% {
				width: 72%;
			}
			80% {
				width: 84%;
			}
			90% {
				width: 92%;
			}
			100% {
				width: 100%;
			}
		}
		.circle_loader {
 			position: absolute;
 			top: 45%;
 			left: 50%;
 			transform: translate(-50%, -50%);
 			width: 100px;
 			height: 100px;
 			border-radius: 50%;
 			border: 10px solid #46b8da; /* #10527B , #10325E, #46b8da */
 			border-top: 10px solid #10527B;
 			animation: animate 0.8s infinite linear; 
 		}
 		@keyframes animate {
 			0% {
 				transform: translate(-50%, -50%) rotate(0deg);
 			}
 			100% {
 				transform: translate(-50%, -50%) rotate(360deg);
 			}
 		}
	</style>
	<script type="text/javascript">
		$(function() {

			$('.alert').hide();
			$('#login').click(function() {
				if($('input[type="email"]').val().trim() == "" || $('input[type="password"]').val().trim() == "") {
					return alert("Please enter email and password!");
				}
				if($('#remember').is(':checked')) {
					var rememberUser = "on";
				} else {
					var rememberUser = "off";
				}
				var email = $('input[type="email"]').val();
				var password = $('input[type="password"]').val();
		        $.ajax({
		          type: "POST",
		          url: "controller.php",
		          data: {"login":"true", "email":email.trim(), "password":password.trim(), 'remember':rememberUser.trim()},
		          cache: false,
		          beforeSend: function() {
		            $('.alert').addClass('alert-info').html("<center><span class='fa fa-spinner fa-pulse'></span><strong> Logging-in...</strong></center>").show();
		            $('.alert').removeClass('alert-danger');
		          },
		          success: function(data) {
		          	var jason = JSON.parse(data);
		          	if(jason.message == "verify") {
		          		$('.alert').removeClass('alert-info').hide();
		          		$('body').html('').addClass('circle_loader');
			            return window.open(jason.url,'_self');
			        }
			        if(jason.message == "success") {
			        	$('.alert').removeClass('alert-info').hide();
			        	$('body').html('').addClass('circle_loader');
		        		return window.open(jason.url,'_self');
			        }
			        if(jason.message == "invalid") {
			        	$('.alert').removeClass('alert-info');
			        	$('.alert').addClass('alert-danger').html("<center><span class='fa fa-warning'></span><strong> Invalid login credentials!.</strong></center>").show();
			        }
		          },
		          error: function() {
		            $('.alert').addClass('alert-danger').removeClass('alert-info').html("<center><span class='fa fa-warning'></span> <strong>Error: 404 url not found.</strong></center>").show();
		          }
		        });
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
							<div class="lead text-center">LOGIN</div>
							<hr>
							<form action="" method="" enctype="">
								<div class="form-group">
									<input type="email" id="email" class="form-control input-lg" name="email" value="<?php (!empty($_COOKIE['email'])) ? print $_COOKIE['email'] : print''; ?>" placeholder="email">
								</div>
								<div class="form-group">
									<input type="password" id="password" class="form-control input-lg" name="password" value="<?php (!empty($_COOKIE['password'])) ? print $_COOKIE['password'] : print''; ?>" placeholder="password">
								</div>
								<div class="form-group">
									<label class="checkbox-inline">
										<input type="checkbox" name="remember_me" <?php if(!empty($_COOKIE['email'])) { ?> checked <?php } ?> id="remember"> <strong class="text-muted">Remember Me</strong>
									</label>
								</div>
								<div class="fomr-group">
									<button type="button" id="login" class="btn btn-lg btn-block btn-info">Login</button>
								</div>
								<hr>
								<div class="form-group">
									<strong>Not registered yet?</strong> |
									<a href="signup">Register here</a>
									<span style="float:right"><a href="reset">Forgot password</a></span>
								</div>
							</form>
						</div>
					</div>
					<div class="alert"></div>
					<!-- <div id="loader">Loading profile...</div> -->
				</div>
				<!-- <div class="loading"></div> -->
			</div>
		</div>
</body>
</html>