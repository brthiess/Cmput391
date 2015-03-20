<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<script src="../js/upload-image.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<body>
<?php include 'navbar.php';?>

<div class="col-sm-4 radiology-form">
	<div class="text-center col-md-12"><h2>Input Record</h2></div>
	<div class="form-group">
		<div class="col-sm-12">
			<fieldset disabled>
				<label class="control-label" for="patient-id">Patient ID</label>
				<?php 
					$patient_id = $_POST["patient-id"];
					echo '<input type="text" class="form-control" id="patient-id" placeholder="' . $patient_id . '">';
				?>
				<label class="control-label" for="record-id">Record ID</label>
				<?php 
					$record_id = 5;
					echo '<input type="text" class="form-control" id="record-id" placeholder="' . $record_id . '">';
				?>
			</fieldset>
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
			<textarea type="text" class="form-control input-description" id="description" rows="3"></textarea>
		</div>		
	</div>
	<br>
</div>
<div class="col-sm-8 medical-image">
	<div class="col-sm-12 text-center">
	<h2>Upload Medical Images</h2>
	</div>
	<div class="col-sm-12" id="upload-images-div">
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-sm-12">
					<button class="btn btn-info upload-images-btn" onclick="document.getElementById('image-upload').click(); return false;" ><strong>+</strong> Add Image</button>
					<input class="btn btn-info" type="file" name="image-upload" id="image-upload" onchange="addImage()" ></input>
				</div>
			</div>
		</form>			

	</div>
</div>
	<div class="row top-buffer"></div>
	<div class="col-sm-2 col-sm-offset-5 top-buffer">
		<button class="btn btn-info"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>
	</div>
</body>
</html>