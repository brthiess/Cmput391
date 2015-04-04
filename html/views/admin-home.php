<!DOCTYPE html>
<html lang="en">
<?php 	include_once 'header.php';
		include_once '../php/login.php';
		include_once '../php/connect.php';
		

		start_session();
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			login($db, $_POST["username"], $_POST["password"], $_POST["clss"]);
			header('Location: admin-home.php');	
			exit;
		}
?>
<body>
<?php include_once 'navbar.php';?>
	
	
	<?php if (check_login($db, 'a')) : ?>
	<div class="col-md-3 top-buffer">
		<a href="admin-create-person.php"><button class="btn btn-info center-block green-btn large-btn"><strong>+</strong> Create Person</button></a>
	</div>

	<div class="col-md-3 top-buffer">
		<a href="admin-edit-person.php"><button class="btn btn-info center-block orange-btn large-btn"><span class="glyphicon glyphicon-pencil"  aria-hidden="true"></span> Edit Person</button></a>
	</div>
	
	<div class="col-md-3 top-buffer">
		<a href="admin-search-diagnosis.php"><button class="btn btn-info center-block purple-btn large-btn"><span class="glyphicon glyphicon-search"  aria-hidden="true"></span> Search Diagnosis</button></a>
	</div>
	
	<div class="col-md-3 top-buffer">
		<a href="admin-search-radiology.php"><button class="btn btn-info center-block red-btn large-btn"><span class="glyphicon glyphicon-search"  aria-hidden="true"></span> Search Records</button></a>
	</div>
	
	<div class="col-md-12 top-buffer-large">
		<a href="admin-data-analysis-view.php"><button class="btn btn-info center-block gray-btn large-btn"><span class="glyphicon glyphicon-stats"  aria-hidden="true"></span> Data Analysis</button></a>
	</div>
	
		<?php else : include_once 'authorization-error.php';?>

        <?php endif; ?>
	
</body>
</html>