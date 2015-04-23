<?php
session_start();

include 'dbconnect.php';
$gl_useremail = $_SESSION["user_email"];
		                
#$sub_query = mysql_query("SELECT title from history WHERE email=('$gl_useremail')");
$sub_query = mysql_query("SELECT title, category from history WHERE email=('$gl_useremail')");

$count = 1;
$wishes = "";
$wishes1 = "";
while ($subs = mysql_fetch_assoc($sub_query)) {

	if ($count != 1) {
		$wishes .= '|';
		$wishes1 .= '|';

	}
	
	$wishes .= $subs['title'];
	$wishes1 .= $subs['category'];
	$count = $count + 1;
}

# if wishlist is empty, set regex to reject all strings. http://stackoverflow.com/questions/62430/regular-expression-that-rejects-all-input

if ($wishes == "") { 

	$wishes = ".^"; # reject everything below.
}

if ($wishes1 == "") { 

	$wishes1 = ".^"; # reject everything below.
}


#echo $wishes;
#echo $wishes1;

# thanks http://stackoverflow.com/questions/26660973/php-pdo-mysql-query-like-multiple-keywords
$query_history = mysql_query("SELECT * from post P, item I WHERE P.email!=('$gl_useremail') AND I.id=P.id
			       AND (I.title RLIKE ('" .mysql_real_escape_string($wishes). "'))");

#$query_history = mysql_query("SELECT * from post P, item I WHERE P.email!=('$gl_useremail') AND I.id=P.id
#AND (I.title RLIKE ('" .mysql_real_escape_string($wishes). "') OR I.category RLIKE ('".mysql_real_escape_string($wishes1). "'))");
		                
if (!$query_history) {
	
	# very volatile. If fails, causes redirect loop... redirect elsewhere?
	header("Location: http://iexchange.web.engr.illinois.edu/index.php");
	exit();
}		                

?>

<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	    <meta name="viewport" content="width=device-width, initial-scale=1">
     		<title>Recommended</title>

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
	        <li><a href="index.php">Home</a></li>
	        <?php if(!isset($_SESSION['user_email'])){ ?>
	        <li><a href="login.php">Login</a></li>
	        <li><a href="signup.php">Signup</a></li>
	        <?php } ?>
	        <li><a href="profile.php">Profile</a></li>
	        <li><a href="search.php">Search</a></li>
	        <?php if(isset($_SESSION['user_email'])) {?>
	        <li><a href="wishlist.php">Wishlist</a></li>
	        <li class="active"><a href="history.php">Recommended</a></li>
	        <li><a href="logout.php">Logout</a></li>
	        <?php } ?>
	      </ul>
	    </div>
	  </div>
    </nav>

	<div class="container">
	<center>
	<h3>Items Based on Your Purchase History</h3>
	<br> <br>
	</center>
	
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
	while ($record=mysql_fetch_assoc($query_history)) {
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