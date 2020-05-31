<?php

//OTP code validation form
if(file_exists('controller.php') && is_file('controller.php')) {
	require_once('controller.php');
} else {
	echo "Failed to include neccessary file!.";
}
$model->page_protected();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Verification</title>
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
		.layer {
			position: absolute;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			color: #fff;
			text-align: center;
			font-family: arial, helvetica;
			font-size: 2.5em;
			z-index: 999;
			display: none;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('#null, #invalid, #success').hide();
			//check if user account already verified
			$.get("controller.php", {"isVerified":"true", "token": $('#token').val().trim()}, function(data) {
				var jason = JSON.parse(data);
				if(jason.message == "true") {
					return window.open(jason.url, '_self');
				}
			});

			$('#verify').click(function() {
				$('#null, #invalid').hide();
				var token = $('#token').val().trim();
				var vrCode = $('#verification_code').val().trim();
				if(vrCode == "") {
					$('#null').show();
					return alert("Please type your verification code!");
				}
				$.ajax({
					type: "POST",
					url: "controller.php",
					data: {"verify":"true", "token":token, "verification_code":vrCode},
					cache: false,
					beforeSend: function() {
						$('#verify').attr('disabled', true);
			            $('.layer').html("<div style='margin-top:150px;'><span class='fa fa-spinner fa-pulse fa-5x'></span><br>Verifying code...</div>").show();
			        },
			        success: function(data) {
			        	var jason = JSON.parse(data);
			        	if(jason.message == "success") {
			        		$('#success').show();
			        		window.open(jason.url, '_self');
			        	}
			        	if(jason.message == "invalid") {
			        		$('#invalid').show();
			        		$('.layer').hide();
				        	$('#verify').attr('disabled', false);
			        	}
			        	if(jason.message == "null") {
			        		$('#null').show();
			        		$('.layer').hide();
				        	$('#verify').attr('disabled', false);
			        	}
			        },
			        error: function() {
			        	$('#verify').attr('disabled', false);
			        	$('.layer').html("<div style='margin-top:150px;'><span class='fa fa-warning'></span><strong>Error: 404 url not found.</strong></div>").show();
			        }
				});
			});

		});
		function confirmAction(message) {
			var action = confirm(message);
			if(action == false) {
				return false;
			}
		}
	</script>
</head>
<body>
	<div class="layer"></div>
	<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-info">
						<div class="panel-body">
							<div class="lead text-center">Verification</div>
							<hr>
							<form action="" method="POST" enctype="application/forms-url-encoded">
								<div class="form-group">
									<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['auth']['token']; ?>">
									<input type="text" id="verification_code" class="form-control input-lg" name="verification_code" placeholder="Enter validation code here">
								</div>
								<div class="fomr-group">
									<button type="button" id="verify" class="btn btn-lg btn-block btn-info"  name="verify" value="true">Verify</button>
								</div>
								<hr>
								<div class="form-group">
									<div class="text-center">
										<strong>Resend verification code?</strong> | 
										<a href="controller.php?resend=true&amp;token=<?php echo $_SESSION['auth']['token']?>" id="resend" onclick="return confirmAction('Resend code?');">Resend code</a>
										<br><br>
										<a href="controller.php?signout=true&amp;token=<?php echo $_SESSION['auth']['token']?>" onclick="return confirmAction('Want to logout?');">Logout <span class="fa fa-sign-out"></span></a>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php if(isset($_GET['action']) && $_GET['action'] == "success") : ?>
					<div class='alert alert-success'><center><span class='fa fa-check'></span> Verification code sent!.</center></div>
					<?php endif; ?>
					<div id="invalid" class='alert alert-danger'><center><span class='fa fa-warning'></span> Verification code is invalid!.</center></div>
				  	<div id="null" class='alert alert-danger'><center><span class='fa fa-warning'></span> Please type your verification code!.</center></div>
				  	<div id="success" class='alert alert-success'><center><span class='fa fa-check'></span> Account verification successful!.</center></div>
				</div>
			</div>
		</div>
</body>
</html>