<?php
/**
 * RemoveData.php
 */

include_once 'TestConstants.php';

include_once '../Database.php';
include_once '../Date.php';
include_once '../PacsImages.php';

$db = Database::instance();  // Acquire database instance.
        
// Deallocate database on reverse order of intialization.        
global $PEOPLE, $USERS, $FAMILY_DOCTORS, $RECORDS, $IMAGES;

foreach ($IMAGES as $r){
    $db->removeImage($r->imageID);
}
        
foreach ($RECORDS as $r){
    $db->removeRadiologyRecord($r->recordID);
}

foreach ($FAMILY_DOCTORS as $fd){
    $db->removeFamilyDoctor($fd);
}
        
foreach ($USERS as $user){
    $db->removeUser($user->userName);
}

foreach ($PEOPLE as $person){
    $db->removePerson($person->personID);
}
?>