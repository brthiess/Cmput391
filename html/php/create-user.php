<?php include_once 'connect.php';
	include_once 'Person.php';

		
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$clss = $_POST["clss"];
	$email = $_POST["email"];
	$phone = $_POST["phone"];
	$doctor = $_POST["doctor"];
	$address = $_POST["address"];
	$start_date = $_POST["start_date"];
	
	//TODO:
		//NEED TO CHECK IF USERNAME ALREADY EXISTS
	
	$new_user = new Person(null, $first_name, $last_name, $address, $email, $phone);
	$new_id = $db->addPerson($new_user);
	print("Person Added");
?>