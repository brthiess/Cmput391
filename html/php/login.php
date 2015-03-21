<?php 

	include_once 'Database.php';

	/**	
		Use this function at the top of every page which requires authentication
	*/
	function start_session() {
		session_start(); 		
	}
	
	/**
		Check if the user is logged in.  If so, return true.
		Also use this on each page to check for authentication.
	*/
	function check_login($db) {
		//Check to make sure user has logged in already
		if (isset($_SESSION["person_id"], $_SESSION["username"], $_SESSION["password"])) {
			$person_id = $_SESSION['person_id'];
			$password = $_SESSION['password'];
			$username = $_SESSION['username'];		
					
			$user = $db->getUser($username);
			
			if ($user != null) {	//Check to see if user exists in db
				if ($password == $user->password){
					//We are logged in
					return true;				
				}
				else{
					//not logged in
					return false;
				}
			}
			else {
				//not logged in
				return false;
			}
		}
		else {
			//not logged in
			return false;
		}
		
	}
	
	
	function login($db, $username, $password) {
		
		//Get actual username's password from DB
		$user = $db->getUser($username);
		if ($user != false){
			//Check if username and entered password match.
			if ($password == $user->password) {
				$_SESSION["password"] = $password;
				$_SESSION["username"] = $username;
				$_SESSION["person_id"] = $user->personID;
				//Log in successful
				return true;		
			}
			else {
				//Log in failed
				return false;			
			}
		}
		else {
			//Log in failed
			return false;
		}
	}
?>

