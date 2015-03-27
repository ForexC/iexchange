<?php

$nm = $_POST['name'];
$em = $_POST['email'];
$pw = $_POST['pw'];

# need to check that no fields are blank! If any of them are, display error message and redirect to signup.html
if (empty($nm) OR empty($em) OR empty($pw)) {
	# redirect to signup.html here
	echo 'Field(s) empty!';
	exit();
}

# check illinois email
#$regex = "/^[a-zA-Z0-9_.+-]+([@illinois.edu])$/"; 
$regex = "/[a-zA-Z0-9_.+-]+(@illinois.edu)/";

if (!preg_match($regex, $em)) {

	# prompt user and redirect to signup.html
	echo 'Email is not @illinois.edu';
	exit();
}

echo 'All good!';
?>