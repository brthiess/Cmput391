<?php
/**
 * UserManagement.php
 */

include_once 'Database.php';
include_once 'User.php';
include_once 'Person.php';
include_once 'FamilyDoctor.php';

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
        if($user->clss != UserClass::admin){
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
        $this->_db->addUser($user);
    }
    
    /**
     * @param user User object to update the corresponding sql tuple.
     * @throws If user->userName doesn't exist in sql.
     */
    public function updateUser(User $user){
        $this->_db->updateUser($user);
    }

    /**
     * @param userName, removes the corresponding user tuple with the same userName.
     */
    public function removeUser($userName){
        $this->_db->removeUser($userName);
    }

    public function addPerson(Person $person){
        $this->_db->addPerson($person);
    }

    public function updatePerson(Person $person){
        $this->_db->updatePerson($person);
    }

    public function removePerson($personID){
        $this->_db->removePerson($personID);
    }

    public function addFamilyDoctor(FamilyDoctor $familyDoctor){
        $this->_db->addFamilyDoctor($familyDoctor);
    }

    public function removeFamilyDoctor(FamilyDoctor $familyDoctor){
        $this->_db->removeFamilyDoctor($familyDoctor);
    }
}

?>