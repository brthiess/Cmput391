<?php
include_once 'login.php';
start_session();
 
// Unset all session values 
$_SESSION = array();
 
// Destroy session 
session_destroy();
header('Location: ../views/index.php');
?>