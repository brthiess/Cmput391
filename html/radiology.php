<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>

<body>
<?php include 'navbar.php';?>

<div class="col-sm-4 radiology-form">
	<div class="text-center col-md-12"><h2>Input Record</h2></div>
	<div class="form-group">
		<div class="col-sm-12">
				<label class="control-label" for="patient-id">Patient ID</label>
				<input type="text" class="form-control" id="patient-id">
		</div>
		<div class="col-sm-12">
				<label class="control-label" for="doctor-id">Doctor ID</label>
				<input type="text" class="form-control" id="doctor-id">
		</div>
		<div class="col-sm-12">
				<label class="control-label" for="radiologist-id">Radiologist ID</label>
				<input type="text" class="form-control" id="radiologist-id">
		</div>
		<div class="col-sm-12">
				<label class="control-label" for="test-type">Test Type</label>
				<input type="text" class="form-control" id="test-type">
		</div>
		<div class="col-sm-12">
			<label class="control-label" for="diagnosis">Diagnosis</label>
			<textarea type="text" class="form-control" id="diagnosis" rows="2"></textarea>
		</div>
		<div class="col-sm-12">
			<label class="control-label" for="description">Description</label>
			<textarea type="text" class="form-control" id="description" rows="2"></textarea>
		</div>		
	</div>
	<br>
</div>
<div class="col-sm-8 medical-image">
	<form action="upload.php" method="post" enctype="multipart/form-data">
		Upload Medical Images
		<input class="btn btn-info" type="file" name="fileToUpload" id="fileToUpload">
		<button type="submit" class="btn btn-block btn-info" name="submit"><h4>Upload Image</h4></button> 
	</form>
</div>
</body>
</html>