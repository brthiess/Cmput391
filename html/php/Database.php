<?php
/**
 * Database.php
 */

include_once 'Person.php';
include_once 'User.php';
include_once 'RadiologyRecord.php';
include_once 'FamilyDoctor.php';
include_once 'common.php';
include_once 'PacsImages.php';

/**
 * The following are db credentials. If you want a quick db, I suggest looking
 * into docker and use the following docker file: 
 * https://github.com/wnameless/docker-oracle-xe-11g
 */
const USER_NAME = 'brad';
const PASS = 'Brad';
const CONNECTION_STRING = "localhost/xe";

/*!@class Database
 * @brief Encapsulates the Database Tier of the 3-tier architecture.
 *
 * Note that this assumes that the schemas are already created.
 */
class Database {
    private $_username = NULL; // Oracle Username.
    private $_connectionString = NULL;  // e.g. "localhost:49161/xe"
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
        $this->createConnection($username, $password, $connectionString);
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
    public function __destruct(){ $this->destroyConnection(); }
    
    /**
     * @param username
     * @param password
     * @param connectionString
     * @throws exception if error occurs.
     */
    public function createConnection($username, $password, $connectionString){
        $this->_connection = oci_connect($username, $password, $connectionString);
        if (!$this->_connection) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            throw new Exception("Database connection failed. Please check constructor arguments.");
        }else{
            //print "Database connection established.\n";
        }
    }

    /**
     * Destroys current connection.
     */
    public function destroyConnection(){
        if($this->_connection != NULL){
            oci_close($this->_connection);
            $this->_connection = NULL;
            //print "Database connection destroyed.\n";
        }
    }
    
    /**
     * @param sqlStmt SQL stmt to be executed.
     * @return array of tuples, by capitlized column name.
     */
    public function executeQuery($sqlStmt){
        $stid = oci_parse($this->_connection, $sqlStmt);
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
        
        $res = null;
        try{
            oci_fetch_all($stid, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        }catch(Exception $e){
            return array();
        }
        
        return $res;
    }

    public function executeQueryWithBindings($sqlStmt, array $inBinding, array &$outBinding){
        $stid = oci_parse($this->_connection, $sqlStmt);
        
        foreach($inBinding as $key => $bind){
            oci_bind_by_name($stid, $key, $bind);
        }

        foreach($outBinding as $key => &$bind){
            oci_bind_by_name($stid, $key, $bind, 100);
        }

        try{
            $rv = oci_execute($stid);
        }catch(Exception $e){
            throw $e;
        }
        
        $res = null;
        try{
            oci_fetch_all($stid, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        }catch(Exception $e){
            return array();
        }
        
        return $res;
    }

    /**
     * @param 
     */
    public function getPerson($personID){
        $sqlStmt = 'SELECT * FROM persons WHERE person_id='.Q($personID);        

        $row = $this->executeQuery($sqlStmt)[0];
        if(sizeof($row) == 0){
            throw(new Exception("Person ID: ".$personID." don't have a corresponding row."));
        }
        return new Person($row['PERSON_ID'], $row['FIRST_NAME'], $row['LAST_NAME'],
                          $row['ADDRESS'], $row['EMAIL'], $row['PHONE']);
    }
    
    /**
     * @param person which is a Person object. Make personID null to make id assignment automated.
     * @return id of the person inserted. This is helpful when personID is set to null and sql 
     *         automatically generates one for you.
     * @throws Exception, something about the Person properties is wrong.
     * @see Person
     */
    public function addPerson(Person $person){
        $id = $person->personID == NULL? "NULL" : $person->personID;
        $autoID = $person->personID == NULL? "TRUE" : "FALSE";
        $p = "insertPerson(persons_rt(".
           $id.", ".
           Q($person->firstName).", ".
           Q($person->lastName).", ".
           Q($person->address).", ".
           Q($person->email).", ".
           Q($person->phone)."),'".$autoID."')";
                
        $sqlStmt = "BEGIN :r := ".$p."; END;";
        $outBinding = array(":r"=>$r);
        $this->executeQueryWithBindings($sqlStmt, array(), $outBinding); 

        return $outBinding[":r"];
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
        $this->executeQuery($sqlStmt);
    }

    /**
     * @param id of the Person to be removed.
     * @throws Exception, id doesn't exist.
     */
    public function removePerson($id){
        $sqlStmt = 'DELETE FROM persons WHERE person_id='.$id.'';
        $this->executeQuery($sqlStmt);
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
        $this->executeQuery($sqlStmt);
    }
    
    /**
     * @param user updates the user tuple that matches user->userName.
     */
    public function updateUser(User $user){
        $sqlStmt = "UPDATE users ".
                 "SET password='".$user->password."', class='".$user->class."' ".
                 ", person_id='".$user->person_id."', date_registered='".$user->dateRegistered."' ".
                 "WHERE user_name='".$user->userName."'";
        $this->executeQuery($sqlStmt);
    }
    
    /**
     * @param userName of the user to remove.
     * @throws Exception if user with a userName does not exist.
     */
    public function removeUser($userName){
        $sqlStmt = 'DELETE FROM users WHERE user_name='.Q($userName).'';
        $this->executeQuery($sqlStmt);
    }

    /**
     * @param userName of the user to be acquired.
     * @return User corresponding to the userName provided.
     * @throws Throws exception if user doesn't exist.
     * @see User
     */
    public function getUser($userName){
        $sqlStmt = 'SELECT * FROM users WHERE user_name='.Q($userName);        
        
        $row = $this->executeQuery($sqlStmt);
        if($row == null){
            return false;
        }     
		$user = $row[0];
        return new User($user['USER_NAME'], $user['PASSWORD'], $user['CLASS'], 
                        $user['PERSON_ID'], new Date($user['DATE_REGISTERED']));
    }
    
    /**
     * @param fd FamilyDoctor object.
     * @throws Exception if user <fd->doctorID, fd->patientID> pair already exist.
     * @see FamilyDoctor
     */
    public function addFamilyDoctor(FamilyDoctor $fd){
        $sqlStmt = 'INSERT INTO family_doctor VALUES('.
                 $fd->doctorID.', '.
                 $fd->patientID.')';
        $this->executeQuery($sqlStmt);
    }

    /**
     * @param fd Family doctor object to be removed.
     * @throws Exception if fd tuple to be remove doesn't exist in SQL.
     */
    public function removeFamilyDoctor(FamilyDoctor $fd){
        $sqlStmt = 'DELETE FROM family_doctor WHERE doctor_id='.$fd->doctorID.' and '.
                 'patient_id='.$fd->patientID;
        $this->executeQuery($sqlStmt);
    }
    
    /**
     * @param rr RadiologyRecord object. Set rr->recordID = null to let sql automatically
     *           assign recordID.
     * @return The assigned recordID.
     * @throws If radiology record already exist or one of the parameters are invalid. Billion
     *         ways to get that wrong so I won't enumerate.
     */
    public function addRadiologyRecord(RadiologyRecord $rr){        
        $id = $rr->recordID == NULL? "NULL" : $rr->recordID;
        $autoID = $id == "NULL"? "TRUE" : "FALSE";
        $p = "insertRadiologyRecord(radiology_record_rt(".
           $id.", ".
           $rr->patientID.", ".
           $rr->doctorID.", ".
           $rr->radiologistID.", ".
           Q($rr->testType).", ".
           Q($rr->prescribingDate).", ".
           Q($rr->testDate).", ".
           Q($rr->diagnosis).", ".
           Q($rr->description)."),'".$autoID."')";

        $sqlStmt = "BEGIN :r := ".$p."; END;";
        $outBinding = array(":r"=>$r);
        $this->executeQueryWithBindings($sqlStmt, array(), $outBinding); 
        
        return $outBinding[":r"];
    }
    
    /**
     * @param recordID id of the radiology_record tuple to be deleted.
     * @throws if tuple associated with a recordID doesn't exist.
     */
    public function removeRadiologyRecord($recordID){
        $sqlStmt = 'DELETE FROM radiology_record WHERE record_id='.$recordID.'';
        $this->executeQuery($sqlStmt);
    }
    
    /**
     * @param userName
     * @return array of RadiologyRecords that is accessible to the user.
     * @throws Exception user not recognized.
     */
    public function getRadiologyRecords($userName){
        $sqlStmt = "SELECT * FROM TABLE(getRadiologyRecords('".$userName."'))";
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        return $rv;
    }
    
    /**
     * @param keywords string of keywords.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKeywordsByRank($userName, $keywords){
        $sqlStmt = "SELECT * FROM TABLE(searchWithKeywordsByRank('".$userName."','".
                 $keywords."'))";
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        return $rv;}

    /**
     * @param doctorID
     * @return array of Person with doctor. @see Person
     */
    public function getPeopleWithDoctor($doctorID){
        $sqlStmt = "SELECT p.* FROM family_doctor fd JOINS persons p ON fd.patient_id = p.person_id ".
                 "WHERE doctor_id=".$doctorID;                
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
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
        
        $rows = $this->executeQuery($sqlStmt);        
        $rv = array();
        foreach($rows as $row){
            $rv[] = 
                  new FamilyDoctor($row['DOCTOR_ID'], $row['PATIENT_ID']);
        }        
        return $rv;
    }
    
    public function insertImage(PacsImages $pi){
        $sql = "INSERT INTO ".
             "pacs_images(record_id, image_id, thumbnail, regular_size, full_size)".
             " VALUES(".
             $pi->recordID.", ".
             $pi->imageID.", ".
             "EMPTY_BLOB(),".
             "EMPTY_BLOB(),".
             "EMPTY_BLOB()".
             ") RETURNING thumbnail, regular_size, full_size ".
             "INTO :thumbnail, :regular_size, :full_size";        
        //echo $sql . PHP_EOL;
        $result = oci_parse($this->_connection, $sql);
        $thumbnailBlob = oci_new_descriptor($this->_connection, OCI_D_LOB);
        $regularSizeBlob = oci_new_descriptor($this->_connection, OCI_D_LOB);
        $fullSizeBlob = oci_new_descriptor($this->_connection, OCI_D_LOB);
        oci_bind_by_name($result, ":thumbnail", $thumbnailBlob, -1, OCI_B_BLOB);
        oci_bind_by_name($result, ":regular_size", $regularSizeBlob, -1, OCI_B_BLOB);
        oci_bind_by_name($result, ":full_size", $fullSizeBlob, -1, OCI_B_BLOB);
        oci_execute($result, OCI_DEFAULT);

        if(!$thumbnailBlob->save($pi->thumbnail) ||
           !$regularSizeBlob->save($pi->regularSize) ||
           !$fullSizeBlob->save($pi->fullSize)) {
            oci_rollback($this->_connection);
        }
        else {
            oci_commit($this->_connection);
        }        
        
        oci_free_statement($result);
        $thumbnailBlob->free();
        $regularSizeBlob->free();
        $fullSizeBlob->free();
    }

    public function removeImage($recordID, $imageID){
        $sqlstmt = "DELETE FROM pacs_images WHERE record_id=".$recordID." AND ".
                 "image_id=".$imageID;
        
        try{
            $rv = oci_execute(oci_parse($this->_connection, $sqlstmt));
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getImage($recordID, $imageID){
        $sqlStmt = "SELECT * FROM pacs_images WHERE record_id=".$recordID." AND ".
                "image_id=".$imageID;

        $stid = oci_parse($this->_connection, $sqlStmt);
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

        $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS);
        if ($row == False){
            throw(new Exception("User doesn't exist"));
        }        
        return new PacsImages($row['RECORD_ID'], $row['IMAGE_ID'], $row['THUMBNAIL'], 
                              $row['REGULAR_SIZE'], $row['FULL_SIZE']);
    }
}

?>