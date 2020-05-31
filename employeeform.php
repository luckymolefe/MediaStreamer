<?php

?>
<!DOCTYPE html>
<html>
<head>
	<title>Client Admin</title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styles/tabs.css">
	<script type="text/javascript" src="styles/tabs.js"></script>
	<style type="text/css">
		body {
			font-family: helvetica, arial;
			background: #d7d7d7;
		}
		.container-item {
			font-family: helvetica, arial;
			max-width: 100%;
			max-width: 800px;
			margin: 0 auto;
			margin-top: 50px;
			background: #f5f5f5;
			padding: 15px;
			border-radius: 5px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
		}
		.heading {
			text-align: center;
			margin-top: -10px;
			font-size: 20px;
			background-color: #146eb4;
			color: #eee;
			padding: 10px;
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
			border-radius: 5px;
			text-indent: 5px;
			color: #777;
			font-size: 15px;
			resize: none;
		}
		.input-label {
			font-weight: bold;
			color: #777;
		}
		.btn-submit {
			width: 150px;
			background: #146eb4; /*linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb);*/ /*#c5d5cd*/
			color: #fff; /* #77a8a8;*/
			padding: 12px;
			border: thin solid #d7d7d7;
			border-radius: 5px;
			font-weight: bold;
		}
		.btn-submit:hover {
			background: #4285f4; /*#ded;*/
			color: #fff; /*#77a8a8;*/
			cursor: pointer;
		}
		.btn-submit:active {
			background: transparent; /*#d9ecd0;*/
			color: #77a8a8;
			border: thin solid #c5d5cd;
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
	</style>
</head>
<body>
	<nav id="navsite">
		<ul>
			<li><a id="current" href="">Personal Info</a></li>
			<li><a href="javascript:void(0)">Contact details</a></li>
			<li><a href="javascript:void(0)">Employment</a></li>
			<li><a href="javascript:void(0)">Educational</a></li>
			<li class="active"><a href="javascript:void(0)" onclick="toggleMenu()">Admin <i class="fa fa-caret-down"></i></a></li>
			<span class="drop-down" id="drop-menu">
				<div><a href="javascript:void(0)">Settings</a></div>
				<div><a href="javascript:void(0)">Logout</a></div>
			</span>
		</ul>
	</nav>
	<div class="container-item">
		<p class="heading">Employee Personal Info</p>
		<form action="contactdetails.php" method="POST" enctype="application/forms-url-encoded">
			<div class="form-control">
				<label class="input-label">Employee Firstname:</label>
				<input type="hidden" name="emp_num" value="">
				<input type="text" class="input-control" name="firstname" placeholder="Enter firstname">
			</div>
			<div class="form-control">
				<label class="input-label">Employee Lastname:</label>
				<input type="text" class="input-control" name="lastname" placeholder="Enter lastname">
			</div>
			<div class="form-control">
				<label class="input-label">Previous Lastname:</label>
				<input type="text" class="input-control" name="prev_lastname" placeholder="Enter previous lastname">
			</div>
			<div class="form-control">
				<label class="input-label">Date of Birth:</label>
				<input type="text" class="input-control" name="dob" placeholder="Enter date of birth">
			</div>
			<div class="form-control">
			<div>
				<button type="reset" class="btn-reset">Clear</button>
				<button type="submit" class="btn-submit">Next</button>
			</div>
		</form>
	</div>
</body>
</html>