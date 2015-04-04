<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'a')){
			$interval = $_GET["interval"];
			echo json_encode($db->getDataCube($interval));			
		}
?>