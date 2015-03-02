<?php
/**
 * Search.php
 */

include_once 'Database.php';
include_once 'RadiologyRecord.php';
include_once 'common.php';

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
     * @param keywords separated by spaces or OR(|), AND(&).
     * @param orderByTiming if true, results are ordered by timing, otherwise ordered by ranking.
     * @return array of results.
     */
    public function searchWithKeywords($keywords, $orderByTiming=false){
        if($orderByTiming==false){
        }else{
        }
    }

    /**
     * @param timePeriod array containing two Dates.
     * @param orderByTiming if true, results are ordered by timing, otherwise ordered by ranking.
     * @return array of results.
     */
    public function searchWithPeriod(array $timePeriod, $orderByTiming=false){
        if($orderByTiming==false){
        }else{
        }
    }
}

?>