<?php
/**
 * InsertData.php
 * 
 * Populate SQL with data.
 */

include_once 'TestConstants.php';

include_once '../Database.php';
include_once '../Date.php';
include_once '../PacsImages.php';

$db = Database::instance();  // Acquire database instance

global $PEOPLE, $USERS, $FAMILY_DOCTORS, $RECORDS, $IMAGES;
        
// Add Person objects.
foreach ($PEOPLE as $person){
    $db->addPerson($person);
}

// Add User objects.
foreach ($USERS as $user){
    $db->addUser($user);
}

// Add Family doctors.
foreach ($FAMILY_DOCTORS as $fd){
    $db->addFamilyDoctor($fd);
}
        
// Add records.
foreach ($RECORDS as $r){
    $db->addRadiologyRecord($r);
}

// Add images.
foreach ($IMAGES as $r){
    $db->insertImage($r);
}

?>