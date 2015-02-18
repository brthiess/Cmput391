<?php
/**
 * User.php
 */

/*!@class User
 * @brief PHP representation of user schema.
 */
class User{
    public $userName = NULL;
    public $password = NULL;
    public $clss = NULL;
    public $personID = NULL;
    public $dateRegistered = NULL;

    public function __construct($userName, $password, $clss, $personID, $dateRegistered){
        $this->userName = $userName;
        $this->password = $password;
        $this->clss = $clss;
        $this->personID = $personID;
        $this->dateRegistered = $dateRegistered;        
    }
}

?>