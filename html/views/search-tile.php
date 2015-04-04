<?php 



function print_tile($db, $record_id){
	
		include_once'../php/login.php';
		include_once '../php/connect.php';
		include_once '../php/tile-info.php';
		include_once '../php/Date.php';
		
		start_session();
	if (check_login($db, 'all')) {
		//Get Person Information on Record
		$rr = $db->getRadiologyRecordByRecordID($record_id);
		$day = getTestDay($rr);
		$year = getTestYear($rr);
		$month = getTestMonth($rr);
		$diagnosis = getDiagnosis($rr);
		$doctor = getDoctorName($rr, $db);
		$patientName = getPatientName($rr, $db);
			echo '<div class="col-lg-3 col-md-4 col-sm-6">
					<div class="search-tile center-block" id="id-' . $record_id . '">
						<div class="name">
							'. $patientName .'
						</div>
						<div class="doctor">
							Dr. '. $doctor .'
						</div>
						<div class="date">
							<div class="day">
								'. $day .'
							</div>
							<div class="month">
								'. $month .'
							</div>
							<div class="year">
								\''. $year .'
							</div>
						</div>
						<div class="diagnosis">
							Diagnosis: '. $diagnosis .'
						</div>
					</div>
				 </div>';
	}	
}
?>

