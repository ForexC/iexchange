<?php
session_start();

if (!isset($_SESSION["update_id"])) { # update not active in profile.php. Redirect to profile.php

	header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
	exit();
}

?>

<html>
 <head>
  <meta charset="UTF-8">
  <title>Update an item</title>
  <link rel="stylesheet" type="text/css" href="style.css">
 </head>

 <body>
	<p class="topbar">
        <span class="logo">i-Exchange</span>
		<span class="nav">
			<ul class="navbar">
				<li class="navlink"><a class="nava" href="index.html">Home</a>
				<li class="navlink"><a class="nava" href="login.html">Login</a>
				<li class="navlink"><a class="nava" href="signup.html">Signup</a>
				<li class="navlink"><a class="nava" href="profile.php">View Profile</a>
			</ul>
			</span>
	</p>
<center>
<h2>Update an Item</h2>

<form action="#" method="post" /> <!-- TODO. Use session variable to keep track of user. Insert successful: redirect to user's profile page.-->
<p>Title: <input type="text" name="title" /> </p>
<p>Category: <select name="dropdown">

	<option value="Apparel">Apparel</option>
	<option value="Computers">Computers</option>
	<option value="Books">Books</option>
	<option value="Accessories">Accessories</option>
	<option value="Misc.">Misc.</option>
</select>

</p>
<p>Price: $<input type="number" step="0.01" name="price" /> </p>
<input type="submit" value="Post" />
</form>
</center>
<!-- now grab the edited values, and use them to update the entry in the 'item' table with an SQL query. -->

<?php

$update_this = $_SESSION["update_id"];

$t_up = $_POST['title'];
$c_up = $_POST['dropdown'];
$p_up = $_POST['price'];

# need to check that no fields are blank! If any of them are, display error message and redirect to signup.html
if (empty($t_up) OR empty($c_up) OR empty($p_up)) {

	# invalid input, redirect to update.php
	header("Location: http://www.iexchange.web.engr.illinois.edu/update.php");
	exit();
}

# connect to DB.
define('DB_NAME', 'iexchang_1');
define('DB_USER', 'iexchang_anchal');
define('DB_PASSWORD', 'cs411');
define('DB_HOST', 'engr-cpanel-mysql.engr.illinois.edu');

# establish link to DB
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

if (!$link) {
	die('Could not connect: ' . mysql_error()); # probably replace with a redirect.
}

# select DB
$db_selected = mysql_select_db(DB_NAME, $link);

if (!$db_selected) {
	die('Did not find ' . DB_NAME . ': ' . mysql_error()); # probably replace with a redirect.
}

$q1 = mysql_query("UPDATE item SET title='$t_up', price=$p_up, category='$c_up' WHERE id=$update_this"); # really need to test correctness.

if (!$q1) {

	# Abandon update. ok for the meanwhile, but should have better checks soon.
	header("Location: http://www.iexchange.web.engr.illinois.edu/profile.php");
	exit();
}

mysql_close();
header("Location: http://www.iexchange.web.engr.illinois.edu/profile.php");
exit();
?>

 </body>
</html>
