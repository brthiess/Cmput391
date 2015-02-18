<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<body>
<?php include 'navbar.php';?>

	<div class="container-fluid">
		<div class="row">
			<h1 class="text-center">Welcome to the Radiology Clinic</h1>
		</div>
		<div class="row">
			<div class="col-sm-4" id="patient-login-form"> 
				<div class="col-sm-8 login-form col-sm-offset-2">
					<div class="form-group">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="patient-user-name" placeholder="Patient Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="patient-password" placeholder="Password">
						</div>
						<div class="row top-buffer">
							<button type="button" class="btn btn-block btn-info"><h4>Patient Login</h4></button> 
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4" id="radiologist-login-form"> 
				<div class="col-sm-8 login-form col-sm-offset-2">
					<div class="form-group">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="radiologist-user-name" placeholder="Radiologist Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="radiologist-password" placeholder="Password">
						</div>
						<div class="row top-buffer">
							<button type="button" class="btn btn-block btn-info radiologist-login-btn"><h4>Radiologist Login</h4></button> 
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4" id="admin-login-form"> 
				<div class="col-sm-8 login-form col-sm-offset-2">			
					<div class="form-group">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="admin-user-name" placeholder="Admin Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="admin-password" placeholder="Password">
						</div>
						<div class="row top-buffer">
							<button type="button" class="btn btn-block btn-info admin-login-btn"><h4>Admin Login</h4></button> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>