<?php include 'search-tile.php';
		include '../php/Search.php';

	// Throws an exception if not a valid "userName" is not valid.
	$search = new Search("userName");
	
	
	print_tile(1);		
	print_tile(2);		
	print_tile(3);		
	print_tile(4);		
?>