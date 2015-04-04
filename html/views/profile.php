<?php 
	include "../php/login.php";
	
	$username = $_POST["username"]; 
	$password = $_POST["password"];	
	$type =		$_POST["type"];
	
	$valid_login = login($username, $password, $type);
	
	//Check if username and password match.
	if ($valid_login) {
	//If they match display user profile page
	//Else, return login error
	}
	else {
		
	}
	
?>

