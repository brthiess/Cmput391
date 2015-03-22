<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'a')){
			$user_info = array();
			$person = $_GET["username"];
			$user = $db->getUser($person);
			array_push($user_info, $user);
			array_push($user_info, $db->getPerson($user->personID));	
			echo json_encode($user_info);
		}
?>