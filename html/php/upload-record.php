<?php	
		
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		include_once 'connect.php';
		include_once 'RadiologyRecord.php';
		include_once 'login.php';

		start_session();
		if (check_login($db, 'r')){
			upload_record($db);
		}
	}

function upload_record($db) {
	$patient_id = $_POST["patient_id"];
	$radiologist_id = $_POST["radiologist_id"];
	$doctor_id = $_POST["doctor_id"];
	$test_type = $_POST["test_type"];
	$diagnosis = $_POST["diagnosis"];
	$description = $_POST["description"];
	$test_date = $_POST["test_date"];
	$prescribing_date = $_POST["prescribing_date"];	
	$images = json_decode($_POST["images"]);
	
	
	
	$explode = explode("/", $_POST["test_date"]);
	$test_date_month = $explode[0];
	$test_date_day = $explode[1];
	$test_date_year = $explode[2];
	
	$explode = explode("/", $_POST["prescribing_date"]);
	$prescribing_date_month = $explode[0];
	$prescribing_date_day = $explode[1];
	$prescribing_date_year = $explode[2];
	
	$test_date = new Date($test_date_month, $test_date_day, $test_date_year);
	$prescribing_date = new Date($prescribing_date_month, $prescribing_date_day, $prescribing_date_year);
	
	$rr = new RadiologyRecord(null, $patient_id, $doctor_id, $radiologist_id, $test_type, $prescribing_date, $test_date, $diagnosis, $description);

	$rr_id = $db->addRadiologyRecord($rr);
	print("record added");
	print($rr_id);
	
	//Delete current images and replace with new ones
	$db->deleteRadiologyImages($rr_id);
	for ($i = 0; $i < count($images); $i++){
		$db->addRadiologyImage($images[$i], $rr_id);
		print("Image Added");
	}
}	
?>