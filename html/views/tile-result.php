<?php
		include_once 'tile-info-print.php';
		include_once 'search-tile.php';
		include_once '../php/Search.php';
		include_once '../php/login.php';
		include_once '../php/connect.php';
		
		start_session();
	if (check_login($db, 'all')) {	
		$record_id = $_POST["id"];
		expand_tile($record_id, $db);
	}

?>