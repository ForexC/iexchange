<?php
session_start();

# User is logged in, display the profile page. Username saved in session variable.
# need to connect to DB!
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

# DB connected.
# retrieve user's post records from the appropriate database to display them (?)
$glb_val = $_SESSION["user_email"];

$query = mysql_query("SELECT * from post P, item I WHERE P.email=('$glb_val') AND I.itemid=P.itemid"); # careful of session var usage.

$row_num = mysql_num_rows($query);
# HTML to display the user's posts below.
?>

<html>
 <head>
  <meta charset="UTF-8">
  <title>Profile</title>
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
 	<h2>Welcome to your profile page! Here, you will see your listings. </h2>
 	<p> <a href="insert.html">Post a new item </a> </p>
 	<!-- todo: delete and update entries. -->

<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>Delete</th>	
<th>Title</th>
<th>Price</th>
<th>Category</th>
</tr>

<?php
	while ($record=mysql_fetch_assoc($query)) {
?>		
		<tr>;
		<td><input name="check[]" type="checkbox" id="check[]" value="<?=$record['id']?>"></td>;
		<td><?=$record['title']?></td>;
		<td><?=$record['price']?></td>;
		<td><?$record['category']?></td>;
		</tr>;
<?php		
	}
?>

</table>
<input type="submit" name="delete" value="Delete" />
</center>
<!-- todo: need update links! -->

<?php

	if ($delete) {

		for ($i=0; $i < row_num; $i++) {

			$del_id = $check[$i];
			$sql_q1 = "DELETE FROM item WHERE id='$del_id'";
			$sql_q2 = "DELETE FROM post WHERE id='$del_id'";
			$res1 = mysql_query($sql_q1);
			$res2 = mysql_query($sql_q2);
		}

		if ($res1 AND $res2) { # done deleting, redirect to profile page.

			mysql_close();
			header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
			exit();
		}	
	}
	mysql_close();
?>

<!--</table> moved above.-->

 </body>
</html>
