<?php
/**
 * RadiologyRecord.php
 */

include_once 'Date.php';

/*!@class RadiologyRecord
 * @brief PHP representation of radiology_record schema.
 */
class RadiologyRecord{
    public $recordID = NULL;
    public $patientID = NULL;
    public $doctorID = NULL;
    public $radiologistID = NULL;
    public $testType = NULL;
    public $prescribingDate = NULL;
    public $testDate = NULL;
    public $diagnosis = NULL;
    public $description = NULL;

    public function __construct(
        $recordID, $patientID, $doctorID, $radiologistID,
        $testType, Date $prescribingDate, Date $testDate, $diagnosis, $description){
        $this->recordID = $recordID;
        $this->patientID = $patientID;
        $this->doctorID = $doctorID;
        $this->radiologistID = $radiologistID;
        $this->testType = $testType;
        $this->prescribingDate = $prescribingDate;
        $this->testDate = $testDate;
        $this->diagnosis = $diagnosis;
        $this->description = $description;
    }
}

?>