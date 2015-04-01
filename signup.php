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
  <title>Signup</title>
  <link rel="stylesheet" type="text/css" href="style.css">
 </head>

 <body>
	<p class="topbar"> 
        <span class="logo">i-Exchange</span> 
		<span class="nav">
			<ul class="navbar">
				<li class="navlink"><a class="nava" href="index.php">Home</a>
				<li class="navlink"><a class="nava" href="login.php">Login</a>
				<li class="navlink"><a class="nava" href="signup.php">Signup</a>
				<li class="navlink"><a class="nava" href="profile.php">View Profile</a>
				<li class="navlink"><a class="nava" href="search.html">Search</a>
			</ul>
			</span>
	</p>
<center>
	<h2>Signup for i-Exchange</h2>
</center>
<form id="signup" action="#" method="post">
    <table align="center">
	        <tr>
			<td width="100px">Name: </td>
			<td>
				<input type="text" name="name" />
			</td></tr>
	        <tr>
			<td>Email: </td>
			<td>
				<input type="text" name="email" />
			</td></tr>
			<tr>
			<td>Password: </td>
			<td>
				<input type="password" name="password" />
			</td></tr>
	</table>
	<br>
	<center>
		<input name="submitSignup" type="submit" value="Submit" />
	</center>
</form>

 </body>
</html>
