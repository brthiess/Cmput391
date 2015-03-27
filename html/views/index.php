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
					<form class="form-group" action="user-home.php" method="post">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="patient-user-name" name="username" placeholder="Patient Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="patient-password" name="password" placeholder="Password">
							<input type="hidden" class="form-control" name="clss" value="p">
						</div>
						<input type="hidden" name="type" value="patient"/>
						<div class="row top-buffer">
							<button type="submit" class="btn btn-block btn-info"><h4>Patient/Doctor Login</h4></button> 
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-4" id="radiologist-login-form"> 
				<div class="col-sm-8 login-form col-sm-offset-2">
					<form class="form-group" action="radiology-home.php" method="post">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="radiologist-user-name" name="username" placeholder="Radiologist Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="radiologist-password" name="password" placeholder="Password">
							<input type="hidden" class="form-control" name="clss" value="r">
						</div>
						<div class="row top-buffer">
							<button type="submit" class="btn btn-block btn-info green-btn"><h4>Radiologist Login</h4></button> 
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-4" id="admin-login-form"> 
				<div class="col-sm-8 login-form col-sm-offset-2">			
					<form class="form-group" action="admin-home.php" method="post">
						<div class="row top-buffer">
							<input type="text" class="form-control" id="admin-user-name" name="username" placeholder="Admin Username">
						</div>
						<div class="row top-buffer">
							<input type="password" class="form-control" id="admin-password" name="password" placeholder="Password">
							<input type="hidden" class="form-control" name="clss" value="a">
						</div>
						<div class="row top-buffer">
							<button type="submit" class="btn btn-block btn-info purple-btn"><h4>Admin Login</h4></button> 
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</body>
</html>