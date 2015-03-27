<?php

# todo.
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

$nm = $_POST['name'];
$em = $_POST['email'];
$pw = $_POST['password'];

# need to check that no fields are blank! If any of them are, display error message and redirect to signup.html
if (empty($nm) OR empty($em) OR empty($pw)) {

	# redirect to signup.html here
	# header("Location: http://www.iexchange.web.engr.illinois.edu/signup.html");
	die('Field(s) empty.');
	exit();
}

# check illinois email
#$regex = "^[a-zA-Z0-9_.+-]+([@illinois.edu])+$"; 
$regex = "/[a-zA-Z0-9_.+-]+(@illinois.edu)/";

if (!preg_match($regex, $em)) {

	# prompt user and redirect to signup.html
	#header("Location: http://www.iexchange.web.engr.illinois.edu/signup.html");
	die('Invalid email.');
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

echo 'All good!';

# Now redirect to login page.

mysql_close();
?>