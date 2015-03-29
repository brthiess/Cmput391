<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'all')){
			$patient_ids = $db->getPatientIDs();			
			echo json_encode($patient_ids);
		}
?>