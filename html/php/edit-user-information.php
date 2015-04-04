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
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$address = $_POST["address"];
	$person = new Person($_SESSION["person_id"], $first_name, $last_name, $email, $address, $phone);
	$db->updatePerson($person);

}

?>