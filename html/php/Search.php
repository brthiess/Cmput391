<?php
/**
 * Search.php
 */

include_once 'Database.php';
include_once 'RadiologyRecord.php';

/*!@class Search
 * @brief Encapsulates Search requirements of this project. This includes, 
 *        filtering and sorting.
 */
class Search{
    private $_user=NULL; // User object. @see User


    private $_db=NULL;  // Database instance.

    /**
     * @param userName username of the user instantiating this class.
     * @param password password of the user instantiating this class.
     * @param dbInstance An instance of Database. Else, a singleton instance will
     *                   be used.
     * @throws Exception Due to security concerns, the assumption prior to instantiation
     *                   is that <userName, password> is not valid.
     */
    public function __construct($username, $password){
        $this->_db = Database::instance();

        // Verify the user, else throw an Exception.
        // TODO: When the login module is established, use that instead.
        $user = $this->_db->getUser($username, $password);
        if($user != False){        
            $this->_user = $user;
        }else{
            throw new Exception('username or password is wrong.');
        }
    }

    /**
     * @return Tuple(s) of RadiologyRecord that is accessible to current user.
     * @throws Exception, indicating that user class of this object is not recognized.
     *                    Such exception is fatal, review schema design and 
     *                    code consistency in Business Tier.
     */
    final public function searchWithSecurityFilter(){
        return $this->_db->getRadiologyRecords($this->_user);
    }

    /**
     * @param keywords array of string.
     * @return Tuple(s) of RadiologyRecord that is accessible to current user and match
     *         keywords.
     * 
     */
    public function searchWithKeywordFilter(array $keywords){
        // Acquire records.
        $records = $this->searchWithSecurityFilter();

        // For each keywords, see if a match in keywords.
        // Note that for each word, see if Edit Distance is within 1, to accomodate
        // some mistype.
        $rv = array();  // This will contain the matched tuples.
        foreach($keywords as $w){
            foreach($records as $r){
                $match = False;

                // Check test type attribute of r.
                $testTypeStrings = 
                    preg_split("/[\.,-\/#!$%\^&\*;:{}=\-_`~() ]/", $r->testType);
                foreach($testTypeStrings as $tts){
                    if($tts == $w){
                        $match = True;
                        $rv[] = $r;
                        break;
                    }
                }

                if($match) break;

                // Check diagnosis attritube of r.
                $diagnosisStrings = 
                    preg_split("/[\.,-\/#!$%\^&\*;:{}=\-_`~() ]/", $r->diagnosis);
                foreach($diagnosisStrings as $ds){
                    if($ds == $w){
                        $match = True;
                        $rv[] = $r;
                        break;
                    }
                }
                
                if($match) break;
                
                // Check description attribute of r.
                $descriptionStrings = 
                    preg_split("/[\.,-\/#!$%\^&\*;:{}=\-_`~() ]/", $r->description);
                foreach($descriptionStrings as $ds){
                    if($ds == $w){
                        $match = True;
                        $rv[] = $r;
                        break;
                    }
                }

                if($match) break;
            }
        }

        return $rv;
    }

    public function sortByDefaultRankning(){
    }

    public function sortByTimingAscending(){
    }

    public function sortByTimingDescending(){
    }
}

?>