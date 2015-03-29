<?php
		include_once 'connect.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'all')){
			$radiologist_ids = $db->getRadiologistIDs();			
			echo json_encode($radiologist_ids);
		}
?>