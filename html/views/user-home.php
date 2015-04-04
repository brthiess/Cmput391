<!DOCTYPE html>
<html lang="en">
<?php 	include_once 'header.php';
		include_once '../php/login.php';
		include_once '../php/connect.php';
		

		start_session();
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			login($db, $_POST["username"], $_POST["password"], $_POST["clss"]);
			header('Location: user-home.php');	
			exit;
		}
?>
<body>
<?php include_once 'navbar.php';?>
	
	
	<?php if (check_login($db, 'p')) : ?>


		<div class="col-sm-12 behind">
		 &nbsp </div>
	<div class="col-md-4"></div>
	<?php 	
	$search_type = "Search Radiology Records";
	$search_type_short = "r";
	include 'search-box.php';?>
	<div class="col-md-4"></div>
	
	<div class="col-sm-12">
	</div>
	
	<div id="search-results">
	</div>
	

</body>
		<?php else : include_once 'authorization-error.php';?>

        <?php endif; ?>
</html>