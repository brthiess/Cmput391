<?php
/**
 * UserManagementTest.php
 */
include_once 'TestConstants.php';

include_once '../UserManagement.php';
include_once '../Database.php';
include_once '../Date.php';

class UserManagementTest extends PHPUnit_Framework_TestCase{
    private $_um = NULL;  // User management module.
    
    protected function setUp(){
        $db = Database::instance();  // Acquire database instance.
        
        global $PEOPLE, $USERS;
        
        // Add Person objects.
        foreach ($PEOPLE as $person){
            $db->addPerson($person);
        }

        // Add all people.
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
        foreach ($PEOPLE as $person){
            $db->removePerson($person->personID);
        }
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
        global $USERS;
        $db = Database::instance();  // Acquire database instance.

        // Add a not-yet added user.
        $success = true;
        try{
            $this->_um->addUser($USERS[1]);
            $db->removeUser($USERS[1]->userName);
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

    public function testUpdateUserPassword(){
        global $USERS;
        $db = Database::instance();  // Acquire database instance.

        // Update existing user.
    }

    public function testRemoveUser(){
    }

    public function testAddPerson(){
    }

    public function testUpdatePerson(){
    }

    public function testRemovePerson(){
    }

    public function testAddFamilyDoctor(){
    }

    public function testUpdateFamilyDoctor(){
    }

    public function testRemoveFamilyDoctor(){
    }
}
?>