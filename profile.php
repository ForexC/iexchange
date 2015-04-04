<?php
session_start();

# if user is not logged in, redirect to login.html
if (!isset($_SESSION['user_email'])) {

	header("Location: http://iexchange.web.engr.illinois.edu/login.php");	
	exit();
}

# User is logged in, display the profile page. Username saved in session variable.
# connect to DB.
include 'dbconnect.php';

# retrieve user's post records from the appropriate database to display them (?)
$glb_useremail = $_SESSION["user_email"];
#echo "$glb_useremail";

$query = mysql_query("SELECT * from post P, item I WHERE P.email=('$glb_useremail') AND I.id=P.id"); # careful of session var usage.
#queryy2 is for fetching the user's name
$query2 = mysql_query("SELECT * from users WHERE users.email=('$glb_useremail')"); 
# adding checks
if (!$query) {

	# the user has no items in the database! Redirect to insert.php and bail out.
	header("Location: http://iexchange.web.engr.illinois.edu/insert.php");
	exit();
}

#initialize user's info 
$row = mysql_fetch_assoc($query2);
$name = $row['name'];
$user_email = $row['email'];
$pass = $row['password'];

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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile</title>
			<!-- jQuery Libraries -->
	   		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	   		<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

 </head>

  <body>

    <!-- topbar -->
 	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="index.php">iExchange</a>
	    </div>
	    <div>
	      <ul class="nav navbar-nav">
	        <li><a href="index.php">Home</a></li>
	        <!-- we don't need these ones
	        <li><a href="login.php">Login</a></li>
	        <li><a href="signup.php">Signup</a></li>
	    	-->
	        <li class="active"><a href="profile.php">Profile</a></li>
	        <li><a href="search.php">Search</a></li>
	        <?php if(isset($_SESSION['user_email'])) {?>
	        <li><a href="logout.php">Logout</a></li>
	        <?php } ?>
	      </ul>
	    </div>
	  </div>
    </nav>
<div class="container">
	<center>
	<h2>Your Information</h2>
	<br>
	<br>
	<br>
		<!-- user information table -->
		<table class="table">
			<tbody>
			<tr>
				<th scope="row">Name</th>
				<td><?=$name?></td>
			</tr>
			<tr>
				<th scope="row">Email</th>
				<td><?=$user_email?></td>
			</tr>
			<tr>
				<th scope="row">Password</th>
				<td>**********</td>
			</tr>
		</tbody>
		</table>
	<br>
	<br>
	<br>
 	<h2>Your Posts</h2>
 	<br>
	<br>
	<br>
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

<table class="table table-hover">
	<thead>
		<tr>
			<th>  </th>	
			<th>Title</th>
			<th>Price</th>
			<th>Category</th>
		</tr>
	</thead>

<tbody>
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
</tbody>
</table>
<br>
<input class="btn btn-info" type="submit" name="delete" value="Delete" onclick="submitted = 0" />
<input class="btn btn-info" type="submit" name="update" value="Update" onclick="submitted = 1"/>
</form>

<form action="insert.html" method="post">
<input class="btn btn-info" type="submit" value="Post a new item">
</form>
</center>
</div>
 </body>
</html>