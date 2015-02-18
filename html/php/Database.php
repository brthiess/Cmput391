<?php
/**
 * Database.php
 */

function connect(){
    $conn = oci_connect("C##PRACTICE01", "1234", "192.168.0.23:1521/orcl.localdomain");    
}
?>