<?php
/**
 * User.php
 */

include_once 'Const.php';
include_once 'Date.php';

/*!@class UserClass
 * @brief Serves as an enum type for users.class.
 */
class UserClass{
    const admin = 'a';
    const patient = 'p';
    const doctor = 'd';
    const radiologist = 'r';
}

/*!@class User
 * @brief PHP representation of user schema.
 */
class User{
    public $userName = NULL;
    public $password = NULL;
    public $clss = NULL;  // User class.
    public $personID = NULL;
    public $dateRegistered = NULL;

    public function __construct(
        $username, $password, $clss, $personID, Date $dateRegistered){
        $this->username = $username;
        $this->password = $password;
        $this->clss = $clss;
        $this->personID = $personID;
        $this->dateRegistered = $dateRegistered;
    }
}

?>