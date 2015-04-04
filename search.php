<?php
session_start();

# User is logged in, display the profile page. Username saved in session variable.
include 'dbconnect.php';

# DB connected.
# Grab all the title from item table which includes the search key
# Fetch the data sent from the HTML form
$search_key = $_POST['search'];

if(!isset($search_key)){
	#error_log("search_key not defined");
}else{
	$search_query = mysql_query("SELECT * from item WHERE title RLIKE ('$search_key')");
}

?>

<html>
 <head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Search</title>
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
	        <?php if(!isset($_SESSION['user_email'])){ ?>
	        <li><a href="login.php">Login</a></li>
	        <li><a href="signup.php">Signup</a></li>
	        <?php } ?>
	        <li><a href="profile.php">Profile</a></li>
	        <li class="active"><a href="search.php">Search</a></li>
	        <?php if(isset($_SESSION['user_email'])) {?>
	        <li><a href="logout.php">Logout</a></li>
	        <?php } ?>
	      </ul>
	    </div>
	  </div>
    </nav>
<div class="container">
	<center>
	<h2>Search Item</h2>
	<br>
	<br>
	<br>

	<form action="#" method="post" /> 
	 <div class="form-group">
	      <div class="col-sm-1">
	      </div>
	      <div class="col-sm-8">
	       <input class="form-control" name="search" type="text" value="search">
	      </div>
	      <div class="col-sm-3">
	      	<input class="btn btn-info" type="submit" value="Search" />
	      </div>
	 </div>
	
	</form>
	</center>


 	<!-- todo: delete and update entries. -->
<br>
<br>
<br>
<table class="table table-hover">
<thead>
	<tr>
	<th>Title</th>
	<th>Price</th>
	<th>Category</th>
	</tr>
</thead>
<tbody>
<?php
	if(isset($search_query)){
		while ($record=mysql_fetch_assoc($search_query)) {
?>
		<tr>
		<td><?=$record['title']?></td>
		<td>$<?=$record['price']?></td>
		<td><?=$record['category']?></td>
		</tr>
<?php
		}
	}
?>
</tbody>
</table>
</div>
 </body>
</html>
