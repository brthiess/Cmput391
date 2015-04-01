<?php 



function expand_tile($record_id, $db){
	

	
		include_once'../php/login.php';
		include_once '../php/tile-info.php';
		include_once '../php/Date.php';
		
		start_session();
	if (check_login($db, 'all')) {
		//Sets the image modal viewer
		set_modal();

		//Get Person Information on Record
		$record_id = explode("-", $record_id)[1];
		$rr = $db->getRadiologyRecordByRecordID($record_id);
		//Gather all relevant data
		$testDate = getTestDate($rr);
		$prescribeDate = getPrescribeDate($rr);
		
		$diagnosis = getDiagnosis($rr);
		$testType = getTestType($rr, $db);
		$description = getDescription($rr);
		
		$doctorName = getDoctorName($rr, $db);
		$doctorAddress = getDoctorAddress($rr, $db);
		$doctorPhone = getDoctorPhone($rr, $db);
		$doctorEmail = getDoctorEmail($rr, $db);
		$doctorID = getDoctorID($rr, $db);
		
		$patientName = getPatientName($rr, $db);
		$patientAddress = getPatientAddress($rr, $db);
		$patientPhone = getPatientPhone($rr, $db);
		$patientEmail = getPatientEmail($rr, $db);
		$patientID = getPatientID($rr, $db);
		$images = getRadiologyImages($record_id, $db);
		
		//print all relevant data
			echo '<div class="col-lg-5 col-lg-offset-1">
					<div class="tile-record center-block" id="id-' . $record_id . '">
						<ul>
							<li>Record ID: <p>' . $record_id . '</p></li>
							<li>Patient ID: <p>' . $patientID . '</p></li>
							<li>Patient Name: <p>' . $patientName . '</p></li>
							<li>Patient Phone: <p>' . $patientPhone . '</p></li>
							<li>Patient Address: <p>' . $patientAddress . '</p></li>
							<li>Patient Email: <p>' . $patientEmail . '</p></li>
							<li>Doctor ID: <p>' . $doctorID . '</p></li>
							<li>Doctor Name: <p>Dr.' . $doctorName . '</p></li>
							<li>Doctor Phone: <p>' . $doctorPhone . '</p></li>
							<li>Doctor Address: <p>' . $doctorAddress . '</p></li>
							<li>Doctor Email: <p>' . $doctorEmail . '</p></li>
							<li>Test Type: <p>' . $testType . '</p></li>
							<li>Date Prescribed: <p>' . $prescribeDate . '</p></li>
							<li>Date Tested: <p>' . $testDate . '</p></li>
							<li>Diagnosis: <p>' . $diagnosis . '</p></li>
							<li>Description: <p>' . $description . '</p></li>
						</ul>
					</div>
				</div>';
			for($i = 0; $i < count($images); $i++){
				echo '<div class="col-sm-2">
						<a href="#" class="thumbnail" data-toggle="modal" data-target="#myModal">
							<img src="' . $images[$i]["IMAGE"] . '" >
						</a>
					  </div>';
			}
	}
}

function set_modal(){
		echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title">Radiology Image</h4>		<button class="btn-inline enlarge-image-button btn btn-info btn-default">View Larger Image</button>
					  </div>
					  <div class="modal-body">
						<img class="img-responsive" id="modal-image" src="images/gallery/1.jpg">
					  </div>
					</div>
				  </div>
				</div>';
}
?>




