<?php
/**
 * DataAnalysis.php
 */


include_once 'Database.php';
include_once 'Person.php';

class Interval{
    // Constants.
    const Monthly = 1;
    const Annually = 2;
}

class DataAnalysis{
    private $_db = NULL;  // Database instance.

    /**
     * @param adminUserName userName of an admin user.
     * @throw Exception, if adminUserName is not a username of an admin.
     * @throw Exception, if adminUserName does not exist.
     */
    public function __construct($adminUserName){
        $this->_db = Database::instance();

        
        $user = $this->_db->getUser($adminUserName);
        // Verify that this user exist.
        if($user == False){
            throw new Exception("No user associated with username: ".$adminUserName.".");
        }

        // Verify that this user is an admin.
        if($user->clss != UserClass::admin){
            // Not an admin, throw an exception.
            throw new Exception("User associated with username: ".$adminUserName.
                                " is not an admin.");
        }
    }
    
    /**
     * @param interval could be Interval::Daily, Interval::Monthly, and so on.
     * @return 
     */
    public function getImgCntPerPeriod($interval){
    }

    /**
     * 
     */
    public function getImgCntPerPatient(){
        
    }

    /**
     * @return
     */
    public function getFTPatientNameTestTypeAndTime(){
    }
?>