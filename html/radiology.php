<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>

<body>
<?php include 'navbar.php';?>

<div class="col-md-4 radiology-form">
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label" for="patient-id">Patient ID</label>
			<input type="text" class="form-control" id="patient-id">
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group ">
			<label class="control-label" for="doctor-id">Doctor ID</label>
			<input type="text" class="form-control" id="doctor-id">
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label" for="radiologist-id">Radiologist ID</label>
			<input type="text" class="form-control" id="radiologist-id">
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label" for="test-type">Test Type</label>
			<input type="text" class="form-control" id="test-type">
		</div>
	</div>
</div>
	<form action="upload.php" method="post" enctype="multipart/form-data">
	Upload Medical Images
	<input type="file" name="fileToUpload" id="fileToUpload">
	<button type="submit" class="btn btn-block btn-info" name="submit"><h4>Upload Image</h4></button> 
			
</form>
</body>
</html>