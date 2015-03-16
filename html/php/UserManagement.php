<?php
/**
 * UserManagement.php
 */

include_once 'Database.php';
include_once 'User.php';

/*!@class UserManagement
 * @brief 
 */
class UserManagement{
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
        if($user->clss != 'a'){
            // Not an admin, throw an exception.
            throw new Exception("User associated with username: ".$adminUserName.
                                " is not an admin.");
        }        
    }

    /**
     * @param user User object to be added. @see User
     * @throw Exception if one of the user attribute is wrong. e.g. user->personID is
     *                  not associated with a person, or $user->userName already exist.
     */
    public function addUser(User $user){
        try{
            $this->_db->addUser($user);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateUserPassword($userName, $password){
        
    }
}

?>