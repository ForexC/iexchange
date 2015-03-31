<?php
session_start();

# User is logged in, display the profile page. Username saved in session variable.
# need to connect to DB!
define('DB_NAME', 'iexchang_1');
define('DB_USER', 'iexchang_anchal');
define('DB_PASSWORD', 'cs411');
define('DB_HOST', 'engr-cpanel-mysql.engr.illinois.edu');

# Fetch the data sent from the HTML form
$search_key = $_POST['search'];

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
# Grab all the title from item table which includes the search key
$search_query = mysql_query("SELECT * from item WHERE title RLIKE ('$search_key')");
?>

<html>
 <head>
  <meta charset="UTF-8">
  <title>Search</title>
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
				<li class="navlink"><a class="nava" href="profile.php">View Profile</a>
				<li class="navlink"><a class="nava" href="search.html">Search</a></li>
			</ul>
		</span>
	  </p>

	<center>
 	<h2>Welcome to the Search page! Here, you will see your listings of your queries. </h2>
 	<!-- todo: delete and update entries. -->

<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>Title</th>
<th>Price</th>
<th>Category</th>
</tr>

<?php
	while ($record=mysql_fetch_assoc($search_query)) {
?>
		<tr>
		<td><?=$record['title']?></td>
		<td>$<?=$record['price']?></td>
		<td><?=$record['category']?></td>
		</tr>
<?php
	}
?>

 </body>
</html>
