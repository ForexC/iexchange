<?php
session_start();

if (isset($_SESSION['user_email'])) { # if already logged in, redirect to profile.php

	header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
	exit();
}

if (isset($_POST['submitSignup'])) {

	include 'dbconnect.php';

	$nm = $_POST['name'];
	$em = $_POST['email'];
	$pw = $_POST['password'];

	# need to check that no fields are blank! If any of them are, display error message and redirect to signup.php
	if (empty($nm) OR empty($em) OR empty($pw)) {

		# redirect to signup.php here. Replace with JS soon.
		header("Location: http://iexchange.web.engr.illinois.edu/signup.php");
		exit();
	}

	# check illinois email
	#$regex = "^[a-zA-Z0-9_.+-]+([@illinois.edu])+$"; 
	$regex = "/[a-zA-Z0-9_.+-]+(@illinois.edu)/";

	if (!preg_match($regex, $em)) {

		# prompt user and redirect to signup.php
		header("Location: http://iexchange.web.engr.illinois.edu/signup.php");
		exit();
	}

	# validate whether a user with the same email already exists.
	$check_dup = mysql_query("SELECT * from users WHERE email=('$em')");

	# ensure that query was successful
	if (!$check_dup) {
		die('Something bad happened while validating email. ' . mysql_error());
	}

	$numrows = mysql_num_rows($check_dup);

	if ($numrows > 0) { # should ideally check == 1
		# prompt that email already exists and redirect to signup.html
		die('This should not happen.');
		#header("Location: http://www.iexchange.web.engr.illinois.edu/signup.html");
		exit();
	}

	# sanitation checks complete. Now insert into DB
	$insert = mysql_query("INSERT INTO users (name, email, password) VALUES ('$nm', '$em', '$pw')");

	if (!$insert) {
		die('Something bad happened while adding user. ' . mysql_error());
	}

	# Now redirect to login page.
	mysql_close();
	header("Location: http://iexchange.web.engr.illinois.edu/login.php");
	exit();
	}

?>

<html>
 <head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Signup</title>
  			<!-- jQuery Libraries -->
	   		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	   		<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
 </head>

 <body>
	<!-- topbar -->
 	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="index.php">iExchange</a>
	    </div>
	    <div>
	      <ul class="nav navbar-nav">
	        <li><a href="index.php">Home</a></li>
	        <li><a href="login.php">Login</a></li>
	        <li class="active"><a href="signup.php">Signup</a></li>
	        <li><a href="profile.php">Profile</a></li>
	        <li><a href="search.php">Search</a></li>
	      </ul>
	    </div>
	  </div>
    </nav>
<div class="container">
	<center>
		<h2>Signup for i-Exchange</h2>
	</center>
	<form id="signup" action="#" method="post" class="form-horizontal">
		 <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Name</label>
		      <div class="col-sm-2">
		        <input class="form-control" name="name" type="text">
		      </div>
		      <div class="col-sm-5">
		      </div>
	    </div>
	    <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Email</label>
		      <div class="col-sm-2">
		        <input class="form-control" name="email" type="text">
		      </div>
		      <div class="col-sm-5">
	      </div>
	    </div>
	    <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Passsword</label>
		      <div class="col-sm-2">
		        <input class="form-control" name="password" type="password">
		      </div>
		      <div class="col-sm-5">
		      </div>
	    </div>
		<br>
		<center>
			<input class="btn btn-info" name="submitSignup" type="submit" value="Sign in" />
		</center>
	</form>
</div>
</body>
</html>
