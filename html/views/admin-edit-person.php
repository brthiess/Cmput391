<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<body>
<?php include 'navbar.php';?>

	<div class="col-sm-6 col-sm-offset-3 create-person-form">
		<div class="text-center col-md-12"><h2>Edit Person</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
					<label class="control-label" for="username">User Name</label>
					<input type="text" class="form-control" id="username">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="password">Password</label>
					<input type="text" class="form-control" id="password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="type">Type</label>
					<div class="dropdown">
						<button class="btn btn-default dropdown-btn dropdown-toggle form-control" type="button" id="type" data-toggle="dropdown" aria-expanded="false">
							<span data-bind="label">Type</span>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="type">
							<li role="presentation"><a role="menuitem" tabindex="-1" >Administrator</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" >Doctor</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" >Patient</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" >Radiologist</a></li>
						</ul>
					</div>
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="diagnosis">Date Registered</label>
				<input type="text" class="form-control" id="start-date" name="start-date">
			</div>
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
			<div class="col-sm-12">
				<label class="control-label" for="family-doctor">Family Doctor</label>
				<input type="text" class="form-control" id="family-doctor">
			</div>
			
		</div>
		<br>
	</div>
	<div class="col-sm-6 col-sm-offset-3 bottom-buffer top-buffer"><button class="btn btn-info full-width-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></div>
</body>
</html>	