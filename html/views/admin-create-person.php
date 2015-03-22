<!DOCTYPE html>
<html lang="en">
<?php 	include_once 'header.php';
		include_once '../php/login.php';
		include_once '../php/connect.php';
		

		start_session();
		
?>
<body>
<?php include_once 'navbar.php';?>
	<?php if (check_login($db, 'a')) : ?>

	<div class="col-sm-6 col-sm-offset-3 create-person-form" id="form">
		<div class="text-center col-md-12"><h2>Create Person</h2></div>
		<div class="form-group">
			<div class="col-sm-12">
					<label class="control-label" for="username">User Name</label>
					<input type="text" class="form-control" id="username" name="username">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="password">Password</label>
					<input type="text" class="form-control" id="password" name="password">
			</div>
			<div class="col-sm-12">
					<label class="control-label" for="type">Type</label>
					<div class="dropdown">
						<button class="btn btn-default dropdown-btn dropdown-toggle form-control" type="button" name="clss" data-toggle="dropdown" aria-expanded="false">
							<span id="type" data-bind="label">Type</span>
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
				<input type="text" class="form-control" id="first-name" name="first-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="last-name">Last Name</label>
				<input type="text" class="form-control" id="last-name" name="last-name">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="address">Address</label>
				<input type="text" class="form-control" id="address" name="address">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="email">Email</label>
				<input type="text" class="form-control" id="email" name="email">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="phone">Phone</label>
				<input type="text" class="form-control" id="phone" name="phone">
			</div>
			<div class="col-sm-12">
				<label class="control-label" for="family-doctor">Family Doctor</label>
				<input type="text" class="form-control" id="family-doctor" name="doctor">
			</div>
			
		</div>
		<br>
	</div>
	<div class="col-sm-6 col-sm-offset-3 bottom-buffer top-buffer save-record-container"><fieldset disabled><button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset></div>
	<div class="col-sm-12 error-log"></div>
		<?php else : include_once 'authorization-error.php';?>

        <?php endif; ?>
</body>
</html>