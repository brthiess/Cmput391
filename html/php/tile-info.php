<?php
function getTestDay($rr){
	$explode = explode("-", $rr["TEST_DATE"]);
	return $explode[2];
}
function getTestMonth($rr){
	 $explode = explode("-", $rr["TEST_DATE"]);
	$month = $explode[1];
        switch($month){
        case 01:
            return 'JAN';
        case 02:
            return 'FEB';
        case 03:
            return 'MAR';
        case 04:
            return 'APR';
        case 05:
            return 'MAY';
        case 06:
            return 'JUN';
        case 07:
            return 'JUL';
        case 08:
            return 'AUG';
        case 09:
            return 'SEPT';
        case 10:
            return 'OCT';
        case 11:
            return 'NOV';
        case 12:
            return 'DEC';
        }
		
}
function getTestYear($rr){
	 $explode = explode("-", $rr["TEST_DATE"]);
	return $explode[0];
}
function getPrescribeDay($rr){
	 $explode = explode("-", $rr["TEST_DATE"]);
	return $explode[2];
}
function getTestDate($rr){
	return $rr["TEST_DATE"];
}
function getPrescribeDate($rr){
	return $rr["PRESCRIBING_DATE"];
}
function getPrescribeMonth($rr){
	 $explode = explode("-", $rr["PRESCRIBING_DATE"]); 
	$month = $explode[1];
        switch($month){
        case 01:
            return 'JAN';
        case 02:
            return 'FEB';
        case 03:
            return 'MAR';
        case 04:
            return 'APR';
        case 05:
            return 'MAY';
        case 06:
            return 'JUN';
        case 07:
            return 'JUL';
        case 08:
            return 'AUG';
        case 09:
            return 'SEPT';
        case 10:
            return 'OCT';
        case 11:
            return 'NOV';
        case 12:
            return 'DEC';
        }
		
}
function getPrescribeYear($rr){
	$explode = explode("-", $rr["PRESCRIBING_DATE"]); 
	$month = $explode[1];
	return $month;
}
function getDiagnosis($rr){
	return $rr["DIAGNOSIS"];
}
function getDescription($rr){
	return $rr["DESCRIPTION"];
}
function getTestType($rr){
	return $rr["TEST_TYPE"];
}
function getDoctorName($rr, $db){
	$doctor_id = $rr["DOCTOR_ID"];
	$person = $db->getPerson($doctor_id);
	return $person->firstName . " " . $person->lastName;
}
function getDoctorPhone($rr, $db){
	$doctor_id = $rr["DOCTOR_ID"];
	$person = $db->getPerson($doctor_id);
	return $person->phone;
}
function getDoctorAddress($rr, $db){
	$doctor_id = $rr["DOCTOR_ID"];
	$person = $db->getPerson($doctor_id);
	return $person->address;
}
function getDoctorEmail($rr, $db){
	$doctor_id = $rr["DOCTOR_ID"];
	$person = $db->getPerson($doctor_id);
	return $person->email;
}
function getDoctorID($rr, $db){
	return $rr["DOCTOR_ID"];
}
function getPatientName($rr, $db){
	$patient_id = $rr["PATIENT_ID"];
	$person = $db->getPerson($patient_id);
	return $person->firstName . " " . $person->lastName;
}
function getPatientPhone($rr, $db){
	$patient_id = $rr["PATIENT_ID"];
	$person = $db->getPerson($patient_id);
	return $person->phone;
}
function getPatientEmail($rr, $db){
	$patient_id = $rr["PATIENT_ID"];
	$person = $db->getPerson($patient_id);
	return $person->email;
}
function getPatientAddress($rr, $db){
	$patient_id = $rr["PATIENT_ID"];
	$person = $db->getPerson($patient_id);
	return $person->address;
}
function getPatientID($rr, $db){
	return $rr["PATIENT_ID"];
}
function getRadiologyImages($record_id, $db){
	return $db->getRadiologyImages($record_id);
}
?>
