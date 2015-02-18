<?php
/**
 * FamilyDoctor.php
 */

/*!@class FamilyDoctor
 * @brief PHP representation of family_doctor schema.
 */
class FamilyDoctor{
    public $doctorID = -1;
    public $patientID = -1;

    public function __construct($docID, $patID){
        $this->doctorID = $docID;
        $this->patientID = $patID;
    }
}
?>