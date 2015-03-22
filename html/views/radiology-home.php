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
	
	
	<?php if (check_login($db, 'r')) : ?>
	<?php 
	$search_type = "Search Radiology Records";
	$search_type_short = "r";
	include 'search-box.php';?>
	<div class="col-md-4 col-md-offset-4 top-buffer">
		<div class="col-sm-10 center-block general-form">
		<div class="text-center col-md-12"><h2>Upload Record</h2></div>
		
			<form class="form-group" action="upload-record.php" method="post">
				<div class="col-sm-12">
					<label class="control-label" for="patient-id">Patient ID</label>
					<input type="text" class="form-control" id="patient-id" name="patient-id">
				</div>
				<div class="col-sm-12 top-buffer">
					<button class="btn btn-info center-block green-btn"><strong>+ </strong>Enter Radiology Record</strong></button>
				</div>
			</form>
		</div>
	</div>
		<?php else : ?>
            <div class="col-sm-12 text-center">
                <h2>You are not authorized to access this page. Please <a href="index.php">login</a>.</h2>
            </div>
        <?php endif; ?>
</body>
</html>