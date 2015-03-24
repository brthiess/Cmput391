<?php include_once '../php/login.php';
	include_once '../php/connect.php';
	start_session();
?>

<div class="col-lg-12 navigational-bar">
	<div class="col-sm-4">
		<div class="title">
			<a href="index.php">The Radiology Clinic</a>
		</div>
	</div>
	<?php if (check_login($db, 'all')) :?>
	<div class="col-sm-8">
		<div class="user-image">
			<a href="../php/logout.php"><img src="../images/logout.png"></a>
		</div>
		<div class="user-name">

			
			<a href="user-settings.php"><?php echo $_SESSION["username"];?> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
			
		</div>
	</div>
	<?php endif;?>

</div>
