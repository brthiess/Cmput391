<?php
/**
 * Person.php
 */

/*!@class Person
 * @brief PHP representation of person schema.
 */
class Person{
    public $personID = NULL;
    public $firstName = NULL;
    public $lastName = NULL;
    public $address = NULL;
    public $email = NULL;
    public $phone = NULL;

    public function __construct($personID, $firstName, $lastName, $address, $email, $phone){
        $this->personID = $personID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
    }
}

?>