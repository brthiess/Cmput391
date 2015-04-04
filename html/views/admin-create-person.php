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

	<?php 
		$title = "Create Person";
		$class = "username-create";
		include_once 'create-person-form.php';?>
		<?php else : include_once 'authorization-error.php';?>

        <?php endif; ?>
</body>
</html>