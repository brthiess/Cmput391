<?php 
		include_once 'connect.php';
		include_once 'Person.php';
/**
* @param This is called when there is a http post
*/
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		edit_user();
	}
	
function edit_user() {
	//Edits the specified user 
}