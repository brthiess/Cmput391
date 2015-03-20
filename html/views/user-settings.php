<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<body>
<?php include 'navbar.php';?>

	<div class="row">
		<h1 class="text-center">Account Settings</h1>
	</div>

	<div class="col-sm-3 col-sm-offset-1 general-form">
		<div class="text-center col-md-12"><h2>Change Password</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
					<label class="control-label" for="current-password">Current Password</label>
					<input type="password" class="form-control" id="current-password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="new-password">New Password</label>
					<input type="password" class="form-control" id="new-password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="new-password-again">New Password Again</label>
					<input type="password" class="form-control" id="new-password-again">
			</div>
				<div class="col-sm-10 col-sm-offset-1 bottom-buffer top-buffer"><button class="btn btn-info full-width-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save New Password</strong></button></div>
		</div>
	</div>
	<div class="col-sm-3 col-sm-offset-3 general-form">
		<div class="text-center col-md-12"><h2>Change Information</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label" for="first-name">First Name</label>
				<input type="text" class="form-control" id="first-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="last-name">Last Name</label>
				<input type="text" class="form-control" id="last-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="address">Address</label>
				<input type="text" class="form-control" id="address">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="email">Email</label>
				<input type="text" class="form-control" id="email">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="phone">Phone</label>
				<input type="text" class="form-control" id="phone">
			</div>
			<div class="col-sm-10 col-sm-offset-1 bottom-buffer top-buffer"><button class="btn btn-info full-width-btn green-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Information</strong></button></div>

		</div>
		<br>
	</div>

</body>
</html>	