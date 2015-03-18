<?php
/**
 * UserManagementTest.php
 */
include_once 'TestConstants.php';

include_once '../UserManagement.php';
include_once '../Database.php';
include_once '../Date.php';

/*!@class UserManagementTest
 * @test Unit tests for the UserManagement module. @see UserManagement
 * 
 * @group Cmput391Test
 */
class UserManagementTest extends PHPUnit_Framework_TestCase{
    private $_um = NULL;  // User management module.
    
    protected function setUp(){
        $db = Database::instance();  // Acquire database instance.
        
        global $PEOPLE, $USERS;
        
        // Only add Joe Shmoe.
        $db->addPerson($PEOPLE[0]);

        // Only add the admin as user.        
        $db->addUser($USERS[0]);
        
        $this->_um = new UserManagement("joeShmoe");
    }

    protected function tearDown(){
        $db = Database::instance();  // Acquire database instance.
        
        global $PEOPLE, $USERS;

        // Dereference hoping that the garbage collector will clean it up.
        $_um = NULL;

        // Only remove the added admin user.
        $db->removeUser($USERS[0]->userName);
        

        // Remove all the people.
        $db->removePerson($PEOPLE[0]->personID);
    }
    
    public function testAdminUse(){
        // Try to use UserManagement module with a valid admin.
        $success = true;
        try{
            // joeShmoe is a valid admin username.
            $_um = new UserManagement("joeShmoe");
        }catch(Exception $e){
            $success = false;
        }
        $this->assertEquals(true, $success);
    }

    public function testNonAdminUse(){
        // Try to use UserManagement module with non-admin. Make sure an exception is caught.
        $success = false;
        try{
            // chetManly is a patient.
            $this->_um = new UserManagement("chetManly");
        }catch(Exception $e){
            $success = true;
        }
        $this->assertEquals(true, $success);

        // Try to use UserManagement module with a non-user.
        $success = false;
        try{
            // killBill69 is an invalid admin username.
            $this->_um = new UserManagement("killBill69");
        }catch(Exception $e){
            $success = true;
        }
        $this->assertEquals(true, $success);
    }
    
    public function testAddUser(){
        global $USERS, $PEOPLE;
        $db = Database::instance();  // Acquire database instance.

        // Add a not-yet added user.
        $success = true;
        try{
            $db->addPerson($PEOPLE[1]);
            $this->_um->addUser($USERS[1]);
            $db->removeUser($USERS[1]->userName);
            $db->removePerson($PEOPLE[1]->personID);
        }catch(Exception $e){
            print $e->getMessage();
            $success = false;
        }
        $this->assertEquals(true, $success);

        // Add an already added user.
        $success = false;
        try{
            $this->_um->addUser($USERS[0]);
        }catch(Exception $e){
            $success = true;
        }
        $this->assertEquals(true, $success);
    }

    public function testUpdateUser(){
        global $USERS, $PEOPLE;
        $db = Database::instance();  // Acquire database instance.
        
        // Update existing user.
        $newPass = "asdf";
        $user = $USERS[1];
        $db->addPerson($PEOPLE[1]);
        $db->addUser($user);
        $user->password = $newPass;
        $this->_um->updateUser($user); // Change from 1234 to asdf.        
        
        // See if the password changed.
        $user = $db->getUser($user->userName);
        $db->removeUser($user->userName);  // Cleanup.
        $db->removePerson($PEOPLE[1]->personID);
        $this->assertEquals($newPass, $user->password);        
    }

    public function testRemoveUser(){
        global $USERS, $PEOPLE;
        $db = Database::instance();  // Acquire database instance.

        // Try adding and removing a user.
        $db->addPerson($PEOPLE[1]);
        $this->_um->addUser($USERS[1]);
        $this->_um->removeUser($USERS[1]->userName);
        $db->removePerson($PEOPLE[1]->personID);

        $success = false;
        try{
            var_dump($db->getUser($USERS[1]->userName));

            // At this point, user is not deleted. Delete it with the tested db code.
            $db->removeUser($USERS[1]->userName);
        }catch(Exception $e){
            // If getUser fails, that means UserManagement::removeUser works.
            $success = true;
            $db->removeUser($USERS[1]->userName);
        }
        
        $this->assertEquals(true, $success);
    } 

    public function testAddPerson(){
        global $PEOPLE;
        $db = Database::instance();  // Acquire database instance.

        $this->_um->addPerson($PEOPLE[6]);
        
        $success = true;
        try{
            //$db->getPerson($PEOPLE[6]->personID);
        }catch(Exception $e){
            $success = false;
        }
        $db->removePerson($PEOPLE[6]->personID);
        $this->assertEquals(true, $success);
    }

    public function testUpdatePerson(){
        global $PEOPLE;
        $db = Database::instance();  // Acquire database instance.
        
        $person = $PEOPLE[6];
        
        $this->_um->addPerson($person);
        $person->firstName = "BillyBurry";
        $this->_um->updatePerson($person);
        
        $success = true;
        $this->assertEquals("BillyBurry", $db->getPerson($person->personID)->firstName);
        $db->removePerson($person->personID);
        $this->assertEquals(true, $success);
    }

    public function testRemovePerson(){
        global $PEOPLE;
        $db = Database::instance();  // Acquire database instance.

        $person = $PEOPLE[6];
        $this->_um->addPerson($person);
        $this->_um->removePerson($person->personID);
        
        $success = false;
        try{
            var_dump($db->getPerson($person->personID));

            // At this point, person is not deleted. Delete it with the tested db code.
            $db->removePerson($person->personID);
        }catch(Exception $e){
            // If getPeople fails, that means UserManagement::removeUser works.
            $success = true;            
        }
        $this->assertEquals(true, $success);
    }

    public function testAddFamilyDoctor(){        
    }

    public function testUpdateFamilyDoctor(){
    }

    public function testRemoveFamilyDoctor(){
    }
}
?>