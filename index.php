<?php
session_start();

if (isset($_SESSION['user_email'])) {

	header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
	exit();
}

?>


<html>
	<head>
		<meta charset="UTF-8">
     		<title>Post an item</title>
	   		<link rel="stylesheet" type="text/css" href="style.css">
 	</head>

 	<body>
 		<p class="topbar"> 
			<span class="logo">i-Exchange</span> 
			<span class="nav">
				<ul class="navbar">
					 <li class="navlink"><a class="nava" href="index.php">Home</a> 
					<li class="navlink"><a class="nava" href="login.html">Login</a>
					<li class="navlink"><a class="nava" href="signup.html">Signup</a>
					<!-- <li class="navlink"><a class="nava" href="profile.php">View Profile</a> -->
					<!-- <li class="navlink"><a class="nava" href="search.html">Search</a> -->
				</ul>
			</span>
		</p>

		<center>
			<h2>Welcome to i-Exchange!</h2>
		</center>
	</body>																								
</html>