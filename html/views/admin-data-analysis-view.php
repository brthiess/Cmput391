<!DOCTYPE html>
<html lang="en">
<?php 	include_once 'header.php';
		echo '<script src="../js/data-analysis.js"></script>';
		include_once '../php/login.php';
		include_once '../php/connect.php';		

		start_session();		
?>
<body>
<?php include_once 'navbar.php';?>
	<?php if (check_login($db, 'a')) : ?>
	
	<div class="col-sm-12 text-center">
		<h2>Data Analysis</h2>
	</div>
	
	<div class="col-sm-4">
		<select id="patient-list" class="center-block" >
			<option value="-1" selected>Choose a Patient</option>
		</select>
	</div>
	<div class="col-sm-4">
		<select id="test-type-list" class="center-block" >
			<option value="-1" selected>Choose a Test Type</option>
		</select>
	</div>
	<div class="col-sm-4">
		<select class="center-block" id="date-list">
			<option value="-1" selected>Choose a Date</option>
		</select>
		<div class="col-sm-3 col-sm-offset-1 interval-btn">
			<button id="yearly-btn" class="center-block circle-btn btn btn-info green-btn selected"><p>Yearly</p></button>
		</div>
		<div class="interval-btn col-sm-3">
			<button id="weekly-btn" class="center-block circle-btn btn btn-info purple-btn"><p>Monthly</p></button>
		</div>
		<div class="col-sm-3 interval-btn">
			<button id="daily-btn" class="center-block circle-btn btn btn-info orange-btn"><p>Weekly</p></button>
		</div>
	</div>
	
	<div class="col-sm-12 text-center data-sum">
		<p>0</p> Images
	</div>
		<div class="error-log"></div>
		<?php else : include_once 'authorization-error.php';?>

        <?php endif; ?>
</body>
</html>