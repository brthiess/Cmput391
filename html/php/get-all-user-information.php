<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'a')){
			echo json_encode($person = $db->getUser($_GET["username"]));
			echo json_encode($db->getPerson($person->personID));			
		}
?>