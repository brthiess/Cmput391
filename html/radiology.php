<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<script src="upload-image.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
	<div class="col-sm-12 text-center">
	<h2>Upload Medical Images</h2>
	</div>
	<div class="col-sm-12">
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<button class="btn btn-info upload-images-btn" onclick="document.getElementById('image-upload').click(); return false;" ><strong>+</strong> Add Image</button>
			<input class="btn btn-info" type="file" name="image-upload" id="image-upload" onchange="addImage()" ></input>
			<img id="img-1" src="" alt="">
		</form>
	</div>
</div>
</body>
</html>