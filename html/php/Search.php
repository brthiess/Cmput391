<?php
/**
 * Search.php
 */

include_once 'Database.php';
include_once 'RadiologyRecord.php';
include_once 'common.php';
include_once 'Date.php';

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
     * @param keywords string of keywords.
     * @return table of radiology_records that matches the given keywords, ordered by rank.
     */
    public function searchWithKeywordsByRank($keywords){
        
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