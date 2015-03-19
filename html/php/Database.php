<?php
/**
 * Database.php
 */

include_once 'Person.php';
include_once 'User.php';
include_once 'RadiologyRecord.php';
include_once 'FamilyDoctor.php';
include_once 'common.php';

/**
 * The following are db credentials. If you want a quick db, I suggest looking
 * into docker and use the following docker file: 
 * https://github.com/wnameless/docker-oracle-xe-11g
 */
const USER_NAME = 'system';
const PASS = 'oracle';
const CONNECTION_STRING = "localhost:49161/xe";

/*!@class Database
 * @brief Encapsulates the Database Tier of the 3-tier architecture.
 *
 * Note that this assumes that the schemas are already created.
 */
class Database {
    private $_username = NULL;
    private $_connectionString = NULL;

    private $_connection = NULL;  // Variable representing database connection.
        
    /**
     * Use singleton method, instance() instead of this.
     * @param username username in oracle db.
     * @param password password in oracle db.
     * @param connectionString connectionString to oracle db.
     */
    private function __construct($username, $password, $connectionString){
        $this->_userName = $username;
        $this->_connectionString = $connectionString;
        $this->_connection = oci_connect($username, $password, $connectionString);
        if (!$this->_connection) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            throw new Exception("Database connection failed. Please check constructor arguments.");
        }else{
            print "Database connection established.\n";
        }
    }

    /**
     * Database Singleton.
     */
    public static function instance(){
        static $inst = NULL;        
        
        if($inst == NULL){
            $inst = new Database(USER_NAME, PASS, CONNECTION_STRING);
        }
        
        return $inst;
    }
    
    /**
     * Destroys resource. This upholds RAII (just worry about initialization).
     */
    public function __destruct(){
        if($this->_connection != NULL){
            oci_close($this->_connection);
            $this->_connection = NULL;
            print "Database connection destroyed.\n";
        }
    }

    public function getPerson($personID){
        $sqlstmt = 'SELECT * FROM persons WHERE person_id='.Q($personID);        

        $stid = oci_parse($this->_connection, $sqlstmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Perform the logic of the query
        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $row = oci_fetch_array($stid, OCI_ASSOC);
        if ($row == False){
            throw(new Exception("User doesn't exist"));
        }
        return new Person($row['PERSON_ID'], $row['FIRST_NAME'], $row['LAST_NAME'],
                          $row['ADDRESS'], $row['EMAIL'], $row['PHONE']);
    }
    
    /**
     * @param person which is a Person object. Make personID null to make id assignment automated.
     * @throws Exception, something about the Person properties is wrong.
     * @see Person
     */
    public function addPerson(Person $person){ 
        $sqlstmt = 'INSERT INTO persons VALUES('.
                 ($person->personID == NULL? 'persons_seq.nextval' : $person->personID).', '.
                 Q($person->firstName).', '.
                 Q($person->lastName).', '.
                 Q($person->address).', '.
                 Q($person->email).', '.
                 Q($person->phone).')';

        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * @param person updates the corresponding sqltuples.
     */
    public function updatePerson(Person $person){
        $sqlStmt = "UPDATE persons ".
                 "SET first_name='".$person->firstName."', ".
                 "last_name='".$person->lastName."', ".
                 "address='".$person->address."', ".
                 "email='".$person->email."', ".
                 "phone='".$person->phone."' ".
                 "WHERE person_id = '".$person->personID."'";
        $rv = oci_execute(oci_parse($this->_connection, $sqlStmt));
    }

    /**
     * @param id of the Person to be removed.
     * @throws Exception, id doesn't exist.
     */
    public function removePerson($id){
        $sqlstmt = 'DELETE FROM persons WHERE person_id='.$id.'';
        
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }
    
    /**
     * @param user which is a User object.
     * @throws Exception, something about the user properties is wrong. For instance,
     *         user already exist.
     * @see User
     */
    public function addUser(User $user){
        $sqlStmt = 'INSERT INTO users VALUES('.
                 Q($user->userName).', '.
                 Q($user->password).', '.
                 Q($user->clss).', '.
                 $user->personID.', '.
                 Q($user->dateRegistered).')';

        $rv = oci_execute(oci_parse($this->_connection, $sqlStmt));        
    }
    
    /**
     * @param user updates the user tuple that matches user->userName.
     */
    public function updateUser(User $user){
        $sqlStmt = "UPDATE users ".
                 "SET password='".$user->password."', class='".$user->class."' ".
                 ", person_id='".$user->person_id."', date_registered='".$user->dateRegistered."' ".
                 "WHERE user_name='".$user->userName."'";
        $rv = oci_execute(oci_parse($this->_connection, $sqlStmt));
    }
    
    /**
     * @param userName of the user to remove.
     * @throws Exception if user with a userName does not exist.
     */
    public function removeUser($userName){
        $sqlstmt = 'DELETE FROM users WHERE user_name='.Q($userName).'';
        
        $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
    }

    /**
     * @param userName of the user to be acquired.
     * @return User corresponding to the userName provided.
     * @throws Throws exception if user doesn't exist.
     * @see User
     */
    public function getUser($userName){
        $sqlstmt = 'SELECT * FROM users WHERE user_name='.Q($userName);        

        $stid = oci_parse($this->_connection, $sqlstmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Perform the logic of the query
        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $row = oci_fetch_array($stid, OCI_ASSOC);
        if ($row == False){
            throw(new Exception("User doesn't exist"));
        }        
        return new User($row['USER_NAME'], $row['PASSWORD'], $row['CLASS'], 
                        $row['PERSON_ID'], new Date($row['DATE_REGISTERED']));
    }
    
    /**
     * @param fd FamilyDoctor object.
     * @throws Exception if user <fd->doctorID, fd->patientID> pair already exist.
     * @see FamilyDoctor
     */
    public function addFamilyDoctor(FamilyDoctor $fd){
        $sqlstmt = 'INSERT INTO family_doctor VALUES('.
                 $fd->doctorID.', '.
                 $fd->patientID.')';

        $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
    }

    public function removeFamilyDoctor(FamilyDoctor $fd){
        $sqlstmt = 'DELETE FROM family_doctor WHERE doctor_id='.$fd->doctorID.' and '.
                 'patient_id='.$fd->patientID;
        
        $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
    }

    public function addRadiologyRecord(RadiologyRecord $rr){
        $sqlstmt = 'INSERT INTO radiology_record VALUES('.
                 commaSeparatedString(
                     array(($rr->recordID==NULL? 'records_seq.nextval' : $rr->recordID),
                           $rr->patientID, $rr->doctorID, 
                           $rr->radiologistID, Q($rr->testType), Q($rr->prescribingDate), 
                           Q($rr->testDate), Q($rr->diagnosis), Q($rr->description))).')';
            
        $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));            
    }

    public function removeRadiologyRecord($recordID){
        $sqlstmt = 'DELETE FROM radiology_record WHERE record_id='.$recordID.'';
        
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }
    
    /**
     * @param userName
     * @return array of RadiologyRecords that is accessible to the user.
     * @throws Exception user not recognized.
     */
    public function getRadiologyRecords($userName){
        $sqlStmt = "SELECT * FROM TABLE(getRadiologyRecords('".$userName."'))";
                                                                
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        $rv = oci_execute($stid);
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);        
        return $rv;
    }
    
    /**
     * @param keywords string of keywords.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKeywordsByRank($userName, $keywords){
        $sqlStmt = "SELECT * FROM TABLE(searchWithKeywordsByRank('".$userName."','".
                 $keywords."'))";
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        $rv = oci_execute($stid);        
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);        
        return $rv;
    }
    
    /**
     * @param keywords string of keywords.
     * @param true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by test_date.
     */
    public function searchWithKeywordsByTime($userName, $keywords, $descending=True){
        $sqlStmt = "SELECT * FROM TABLE(searchWithKeywordsByTime('".$userName."','".
                 $keywords."','".($descending? "TRUE" : "FALSE" )."'))";
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        $rv = oci_execute($stid);        
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);        
        return $rv;
        
    }

    /**
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @param descending true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by test_date.
     */
    public function searchWithPeriodByTime($userName, Date $d1, Date $d2, $descending=True){
        $sqlStmt = "SELECT * FROM TABLE(searchWithPeriodByTime('".$userName."','".
                 $d1."','".$d2."','".($descending? "TRUE" : "FALSE" )."'))";
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        $rv = oci_execute($stid);
            
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);        
        return $rv;        
    }

    /**
     * @param keywords string of keywords.
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @return table of radiology_records that matches the given keywords, 
     *         and test taken within d1 and d2, ordered by rank.
     */
    public function searchWithKPByRank($userName, $keywords, Date $d1, Date $d2){
        $sqlStmt = "SELECT * FROM TABLE(searchWithKPByRank('".$userName."','".
                 $keywords."','".$d1."','".$d2."'))";
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        try{
            $rv = oci_execute($stid);
        }catch(Exception $e){
            throw $e;
        }
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);  
        return $rv;
    }

    /**
     * @param keywords string of keywords.
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @param desencending true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKPByTime($userName, $keywords, Date $d1, Date $d2, $descending=True){
        $sqlStmt = "SELECT * FROM TABLE(searchWithKPByTime('".$userName."','".
                 $keywords."','".$d1."','".$d2."','".($descending? "TRUE" : "FALSE" )."'))";
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        try{
            $rv = oci_execute($stid);
        }catch(Exception $e){
            throw $e;
        }
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new RadiologyRecord(
                      $row['RECORD_ID'],
                      $row['PATIENT_ID'],
                      $row['DOCTOR_ID'],
                      $row['RADIOLOGIST_ID'],
                      $row['TEST_TYPE'],
                      new Date($row['PRESCRIBING_DATE']),
                      new Date($row['TEST_DATE']),
                      $row['DIAGNOSIS'],
                      $row['DESCRIPTION']
                  );
        }

        oci_free_statement($stid);  
        return $rv;
    }

    /**
     * @param doctorID
     * @return array of Person with doctor. @see Person
     */
    public function getPeopleWithDoctor($doctorID){
        $sqlStmt = "SELECT p.* FROM family_doctor fd JOINS persons p ON fd.patient_id = p.person_id ".
                 "WHERE doctor_id=".$doctorID;
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        try{
            $rv = oci_execute($stid);
        }catch(Exception $e){
            throw $e;
        }
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new Person(
                      $row['PERSON_ID'],
                      $row['FIRST_NAME'],
                      $row['LAST_NAME'],
                      $row['ADDRESS'],
                      $row['EMAIL'],
                      $row['PHONE']
                  );
        }

        oci_free_statement($stid);  
        return $rv;
    }

    /**
     * @param patientID
     * @return array of Doctor that have the Patient with the corresponding patientID.
     * @see Doctor.
     */
    public function getDoctorWithPatient($patientID){
        $sqlStmt = "SELECT fd.* FROM family_doctor fd JOINS persons p ON fd.patient_id = p.person_id ".
                 "WHERE person_id=".$patientID;
        
        $stid = oci_parse($this->_connection, $sqlStmt);
        if (!$stid) {
            $e = oci_error($this->_connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        try{
            $rv = oci_execute($stid);
        }catch(Exception $e){
            throw $e;
        }
        
        $rv = array();
        while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false){
            $rv[] = 
                  new FamilyDoctor(
                      $row['DOCTOR_ID'],
                      $row['PATIENT_ID']
                  );
        }

        oci_free_statement($stid);  
        return $rv;
    }    
}

?>