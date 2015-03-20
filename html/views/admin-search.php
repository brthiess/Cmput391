<!DOCTYPE html>
<?php include 'header.php';?>
<body>
<?php include 'navbar.php';?>
<div class="col-md-4 col-md-offset-4">		
		<div class="general-form col-sm-10">
		<div class="text-center col-md-12"><h2>Search</h2></div>
			<form class="form-group" action="../php/Search.php" method="post">
				<div class="col-sm-12">
					<label class='control-label' for="diagnosis">Diagnosis</label>
					<input type="text" class="form-control" id="diagnosis" name="diagnosis">
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="start-date">Start Date</label>
					<input type="text" class="form-control" id="start-date" name="start-date">
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="end-date">End Date</label>
					<input type="text" class="form-control" id="end-date" name="end-date">
				</div>
				<div class="col-sm-12 top-buffer">
					<button type="submit" class="btn btn-info center-block"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search Records</strong></button>
				</div>
			</form>
		</div>
	</div>
</body>