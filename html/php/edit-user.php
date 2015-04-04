<?php 
		include_once 'connect.php';
		include_once 'Person.php';
/**
* @param This is called when there is a http post
*/
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		include_once 'connect.php';
		include_once 'Person.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'all')){
			edit_user($db);
		}
	}
	
function edit_user($db) {
	//Check to see if old password matches
	$old_password = $_POST["old_password"];
	$new_password = $_POST["new_password"];
	if ($old_password == $_SESSION["password"]) {
		$db->updateUserPassword($_SESSION["username"], $new_password);
		$_SESSION["password"] = $new_password;
	}
	else {  //Return error.  User entered incorrect password
		header('HTTP/1.1 500 Internal Server Error');
	}
}

?>