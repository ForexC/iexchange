<?php
session_start();

if (!isset($_SESSION["update_id"])) { # update not active in profile.php. Redirect to profile.php

		header("Location: http://iexchange.web.engr.illinois.edu/profile.php");
		exit();
}

	
if (isset($_POST['updateSubmit'])) {

	#connect to DB
	# User is logged in, display the profile page. Username saved in session variable.
	# need to connect to DB!

	include 'dbconnect.php';

	$update_this = $_SESSION["update_id"];

	$t_up = $_POST['title'];
	$c_up = $_POST['dropdown'];
	$p_up = $_POST['price'];

	$q1 = mysql_query("UPDATE item SET title='$t_up', price=$p_up, category='$c_up' WHERE id=$update_this"); # really need to test correctness.

	if (!$q1) {

			# Abandon update. ok for the meanwhile, but should have better checks soon.
			echo '<script type="text/javascript">
	               window.location = "http://iexchange.web.engr.illinois.edu/profile.php"
	              </script>';
			exit();
	}

	mysql_close();
	echo '<script type="text/javascript">
	               window.location = "http://iexchange.web.engr.illinois.edu/profile.php"
	              </script>';
	exit();

}	

?>


<html>
<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	    <meta name="viewport" content="width=device-width, initial-scale=1">
     		<title>Update an item</title>

     		<!-- jQuery Libraries -->
	   		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	   		<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
<!-- JavaScript -->

<script type="text/javascript">

		function validateInputs(){
			var title = document.forms["updateForm"]["title"];
			var dropdown = document.forms["updateForm"]["dropdown"];
			var price = document.forms["updateForm"]["price"];

			if(title == ""){
				alert("title missing");
				return false;
			}
			if(!(price.value>0)){
				alert("invalid price");
				return false;
			}
			return true;
		}
</script>


</head>

<body>
 <!-- topbar -->
 	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="index.php">i-Exchange</a>
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
	<h2>Update an Item</h2>

	<form name="updateForm" onsubmit="return validateInputs()" action="#" method="post" class="form-horizontal"/>

	 <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Title</label>
		      <div class="col-sm-2">
		        <input class="form-control" name="title" type="text">
		      </div>
		      <div class="col-sm-5">
		      </div>
	    </div>
	    <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Category</label>
		      <div class="col-sm-2">
		        <select name="dropdown" class="form-control">
					<option value="Apparel">Apparel</option>
					<option value="Computers">Computers</option>
					<option value="Books">Books</option>
					<option value="Accessories">Accessories</option>
					<option value="Misc.">Misc.</option>
				</select>
		      </div>
		      <div class="col-sm-5">
	      </div>
	    </div>
	    <div class="form-group">
		      <div class="col-sm-3">
		      </div>
		      <label class="col-sm-2 control-label">Price</label>
		      <div class="col-sm-2">
		        <input class="form-control" type="number" step="0.01" name="price"/>
		      </div>
		      <div class="col-sm-5">
		      </div>
	    </div>

	<input class="btn btn-info" name="updateSubmit" type="submit" value="Post" />
	</form>
	</center>
	</div>
	 </body>
	</html>
