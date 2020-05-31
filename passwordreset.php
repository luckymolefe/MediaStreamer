<?php


?>
<!DOCTYPE html>
<html>
<head>
	<title>Password Reset</title>
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
			border-radius: 3px;
		}
		.btn {
			border-radius: 3px;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-info">
					<div class="panel-body">
						<form action="" method="" enctype="">
							<hr>
							<div class="form-group">
								<label class="form-label text-muted">Type your email address:</label>
								<input type="email" class="form-control input-lg" name="emailAddr" placeholder="Enter your email" autofocus>
							</div>
							<div class="form-group" align="right" >
								<button class="btn btn-lg btn-info">Reset Password</button>
							</div>
							<div class="form-group"><a href="login">&larr; Return to login</a></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>