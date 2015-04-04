<?php 

	include_once 'Database.php';

	/**	
		Use this function at the top of every page which requires authentication
	*/
	function start_session() {
				
		if(!isset($_SESSION)){
			session_start();
		} 		
	}
	
	/**
		Check if the user is logged in.  If so, return true.
		Also use this on each page to check for authentication.
	*/
	function check_login($db, $clss_user) {
		//Check to make sure user has logged in already
		if (isset($_SESSION["person_id"], $_SESSION["username"], $_SESSION["password"], $_SESSION["clss"])) {
			$person_id = $_SESSION['person_id'];
			$password = $_SESSION['password'];
			$username = $_SESSION['username'];	
			$clss= $_SESSION['clss'];
			
			$user = $db->getUser($username);
			if ($user != null) {	//Check to see if user exists in db
				if ($password == $user->password  && ($clss == $clss_user || $clss_user == 'all')){
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
	
	
	function login($db, $username, $password, $clss) {
		//Get actual username's password from DB
		$user = $db->getUser($username);
		if ($user != false){
			//Check if username and entered password match.
			
			if ($password == $user->password && $clss == $user->clss) {
				$_SESSION["password"] = $password;
				$_SESSION["username"] = $username;
				$_SESSION["person_id"] = $user->personID;
				$_SESSION["clss"] = $user->clss;
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

