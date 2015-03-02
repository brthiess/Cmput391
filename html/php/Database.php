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
    public function getUser($userName, $password){
        $sqlstmt = 'SELECT * FROM users WHERE user_name='.Q($userName).' and '.
            'password='.Q($password).'';

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
     * @param user User object.
     * @return array of RadiologyRecords.
     * @throws Exception user not recognized.
     * @see User
     * 
     * This method first ensure that $user's password match for security reasons.
     * This is because anyone can call this and without check password,
     * security assumption failed.
     */
    public function getRadiologyRecords($user){
        // Validate user first. That is, ensure that all of User object
        // attribute are legitimate.
        if($user != $this->getUser($user->userName, $user->password)){
            throw new Exception('User not recognized. Access Denied.');
        }

        // At this point, user is validated, perform the appropriate query.
        $sqlStmt = NULL;
        switch($user->clss){
        case 'a':
            // User is admin, user can select all records.
            $sqlStmt = 'SELECT * FROM radiology_record';
            break;
        case 'p':
            // Can view only records that belongs to his/her.
            $sqlStmt = 'SELECT rr.* '.
                'FROM radiology_record rr JOIN users u ON rr.patient_id=u.person_id '.
                'WHERE u.person_id='.$user->personID;
            break;
        case 'd':
            // Can view records of his/her patients.
            $sqlStmt = 'SELECT rr.* '.
                'FROM radiology_record rr JOIN users u ON rr.doctor_id=u.person_id '.
                'WHERE u.person_id='.$user->personID;
            break;
        case 'r':
            // Can view records that he/she took with a patient.
            $sqlStmt = 'SELECT rr.* '.
                'FROM radiology_record rr JOIN users u ON rr.radiologist_id=u.person_id '.
                'WHERE u.person_id='.$user->personID;
            break;
        default:
            throw new Exception('Fatal Error: Check schema and php codes.');
            break;
        }
        
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
     * @param recordID id of the radiology_record instance that you want a ranking.
     * @return rank of the record associated with the recordID
     */
    public function getRecordRank($recordID){
        $sqlstmt = 'SELECT getRecordRank('.$recordID.','.'\'cyril|figgis\''.') as rank FROM dual';

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
            return -1;
        }        
        
        return $row["RANK"];
    }
}

?>