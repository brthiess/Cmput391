<?php	
		
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		include_once 'connect.php';
		include_once 'Person.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'a')){
			create_user($db);
		}
	}
		
	/**
	* @param $_POST
	* Creates a user from the post variables
	*/
	function create_user($db) {	
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
		if (strlen($start_date) == 0) {
			$start_date_month = '01';
			$start_date_day = '01';
			$start_date_year = '01';
		}
		else {
			$start_date_month = explode("/", $_POST["start_date"])[0];
			$start_date_day = explode("/", $_POST["start_date"])[1];
			$start_date_year = explode("/", $_POST["start_date"])[2];
		}

		
		$start_date = new Date($start_date_month, $start_date_day, $start_date_year);

		if ($db->userExists($username)) { //If user exists already
			include_once 'edit-user.php';
			edit_user();
			return;
		}		
		else { //User is new.  Add it to DB
			
			//Add person to DB
			$person = new Person(null, $first_name, $last_name, $address, $email, $phone);
			$new_id = $db->addPerson($person);
			print("Person Added");
			
			//Add user with person_id we just obtained
			$user = new User($username, $password, $clss, $new_id, $start_date);
			$db->addUser($user);
			print("User Added");
		}
	}
	
?>