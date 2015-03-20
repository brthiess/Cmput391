<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<body>
	<?php include 'navbar.php';?>
	<?php include 'search-box.php';?>
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
</body>
</html>