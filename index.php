<?php
session_start();

# edit: Since we show listings with email buttons, we can't let users access it unless they're logged in.
# if user is not logged in, redirect to login.html
if (!isset($_SESSION['user_email'])) {

	header("Location: http://iexchange.web.engr.illinois.edu/login.php");	
	exit();
}

include 'dbconnect.php';
$gl_useremail = $_SESSION["user_email"];

# query to get all items in the table that are posted by other users. Hence the not-equals-to.
$query_all = mysql_query("SELECT * from post P, item I WHERE P.email!=('$gl_useremail') AND I.id=P.id");

if (!$query_all) {

	header("Location: http://iexchange.web.engr.illinois.edu/index.php");
	exit();
}

$row_nums = mysql_num_rows($query_all);		         
?>

<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	    <meta name="viewport" content="width=device-width, initial-scale=1">
     		<title>Home</title>

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
	      <a class="navbar-brand" href="index.php">i-Exchange</a>
	    </div>
	    <div>
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="index.php">Home</a></li>
	        <?php if(!isset($_SESSION['user_email'])){ ?>
	        <li><a href="login.php">Login</a></li>
	        <li><a href="signup.php">Signup</a></li>
	        <?php } ?>
	        <li><a href="profile.php">Profile</a></li>
	        <li><a href="search.php">Search</a></li>
	        <?php if(isset($_SESSION['user_email'])) {?>
	        <li><a href="wishlist.php">Wishlist</a></li>
	        <li><a href="history.php">Recommended</a></li>
	        <li><a href="logout.php">Logout</a></li>
	        <?php } ?>
	      </ul>
	    </div>
	  </div>
    </nav>

 	<div class="container">

		<center>
			<h3>All Posts</h3>
			<br> <br>
		</center>	
	<!-- Need to display all items here. No check-boxes, but we need email user buttons. -->
	
	<table class="table table-hover">
	<thead>
		<tr>
			<th>Title</th>
			<th>Price</th>
			<th>Category</th>
			<th>Contact Seller</th>
		</tr>
	</thead>

	<tbody>
	<?php
	while ($record=mysql_fetch_assoc($query_all)) {
	?>		
		<tr>
		<td><?=$record['title']?></td>
		<td>$<?=$record['price']?></td>
		<td><?=$record['category']?></td>
		<td><input class="btn btn-info" type="submit" name="EmailUser" value="Send Email" 
		 onclick="window.location.href='mailto:<?=$record['email']?>';"/> </td>
		</tr>
<?php		
	}
?>
</tbody>
</table>
	</div>
	
</body>																								
</html>