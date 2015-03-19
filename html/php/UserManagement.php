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
     * @param userName
     * @return User corresponding to the userName provided. @see User
     * @throws Throws exception if user doesn't exist.
     */
    public function getUser($userName){
        $this->_db->getUser($userName);
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
     * @throws Exception if userName doesn't correspond to a user in sql.
     */
    public function removeUser($userName){
        $this->_db->removeUser($userName);
    }
    
    /**
     * @param personID
     * @return returns the corresponding Person object. @see Person
     */
    public function getPerson($personID){
        $this->_db->getPerson($personID);
    }

    /**
     * @param person object to be added.
     * @throws Exception if person added already exist.
     */
    public function addPerson(Person $person){
        $this->_db->addPerson($person);
    }

    /**
     * @param person updates the corresponding tuple in sql.
     * @throws Exception if person doesn't exist.
     */
    public function updatePerson(Person $person){
        $this->_db->updatePerson($person);
    }

    /**
     * @param personID corresponds to the tuple in sql to be deleted.
     * @throws Exception if personID don't correspond to a tuple in sql.
     */
    public function removePerson($personID){
        $this->_db->removePerson($personID);
    }

    /**
     * @param familyDoctor adds the family doctor.
     */
    public function addFamilyDoctor(FamilyDoctor $familyDoctor){
        $this->_db->addFamilyDoctor($familyDoctor);
    }

    /**
     * @param familyDoctor removes the corresponding sql tuple.
     */
    public function removeFamilyDoctor(FamilyDoctor $familyDoctor){
        $this->_db->removeFamilyDoctor($familyDoctor);
    }

    /**
     * @param doctorID
     * @return array of Person with doctor. @see Person
     */
    public function getPeopleWithDoctor($doctorID){
        $this->_db->getPeopleWithDoctor($doctorID);
    }
    
    /**
     * @param patientID
     * @return array of Doctor that have the Patient with the corresponding patientID.
     * @see Doctor.
     */
    public function getDoctorWithPatient($patientID){
        $this->_db->getDoctorWithPatient($patientID);
    }
}

?>