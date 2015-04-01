<?php
session_start();

# if user is not logged in, redirect to login.html
if (!isset($_SESSION['user_email'])) {

	
	("Location: http://iexchange.web.engr.illinois.edu/login.php");
	exit();
}

# User is logged in, display the profile page. Username saved in session variable.
# connect to DB.
include 'dbconnect.php';

# retrieve user's post records from the appropriate database to display them (?)
$glb_val = $_SESSION["user_email"];
#echo "$glb_val";

$query = mysql_query("SELECT * from post P, item I WHERE P.email=('$glb_val') AND I.id=P.id"); # careful of session var usage.

# adding checks
if (!$query) {

	# the user has no items in the database! Redirect to insert.php and bail out.
	header("Location: http://iexchange.web.engr.illinois.edu/insert.php");
	exit();
}

$row_num = mysql_num_rows($query);
# HTML to display the user's posts below.

#Check if user has deleted or updated a post
if (isset($_POST["delete"])) {
	if(!empty($_POST['check'])){
		$check = $_POST["check"];
		for ($i=0; $i < $row_num; $i++) {
			$del_id = $check[$i];
			$sql_q1 = "DELETE FROM item WHERE id='$del_id'";
			$sql_q2 = "DELETE FROM post WHERE id='$del_id'";
			$res1 = mysql_query($sql_q1);
			$res2 = mysql_query($sql_q2);
		}
	}
		

	if ($res1 AND $res2) { # done deleting, redirect to profile page.

		mysql_close();
		echo '<script type="text/javascript">
               window.location = "http://iexchange.web.engr.illinois.edu/profile.php"
              </script>';
		exit();
		}	
	}

	if (isset($_POST['update'])) { # check if the user pressed delete or not.

		if(!empty($_POST['check'])){
			$check = $_POST['check'];
			$check_value = $check[0];
			$_SESSION["update_id"] = $check_value;
			echo '<script type="text/javascript">
               window.location = "http://iexchange.web.engr.illinois.edu/update.php"
              </script>';
			exit();
		}
	}
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
				<li class="navlink"><a class="nava" href="index.php">Home</a>
				<li class="navlink"><a class="nava" href="login.php">Login</a>
				<li class="navlink"><a class="nava" href="signup.php">Signup</a>
				<li class="navlink"><a class="nava" href="profile.php">View Profile</a>
				<li class="navlink"><a class="nava" href="search.html">Search</a>
			</ul>
		</span>
	  </p>

	<center>
 	<h2>Your Posts</h2>
 	<!-- <p> <a href="insert.html">Post a new item </a> </p> -->


<script type="text/javascript">
var submitted = 3;

function validateInputs(){
	var del_num = document.forms["profileTableForm"]["check[]"];
	var count = document.querySelectorAll('input[type="checkbox"]:checked').length;
	if(submitted == 1 && count != 1){
		alert("please choose one item to update");
		return false;
	}
	if(submitted == 0 && count < 1){
		alert("please select items to delete");
		return false;
	}
	return true;
}
</script>


<form name="profileTableForm" action="#" method="post" onsubmit="return validateInputs()">

<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>  </th>	
<th>Title</th>
<th>Price</th>
<th>Category</th>
</tr>

<?php
	while ($record=mysql_fetch_assoc($query)) {
?>		
		<tr>
		<td>
			<input name="check[]" type="checkbox" id="check[]" value="<?=$record['id']?>"/>
		</td>
		<td><?=$record['title']?></td>
		<td><?=$record['price']?></td>
		<td><?=$record['category']?></td>
		</tr>
<?php		
	}
?>

</table>
<br>
<input type="submit" name="delete" value="Delete" onclick="submitted = 0" />
<input type="submit" name="update" value="Update" onclick="submitted = 1"/>
</form>

<form action="http://iexchange.web.engr.illinois.edu/insert.html">
<input type="submit" value="Post a new item">
</form>
</center>

 </body>
</html>