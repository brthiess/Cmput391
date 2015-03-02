<?php
/**
 * Database.php
 */

include_once 'Person.php';
include_once 'User.php';
include_once 'RadiologyRecord.php';
include_once 'FamilyDoctor.php';
include_once 'common.php';

const USER_NAME = 'C##PRACTICE01';
const PASS = '1234';
const CONNECTION_STRING = "192.168.0.23:1521/orcl.localdomain";

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
    
    /**
     * @param person which is a Person object.
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
     * @param id of the Person to be removed.
     * @throws Exception, id doesn't exist.
     */
    public function removePerson($id){
        $sqlstmt = 'DELETE FROM persons WHERE person_id='.$id;
        
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
        $sqlstmt = 'INSERT INTO users VALUES('.
            Q($user->userName).', '.
            Q($user->password).', '.
            Q($user->clss).', '.
            $user->personID.', '.
            Q($user->dateRegistered).')';

        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }
    
    /**
     * @param userName of the user to remove.
     * @throws Exception if user with a userName does not exist.
     */
    public function removeUser($userName){
        $sqlstmt = 'DELETE FROM users WHERE user_name='.Q($userName).'';
        
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * @param userName of the user to be acquired.
     * @param password of the user to be acquired. This is for security reasons.
     * @return User object. (Note this can't be an array since userName is key).
     *         False if user does not exist.
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
            return False;
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

        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }

    public function removeFamilyDoctor(FamilyDoctor $fd){
        $sqlstmt = 'DELETE FROM family_doctor WHERE doctor_id='.$fd->doctorID.' and '.
            'patient_id='.$fd->patientID;
        
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }

    public function addRadiologyRecord(RadiologyRecord $rr){
        $sqlstmt = 'INSERT INTO radiology_record VALUES('.
            commaSeparatedString(
                array(($rr->recordID==NULL? 'records_seq.nextval' : $rr->recordID),
                $rr->patientID, $rr->doctorID, 
                $rr->radiologistID, Q($rr->testType), Q($rr->prescribingDate), 
                Q($rr->testDate), Q($rr->diagnosis), Q($rr->description))).')';
            
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
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
        $sqlStmt = 'SELECT * FROM TABLE(getRadiologyRecords('.Q($userNmae.'))';
        
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
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKeywordsByRank($keywords){
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
     * @param true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by test_date.
     */
    public function searchWithKeywordsByTime($keywords, $descending=True){
        
    }

    /**
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @param descending true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by test_date.
     */
    public function searchWithPeriodByTime(Date $d1, Date $d2, $descending=True){
        
    }

    /**
     * @param keywords string of keywords.
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKPByRank($keywords, Date $d1, Date $d2){
        
    }

    /**
     * @param keywords string of keywords.
     * @param d1 lowerbound of date to be included.
     * @param d2 upperbound of date to be included.
     * @param desencending true for descending ordering, false otherwise.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKPByTime($keywords, Date $d1, Date $d2, $descending=True){
        
    }
}

?>