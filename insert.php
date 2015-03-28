<?php
session_start(); # check for ISSET()?
#$_SESSION["user_email"]

$user = $_SESSION["user_email"];
$t = $_POST['title'];
$c = $_POST['dropdown'];
$p = $_POST['price'];

# connect to DB. [Later] Make a file with the code snippet.
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
if (empty($t) OR empty($c) OR empty($p)) {

	# all fields are required. Bail out and redirect to insert.html.
	header("Location: http://iexchange.web.engr.illinois.edu/insert.html");
	exit();
}

# Checks complete. Now insert into post and item tables carefully. Keep in mind that the item ID is auto-incremented.
$t1 = mysql_query("INSERT INTO item (title, price, category) VALUES ('$t', $p, '$c')");

if (!$t1) {
	die('Something bad happened while adding item. ' . mysql_error());
}

# Grab the ID to insert in the post table. Very clumsy, but you gotta do what you gotta do.
$t2 = mysql_query("SELECT id from item WHERE title=('$t') AND category=('$c') AND price=($p)"); # shouldn't compare floats?

if (!$t2) {
	die('Something bad happened while selecting id. Weird. ' . mysql_error());
}

# now actually 'grab' the id.
$item_row = mysql_fetch_assoc($t2);
$grab_id = $item_row['id'];
$glbl = $_SESSION["user_email"];

$t3 = mysql_query("INSERT INTO post (email, id) VALUES ('$glbl', $grab_id)");
if (!$t3) {
	die('Something bad happened while inserting into post. ' . mysql_error());
}

# all done, simply redirect to user's profile page.
mysql_close();
header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
exit();
?>