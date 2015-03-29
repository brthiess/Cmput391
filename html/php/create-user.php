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
		$doctor_ids = json_decode($_POST["doctor"]);
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
		
		//Person
		$person = new Person(null, $first_name, $last_name, $address, $email, $phone);

		
		$new_id = 0;
		if ($db->userExists($username)) { //If user exists already	
			print("EXISTS: " . $username);
			//Get User ID
			$new_id = $db->getUserID($username);
			//Set it to the person
			$person->personID = $new_id;
			$db->updatePerson($person);
			print("Person Edited");
			//Update User
			$user = new User($username, $password, $clss, $new_id, $start_date);
			$db->updateUser($user);
			print("User Updated");
			$db->removeAllFamilyDoctorsFromPatient($new_id);
			foreach($doctor_ids as $doctor_id){
				$doctor = new FamilyDoctor($doctor_id, $new_id);
				$db->addFamilyDoctor($doctor);
				print("Doctor Edited");
			}
			return;
		}		
		else { //User is new.  Add it to DB			
			//Add person to DB
			$new_id = $db->addPerson($person);
			print("Person Added");
			//Add user with person_id we just obtained
			$user = new User($username, $password, $clss, $new_id, $start_date);
			$db->addUser($user);
			print("User Added");
			$db->removeAllFamilyDoctorsAssociatedWithPatient($new_id);
			foreach($doctor_ids as $doctor_id){
				$doctor = new FamilyDoctor($doctor_id, $new_id);
				$db->addFamilyDoctor($doctor);
				print("Doctor Added");
			}		
		}
	}
	
?>