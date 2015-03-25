<?php
function getDay($rr){
	return explode("-", $rr["TEST_DATE"])[0];
}
function getMonth($rr){
	$month = explode("-", $rr["TEST_DATE"])[1];
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
function getYear($rr){
	return explode("-", $rr["TEST_DATE"])[2];
}
function getDiagnosis($rr){
	return $rr["DIAGNOSIS"];
}
function getDescription($rr){
	return $rr["DIAGNOSIS"];
}
function getDoctor($rr, $db){
	$doctor_id = $rr["DOCTOR_ID"];
	$person = $db->getPerson($doctor_id);
	return $person->firstName . " " . $person->lastName;
}
function getPatientName($rr, $db){
	$patient_id = $rr["PATIENT_ID"];
	$person = $db->getPerson($patient_id);
	return $person->firstName . " " . $person->lastName;
}
