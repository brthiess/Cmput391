<?php include_once 'search-tile.php';
		include_once '../php/Search.php';
		include_once '../php/login.php';
		include_once '../php/connect.php';
		
		start_session();
	if (check_login($db, 'all')) { //Make sure search is authorized
		
		// Throws an exception if not a valid "userName" is not valid.
		$username = $_SESSION["username"];
		$search = new Search($username);
		$search_results = "";
		
		//Get user search input
		$keywords = $_POST["input"];
		$start_date = $_POST["start_date"];
		$end_date = $_POST["end_date"];

		$search_type = $_POST["search_type"];  //Search by diagnosis (d) or record (r)
		$sort_type = $_POST["sort_type"];	//d = descending, a = ascending, n = none
		
		
		//Check to see if user entered a start date.  If not, put in our own start date of 01/01/1000
		if (strlen($start_date) == 0) {
			$start_date_month = "03";
			$start_date_year = "1900";
			$start_date_day = "15";
		}
		else {	//Separate inputted date into its month year and day counterparts
			$start_date_month = explode("/", $_POST["start_date"])[0];
			$start_date_day = explode("/", $_POST["start_date"])[1];
			$start_date_year = explode("/", $_POST["start_date"])[2];
		}
		if (strlen($end_date) == 0) {
			$end_date_month = "01";
			$end_date_year = "3000";
			$end_date_day= "15";
		}
		else {
			$end_date_month = explode("/", $_POST["end_date"])[0];
			$end_date_day = explode("/", $_POST["end_date"])[1];
			$end_date_year = explode("/", $_POST["end_date"])[2];
		}
		if (strlen($keywords) == 0) {
			$keywords = "null";
		}
		
		

		$start_date = new Date($start_date_month, $start_date_day, $start_date_year);
		$end_date = new Date($end_date_month, $end_date_day, $end_date_year);
		
		print($start_date);
		print($end_date);
		
		if ($search_type == 'r') {  //Search radiology records
			if ($sort_type == 'n'){  //If user indicates not to sort results by time
					print("No Sort");
					$search_results = $search->searchWithKPByRank($keywords, $start_date, $end_date);
			}
			else if ($sort_type == 'd') {  //If user indicates to sort results by time descending
				print("desc");
				$search_results = $search->searchWithKPByTime($keywords, $start_date, $end_date, true);
			}
			else if ($sort_type == 'a') { // If user indicates to sort results by time ascending
				print("asc");
				$search_results = $search->searchWithKPByTime($keywords, $start_date, $end_date, false);
			}
		}
		
		else if ($search_type == 'd') {  //Search by diagnosis
			print("Search By Diagnosis");
			$search_results = $search->searchByDiagnosis($keywords, $start_date, $end_date);	
		}
		
		print_r($search_results);
		
		for($i = 0; $i < count($search_results); $i++) {
		print_tile($db, $search_results[$i]->recordID);		
		}

	}
	
?>