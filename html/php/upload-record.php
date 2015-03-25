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
	
	$test_date_month = explode("/", $_POST["test_date"])[0];
	$test_date_day = explode("/", $_POST["test_date"])[1];
	$test_date_year = explode("/", $_POST["test_date"])[2];
	
	$prescribing_date_month = explode("/", $_POST["prescribing_date"])[0];
	$prescribing_date_day = explode("/", $_POST["prescribing_date"])[1];
	$prescribing_date_year = explode("/", $_POST["prescribing_date"])[2];
	
	$test_date = new Date($test_date_month, $test_date_day, $test_date_year);
	$prescribing_date = new Date($prescribing_date_month, $prescribing_date_day, $prescribing_date_year);
	
	$rr = new RadiologyRecord(null, $patient_id, $doctor_id, $radiologist_id, $test_type, $prescribing_date, $test_date, $diagnosis, $description);
	print("record added");
	$rr_id = $db->addRadiologyRecord($rr);
	print($rr_id);
}	
?>