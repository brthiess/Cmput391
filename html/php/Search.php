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
            print "Access Granted.";
            $this->_user = $user;
        }else{
            throw new Exception('username or password is wrong.');
        }
    }

    /**
     * @return Tuple(s) of RadiologyRecord
     * @throws Exception, indicating that user class of this object is not recognized.
     *                    Such exception is fatal, review schema design and 
     *                    code consistency in Business Tier.
     */
    final public function searchWithSecurityFilter(){
        return getRadiologyRecords($this->_user);
    }

    public function searchConditionFilter(){
    }

    public function sortByDefaultRankning(){        
    }

    public function sortByTimingAscending(){
    }

    public function sortByTimingDescending(){
    }
}

?>