<?php
session_start();

if (isset($_SESSION['user_email'])) { # if already logged in, redirect to profile.php

	header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
	exit();
}

if (isset($_POST['submitLogin'])) {
	include 'dbconnect.php';

	$em = $_POST['email'];
	$pw = $_POST['password'];

	# need to check that no fields are blank. If any of them are, display error message and redirect to signup.php
	if (empty($em) OR empty($pw)) {
	
		# redirect to login.php. To improve this, use JS.
		header("Location: http://iexchange.web.engr.illinois.edu/login.php");
		exit();
	}

	# sanitation checks complete. Simply retrieve user row and validate.
	$retrieve = mysql_query("SELECT * from users WHERE email=('$em') AND password=('$pw')");

	if (!$retrieve) {
		die('Something bad happened while fetching user info. ' . mysql_error());
	}

	# if we retrieved only one row, we're good. Then use mysql_array or something to get the user
	# and redirect to the user's profile page

	$numrows = mysql_num_rows($retrieve);

	if ($numrows == 0) { # user not found, or wrong email/pw combo. Redirect to login page.

		header("Location: http://iexchange.web.engr.illinois.edu/login.php");
		exit();
	}

	if ($numrows > 1) { # this should not happen!

		header("Location: http://iexchange.web.engr.illinois.edu/login.php");
		exit();
	}

	# sanitation checks complete. Now fetch user entries (ID/name/email) and redirect to profile page.
	$row = mysql_fetch_assoc($retrieve);
	$user_email = $row['email'];
	$pass = $row['password'];

	# VERY IMPORTANT: set session username for login! :)
	$_SESSION["user_email"] = $user_email;
	$_SESSION["password"] = $pass;

	mysql_close();

	# done, redirect to user's profile page.
	header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
	exit();

}

?>

<html>
 <head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Login</title>
  	<!-- jQuery Libraries -->
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
 </head>

 <body>
 	
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="index.php">iExchange</a>
	    </div>
	    <div>
	      <ul class="nav navbar-nav">
	        <li><a href="index.php">Home</a></li>
	        <li class="active"><a href="login.php">Login</a></li>
	        <li><a href="signup.php">Signup</a></li>
	        <li><a href="profile.php">Profile</a></li>
	        <li><a href="search.php">Search</a></li>
	      </ul>
	    </div>
	  </div>
    </nav>
<div class="container">
	<center>
		<h3>Login to i-Exchange</h3>
		<br>
	</center>
	<form id="login" action="#" method="post" class="form-horizontal">
	    <div class="form-group">
	      <div class="col-sm-3">
	      </div>
	      <label class="col-sm-2 control-label"></label>
	      <div class="col-sm-2">
	        <input class="form-control" name="email" type="text" placeholder="Email">
	      </div>
	      <div class="col-sm-5">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-sm-3">
	      </div>
	      <label class="col-sm-2 control-label"></label>
	      <div class="col-sm-2">
	        <input class="form-control" name="password" type="password" placeholder="Password">
	      </div>
	      <div class="col-sm-5">
	      </div>
	    </div>
		<br>
			<center>
				<input class="btn btn-info" name="submitLogin" type="submit" value="Login" />
			</center>
			<br>
			<center>
				<p>Not a user? <a href="http://iexchange.web.engr.illinois.edu/signup.php">Sign up.</a> </p>
			</center>
	</form>
</div>
 </body>
</html>