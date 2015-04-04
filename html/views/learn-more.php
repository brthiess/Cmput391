<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';?>
<script src="../js/learn-more.js"></script>
<body>
<?php include 'navbar.php';?>

	
	<div class="col-sm-12 learn-more-element learn-more text-center">
		<img class="center-block" src="../images/radiology-logo.png">
		<h1>We are the <div class="learn-more-title">Radiology Information System</div>, an award winning medical system based in Edmonton, Alberta</h1>
		
		<div class="sidebar">
			<div class="sidebar-element sidebar-install">
				<p>Installation</p>
			</div>
			<div class="sidebar-element sidebar-login">
				<p>Login</p>
			</div>
			<div class="sidebar-element sidebar-user-management">
				<p>User-Management</p>
			</div>
			<div class="sidebar-element sidebar-report-generating">
				<p>Generating Reports</p>
			</div>
			<div class="sidebar-element sidebar-uploading">
				<p>Uploading Reports</p>
			</div>
			<div class="sidebar-search sidebar-element">
				<p>Searching</p>
			</div>
			<div class="sidebar-data-analysis sidebar-element">
				<p>Data Analysis</p>
			</div>
		</div>
	</div>
	<div class="col-sm-12 learn-more-element how-to text-center">
		<h1>How To Install</h1>
		<h2>Our system is incredibly intuitive and easy to use.  All that is required to install our system is a computer!</h2>
	</div>
	<div class="col-sm-12 learn-more-element login-how-to text-center">
		<h1>How To Login</h1>
		<h2>In order to log in, an administrator account must exist.  The initial script that is run, installs an administrator account with: <br>
		 <div class="code-text"><br>username: admin <br>
								password: password </div>  <br>
		From there accounts can be created by the administrator by clicking on the <i>Create Person</i> button.  From there, simply fill out the form and click <i>Save Record</i> and the new user will be created. <br><br>
		If a user would like to modify their information, they can click the <span class="glyphicon glyphicon-cog"></span> icon in the top right hand corner of their screen.  This will take the user to their profile settings page where they may modify whatever personal information they desire such as their password or home address.</h2>
	</div>
	<div class="col-sm-12 learn-more-element user-management-how-to text-center">
		<h1>User Management</h1>
		<h2>Our system allows for the system administrator to create and manage users.  If the administrator would like to create a user, they can click on the <i>Create Person</i> button on the admin home page.  From there they fill out the necessary details and click <i>Save Record.</i>  It is important to make sure that all inputs in the form are valid.  For your convenience we have provided an automatic algorithm to check all form inputs to make sure they are correct. <br><br>
		If an administrator has decided that he or she wishes to modify a user's information they can click on the <i>Edit Person</i> button on the admin home page.  From there they enter the first few characters of the the user they would like to edit and autocomplete should do the rest for them.  Then the admin is free to edit whatever details they feel are necessary.</h2>
	</div>
	<div class="col-sm-12 learn-more-element report-generating-how-to text-center">
		<h1>How To Generate Reports</h1>
		<h2>If a user would like to generate reports they must first log in as an administrator.  From there they can click on <i>Search Diagnosis.</i>  The administrator can then enter in the search criteria and the dates they would like to limit the search results between.  In addition they may also sort the results by the report date. <br><br>
		Once they click search, results will appear as <i>tiles.</i>  The administrator can then click on a specific tile to bring up the report which will contain information such as: name, address and testing date.</h2>
	</div>
	<div class="col-sm-12 learn-more-element uploading-how-to text-center">
		<h1>How To Upload Reports</h1>
		

		<h2>In order to upload medical reports to the database, the user must be a radiologist.  Once the user has logged in and are at the home screen for the radiology section, the user can enter a valid patient id and click on <i>Upload Record</i>.  It is important to note, that if the patient id is not valid, the form will not be able to be submitted.  From there, the radiologist enters in the required information such as the Radiologist ID, Doctor ID and Diagnosis.  If they choose, they may also upload some medical images as well.  Once they hit the <i>Save Record</i> button, the record is uploaded to the database and a confirmation message should appear.</h2>
	</div>
	<div class="col-sm-12 learn-more-element search-how-to text-center">
		<h1>Searching</h1>
		<h2>Our system has an incredibly powerful search tool!  Any user in the system may use it.  For any user, they can simply log in and go to their home screen.  From there, just enter the keywords that you wish to search such as "MRI" or "Doctor Smith".  The results will be displayed as <i>tiles</i>.  The user can click on a tile to expand the radiology record and view things such as the doctor's phone number or the date of the test.  The user can also view their medical images in higher resolution by clicking on the thumbnail provided.  <br><br>
			One thing that is important to note is that due to privacy concerns a radiologist may only view records that they entered personally, a doctor may only view records of their patients, and a patient may only view his or her own records.  An administrator may view any records however. <br><br>
			If the user wishes to sort their results, they may do so by selecting one of the provided radio buttons.  They can either sort the records by the date of the test (ascending or descending) or view it with our own special ranking formula.</h2>
	</div>
	<div class="col-sm-12 learn-more-element data-analysis-how-to text-center">
		<h1>Analyzing Data</h1>
		<h2>Thanks to new <em>Data Cube</em> technology, we are able to provide state of the art data analysis.  Our administrators can view complex SQL queries with the touch of a button.  <br><br>
			First you have to log in as an administrator, and then click on the <i>Data Analysis</i> button.  From there the user is given three variables (in the form of an HTML Select) to manipulate.  They can choose the variables from the drop down select and the statistics will be displayed automatically.  <br><br>
				The user may also choose to view the statistics on a daily, weekly or yearly basis by clicking the appropriate buttons.</h2>
	</div>
</body>
</html>