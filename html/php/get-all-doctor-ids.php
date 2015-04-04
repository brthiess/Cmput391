<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'all')){
			$doctor_ids = $db->getDoctorIDs();			
			echo json_encode($doctor_ids);
		}
?>