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
<script src="../js/user-settings.js"></script>
<body>
<?php include_once 'navbar.php';?>
	
	
<?php if (check_login($db, 'all')) : ?>

	<div class="row">
		<h1 class="text-center">Account Settings</h1>
	</div>

	<div class="col-sm-3 col-sm-offset-1 general-form">
		<div class="text-center col-md-12"><h2>Change Password</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
					<label class="control-label" for="current-password">Current Password</label>
					<input type="password" class="form-control password-form no-feedback" id="current-password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="new-password">New Password</label>
					<input type="password" class="form-control password-form" id="new-password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="new-password-again">New Password Again</label>
					<input type="password" class="form-control password-form" id="new-password-again">
			</div>
				<div class="col-sm-10 col-sm-offset-1 bottom-buffer top-buffer save-password-container"><fieldset disabled><button class="btn btn-info full-width-btn save-password-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save New Password</strong></button></fieldset></div>
		</div>
		<div class="confirmation-container"><p>New Password Saved</p></div>
	</div>
	<div class="col-sm-3 col-sm-offset-3 general-form">
		<div class="text-center col-md-12"><h2>Change Information</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label" for="first-name">First Name</label>
				<input type="text" class="form-control information-form" id="first-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="last-name">Last Name</label>
				<input type="text" class="form-control information-form" id="last-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="address">Address</label>
				<input type="text" class="form-control information-form" id="address">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="email">Email</label>
				<input type="text" class="form-control information-form" id="email">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="phone">Phone</label>
				<input type="text" class="form-control information-form" id="phone">
			</div>
			<div class="col-sm-10 col-sm-offset-1 bottom-buffer top-buffer change-information-container"><fieldset disabled><button class="btn btn-info full-width-btn green-btn save-information-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Information</strong></button></fieldset></div>
			<div class="confirmation-container confirmation-container-name"><p>New Password Saved</p></div>
		</div>
		<br>
	</div>
	<?php else : include_once 'authorization-error.php';?>
	<?php endif; ?>
</body>
</html>	