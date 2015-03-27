<?php
session_start();

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

$em = $_POST['email'];
$pw = $_POST['password'];

# need to check that no fields are blank. If any of them are, display error message and redirect to signup.html
if (empty($em) OR empty($pw)) {
	
	# redirect to login.html. To improve this, use AJAX.
	header("Location: http://iexchange.web.engr.illinois.edu/login.html");
	exit();
}

# sanitation checks complete. Simply retrieve user row and validate.
$retrieve = mysql_query("SELECT * from users WHERE email=('$em') AND password=('$pw')");

if (!$retrieve) {
	die('Something bad happened while fetching user info. ' . mysql_error());
}

# if we retrieved only one row, we're good. Then use mysql_array or something to get the user
# and redirect to the user's profile page [difficult]

$numrows = mysql_num_rows($retrieve);

if ($numrows == 0) { # user not found, or wrong email/pw combo. Redirect to login page.

	header("Location: http://iexchange.web.engr.illinois.edu/login.html");
	exit();
}

if ($numrows > 1) { # this should not happen!

	header("Location: http://iexchange.web.engr.illinois.edu/login.html");
	exit();
}

# sanitation checks complete. Now fetch user entries (ID/name/email) and redirect to profile page.
$row = mysql_fetch_assoc($retrieve);
$user_email = $row['email'];
$pass = $row['password'];


# VERY IMPORTANT: set session username for login! :)
$_SESSION["user_email"] = $user_email;
$_SESSION["password"] = $pass;

## login complete. Now redirect to display user profile.
#include('profile.php'); # will this work? Replace with header()?

mysql_close();
header("Location: http://iexchange.web.engr.illinois.edu/profile.php"); # ensure that this works.
exit();
?>
<!-- 
# retrieve user's post records from the appropriate database to display them (?)
#$query1 = mysql_query("SELECT * from post where username=('$user')");


# start of profile.php
session_start();
$query = mysql_query("SELECT * from post P, item I WHERE P.email=('$user_email') AND I.itemid=P.itemid");

# HTML to display the user's posts below.
#mysql_close();
?>

<html>
 <head>
  <meta charset="UTF-8">
  <title>Profile</title>
 </head>

 <body>

 	<p>Welcome to your profile page! Here, you will see your listings. </p>
 	<p> <a href="http://iexchange.web.engr.illinois.edu/insert.html">Post a new item </a> </p>

<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>Title</th>
<th>Price</th>
<th>Category</th>
</tr>

<?php
	// while ($record=mysql_fetch_assoc($query)) {

	// 	echo "<tr>";
	// 	echo "<td>".$record['title']."</td>";
	// 	echo "<td>".$record['price']."</td>";
	// 	echo "<td>".$record['category']."</td>";
	// 	echo "</tr>";
	// }

 //  	# todo: need update and delete buttons!
	// mysql_close();
?>

</table>

 </body>
</html> -->