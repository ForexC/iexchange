<?php
session_start();

include 'dbconnect.php';
$gl_useremail = $_SESSION["user_email"];
		                
$sub_query = mysql_query("SELECT title from wishlist WHERE email=('$gl_useremail')");

$count = 1;
$wishes = "";
while ($subs = mysql_fetch_assoc($sub_query)) {

	if ($count != 1) {
		$wishes .= '|';
	}
	
	$wishes .= $subs['title'];
	$count = $count + 1;
}

# if wishlist is empty, set regex to reject all strings. http://stackoverflow.com/questions/62430/regular-expression-that-rejects-all-input

if ($wishes == "") { 

	$wishes = ".^"; # reject everything below.
}

#echo $wishes;

# thanks http://stackoverflow.com/questions/26660973/php-pdo-mysql-query-like-multiple-keywords
$query_wishlist = mysql_query("SELECT * from post P, item I WHERE P.email!=('$gl_useremail') AND I.id=P.id
			       AND I.title REGEXP ('" .mysql_real_escape_string($wishes). "')");
		                
if (!$query_wishlist) {
	
	# very volatile. If fails, causes redirect loop... redirect elsewhere?
	header("Location: http://iexchange.web.engr.illinois.edu/index.php");
	exit();
}		                

#Add to wishlist
if (isset($_POST['addWish'])) {

	$search_key_temp = $_POST['addWish'];
	
	# hotfix edit: split $search_key_temp, replacing spaces with |	
	$search_key = str_replace(" ", "|", $search_key_temp);
	
	if (!empty($search_key)) {
	
		$awquery = mysql_query("INSERT INTO wishlist (email, title) VALUES ('$gl_useremail', '$search_key')");
	}	
}

?>

<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	    <meta name="viewport" content="width=device-width, initial-scale=1">
     		<title>Wishlist</title>

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
	        <li class="active"><a href="wishlist.php">Wishlist</a></li>
	        <li><a href="history.php">Recommended</a></li>
	        <li><a href="logout.php">Logout</a></li>
	        <?php } ?>
	      </ul>
	    </div>
	  </div>
    </nav>
	
	<br>
	<div class="container">

	<center>
	<h3>Items from Your Wishlist</h3>
	<br>
	</center>
	
	
	<form action="#" method="post" />
	<div class="row">
    	<div class="col-lg-4 col-lg-offset-4">
            <div class="input-group">
                <input type="text" class="form-control" name="addWish" /> 
                    <span class="input-group-btn">
                    <input class="btn btn-default" type="submit" value="Add to Wishlist" />
                    </span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
        </form>
	
	<!--</div>-->
	<br>

	<!--<div class="container">-->	
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
	while ($record=mysql_fetch_assoc($query_wishlist)) {
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