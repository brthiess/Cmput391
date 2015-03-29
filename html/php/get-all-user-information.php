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
			array_push($user_info, $db->getDoctorWithPatient($user->personID));
			
			
			$month = $user_info[0]->dateRegistered->getMonth();
			$year = $user_info[0]->dateRegistered->getYear();
			$day = $user_info[0]->dateRegistered->getDay();
			
			$dateRegistered = $month->toMM() . '/' . $day . '/' . $year;
			$user_info[0]->dateRegistered = $dateRegistered;
			
			echo json_encode($user_info);
		}
?>