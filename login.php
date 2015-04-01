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
  <title>Login</title>
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
		</span></p>

<center>
	<h2>Login to i-Exchange</h2>
</center>
<form id="login" action="#" method="post">
    <table align="center">
		<tr>
		<td width="100px">Email: </td>
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
			<input name="submitLogin" type="submit" value="Submit" />
		</center>
		<center>
			<p>Not a user? <a href="http://iexchange.web.engr.illinois.edu/signup.php">Sign up.</a> </p>
		</center>
</form>

 </body>
</html>