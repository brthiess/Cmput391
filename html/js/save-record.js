var spinner;
var allDoctors;

var id = 0;

$(document).ready( function() {
	
	//Get all users for autocomplete
	allUsers = getAllUsers();
	$( ".username-edit" ).autocomplete({
		source: allUsers,
		change: function(event, ui) { //Get users information and fill in all inputs ajax style
			var username = $(".username-edit").val();
			$.ajax ({
				url: '../php/get-all-user-information.php?username='+username,
				success:function(data){
								$(".error-log").html(data);
								var userInfo = JSON.parse(data);
								outputPersonInfo(userInfo[1]);
								outputUserInfo(userInfo[0]);
								outputDoctorInfo(userInfo[2]);
								check_form();
						}	
			})		
		}
    });
	
	allDoctors = getAllDoctors();
	$(".family-doctor").autocomplete({
		source: allDoctors,
		change: function(event, ui){
			check_form();
		}
	});
	
	$(".form-control").each(function() {
		$(this).addClass("input-wrong");
	});
	
	

	//Saves a record when admin clicks on save record
	$('body').on('click','.save-record-btn',function(){	
		event.preventDefault();
		saveRecord();
		var target = document.getElementById('form');
		spinner = new Spinner(opts).spin(target);
	});	
	
	//Saves a record when admin clicks on save record
	$('body').on('click','.add-doctor-btn',function(){	
		event.preventDefault();
		addDoctorInput();
		check_form()
	});	
	
	//Saves a record when admin clicks on save record
	$('body').on('click','.delete-image-btn',function(){	
		event.preventDefault();
		//Get div id
		var id = $(this).parent().parent().attr("id").split('-')[1];

		removeDoctorInput(id);
	});	
	

	
	//Watches to make sure all fields in the form are filled out before allowing admin to add/edit a user
	$('body').on('change', ".form-control", function() {
		check_form();	
	});

	//spinner stuff
		var opts = {
		  lines: 13, // The number of lines to draw
		  length: 20, // The length of each line
		  width: 9, // The line thickness
		  radius: 30, // The radius of the inner circle
		  corners: 1, // Corner roundness (0..1)
		  rotate: 0, // The rotation offset
		  direction: 1, // 1: clockwise, -1: counterclockwise
		  color: '#000', // #rgb or #rrggbb or array of colors
		  speed: 1, // Rounds per second
		  trail: 60, // Afterglow percentage
		  shadow: false, // Whether to render a shadow
		  hwaccel: false, // Whether to use hardware acceleration
		  className: 'spinner', // The CSS class to assign to the spinner
		  zIndex: 2e9, // The z-index (defaults to 2000000000)
		  top: '50%', // Top position relative to parent
		  left: '50%' // Left position relative to parent
		};
});

//Returns true if all given inputs have the same value (Useful for password matching)
//Takes array of IDs
function inputsMatch(inputArray){
	for(var i = 0; i < inputArray.length; i++){
		for(var j = i + 1; j < inputArray.length; j++){
			if ($("#" + inputArray[i]).val() != $("#" + inputArray[j]).val() || $("#" + inputArray[i]).val().length == 0){
				return false;
			}
		}
	}
	return true;
}

//Sets the input to either correct or wrong
//Takes the div ID as input
function setInputsCorrect(inputArray, correct, list){
	var input = '';
	var opposite = '';
	if (correct == true){
		input = 'input-correct';
		opposite = 'input-wrong';
	}
	else {
		input = 'input-wrong';
		opposite = 'input-correct';
	}
	if (list) {  //If inputs are in list form (as opposed to normal text input)
		for (var i = 0; i < inputArray.length; i++){
			$("#" + inputArray[i]).parent().addClass(input);
			$("#" + inputArray[i]).parent().removeClass(opposite);
		}
	}
	else {
		for (var i = 0; i < inputArray.length; i++){
			$("#" + inputArray[i]).addClass(input);
			$("#" + inputArray[i]).removeClass(opposite);
		}
	}
}


//If the input has a length > 0 then set the input to a checkmark
function inputHasValue() {
	$('.form-control').each(function() {
		if ($(this).val().length > 0){
			setInputsCorrect([$(this).attr("id")], true, false);
		}
		else {
			setInputsCorrect([$(this).attr("id")], false, false);
		}
		
	});
}

//Checks if the given input is an email
function inputIsEmail(input){
	var email = $("#" + input).val();
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function inputIsPhone(input){
	var phone = $("#" + input).val();
	if (isNumeric(phone) && phone.length == 10){
		return true;
	}
	else {
		return false;
	}
}

function inputIsType(input){
	var type = $("#" + input).html();
	if (type == 'Patient' || type == 'Radiologist' || type == 'Doctor' || type == 'Administrator'){
		return true;
	}
	else {
		return false;
	}
}

function inputIsDoctor(input){
	var value = $("#" + input).val();
	
	if (allDoctors.indexOf(value) > -1){

		return true;
	}
	else {

		return false;
	}
}


function saveRecord() {
	var username = $("#username").val();	
	var password = $("#password").val();	
	var type = $("#type").html();		
	var startDate= $("#start-date").val();		
	var firstName = $("#first-name").val();		
	var lastName = $("#last-name").val();		
	var address = $("#address").val();		
	var email = $("#email").val();		
	var phone = $("#phone").val();	
	
	var doctorArray = [];
	
	$(".family-doctor").each(function() {
		doctorArray.push($(this).val());
	});
	
	var jsonDoctorArray = JSON.stringify(doctorArray);

	if (type == 'Patient') {
		type = 'p';
	}
	else if (type == 'Doctor'){
		type = 'd';
	}
	else if (type == 'Administrator') {
		type = 'a';
	}
	else if (type == 'Radiologist') {
		type = 'r';
	}
	

	$.ajax ({
			type:"post",
			url: "../php/create-user.php",
			data:{"username": username, "password": password, "clss": type,
					"start_date": startDate, "first_name": firstName, "last_name": lastName,
					"address": address, "email": email, "phone": phone,
					"doctor": jsonDoctorArray},
			success:function(data){
				spinner.stop();
				//Show confirmation 
				$(".confirmation-container").css("opacity", "1");
				$(".confirmation-container").css("z-index", "999");
				setTimeout(function() {
					$(".confirmation-container").css("opacity","0");
					$(".confirmation-container").css("z-index","-1");
				}, 1000);
				
				$(".error-log").html(data);
			}
	})
}

function allFieldsCorrect() {
	var correct = true;
	$('.form-control').each(function() {
		if ($(this).attr("class").indexOf("input-correct") == -1){
			correct = false;
		}
	});
	
	return correct;
}

//Ajax call to DB to get all users
function getAllUsers() {
	var allUsers = [];
	$.ajax ({
			url: '../php/get-all-users.php',
			success:function(data){
				allUsersJSON = JSON.parse(data);
				for (var i = 0; i < allUsersJSON.length; i++){
					allUsers[i] = allUsersJSON[i].USER_NAME;
				}				
			}
	});
	
	return allUsers;
	
}
function getAllDoctors(){
	allDoctors = [];
	$.ajax({
		url: '../php/get-all-doctor-ids.php',
		success:function(data){
			allDoctorsJSON = JSON.parse(data);
			for (var i = 0; i < allDoctorsJSON.length; i++){
					allDoctors[i] = allDoctorsJSON[i].PERSON_ID;
			}
		}
	});
	return allDoctors;
}

function outputUserInfo(userInfo){
	$("#start-date").val(userInfo.dateRegistered);
	$("#password").val(userInfo.password);
	$("#password-again").val(userInfo.password);
	
	var type = $("#type");
	if (userInfo.clss == 'p'){
		type.html("Patient");
	}
	else if (userInfo.clss == 'd'){
		type.html("Doctor");
	}
	else if (userInfo.clss == 'r'){
		type.html("Radiologist");
	}
	else if (userInfo.clss == 'a'){
		type.html("Administrator");
	}
}

function outputPersonInfo(personInfo){
	$("#first-name").val(personInfo.firstName);
	$("#last-name").val(personInfo.lastName);
	$("#email").val(personInfo.email);
	$("#address").val(personInfo.address);
	$("#phone").val(personInfo.phone);
}


function outputDoctorInfo(doctorInfo){	
	removeAllDoctorInfo();
	for(var i = 0; i < doctorInfo.length; i++){
		addDoctorInputWithValue(doctorInfo[i].DOCTOR_ID);
	}
}

function removeAllDoctorInfo(){
	for(var i = 0; i <= id; i++){
		removeDoctorInput(i);
	}
}

//Returns true if the string is a number
function isNumeric(num){
    return !isNaN(num);
}

function check_form() {
	var passwordArray = ["password", "password-again"];
		//Check to see if every input has some input
		//If so, then put check mark beside it (for now)
		inputHasValue();
		
		//Give the input a checkmark if input is email
		if(inputIsEmail('email')){
			setInputsCorrect(['email'], true, false);
			removeWarning('email');
		}
		else {
			addWarning('email', 'Must be a valid email address');
			setInputsCorrect(['email'], false, false);
		}
		
		//Give the input a checkmark if the input is a phone number
		if(inputIsPhone('phone')){
			setInputsCorrect(['phone'], true, false);
			removeWarning('phone');
		}
		else {
			addWarning('phone', 'Number must be 10 digits long');
			setInputsCorrect(['phone'], false, false);
		}
		
				//Give the input a checkmark if the input is a phone number
		if(inputIsType('type')){
			setInputsCorrect(['type'], true, true);
			removeWarning('type');
		}
		else {
			addWarning('type', 'Type must be one of 4 types');
			setInputsCorrect(['type'], false, true);
		}
		
		for(var i = 0; i <= id; i++){
			if (inputIsDoctor('family-doctor-' + i)){
				setInputsCorrect(['family-doctor-' + i], true, false);
			}
			else {
				setInputsCorrect(['family-doctor-' + i], false, false);
			}
		}
		
		if (inputsMatch(passwordArray)){
			setInputsCorrect(passwordArray, true, false);
			removeWarning('password');
		}
		else {
			addWarning('password', 'Passwords must match');
			setInputsCorrect(passwordArray, false, false);
		}
		if (allFieldsCorrect()) {
			$(".save-record-container").html('<button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".save-record-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
		}
}

function addDoctorInput(){
	id += 1;
	$(".form-group").append('<div class="col-sm-12 doctor-input top-buffer" id="id-' + id + '"><div class="col-sm-3"><label class="control-label" for="family-doctor">Doctor ID</label></div><div class="col-sm-4 delete-image"><img class="delete-image-btn" src="../images/delete-icon.png"></div><input type="text" class="form-control family-doctor" id="family-doctor-' + id + '" name="doctor"></div>');
}

function addDoctorInputWithValue(value){
	id += 1;
	$(".form-group").append('<div class="col-sm-12 doctor-input top-buffer" id="id-' + id + '"><div class="col-sm-3"><label class="control-label" for="family-doctor">Doctor ID</label></div><div class="col-sm-4 delete-image"><img class="delete-image-btn" src="../images/delete-icon.png"></div><input type="text" class="form-control family-doctor" id="family-doctor-' + id + '" value="' + value + '" name="doctor"></div>');
}



function removeDoctorInput(id){
	$("#id-" + id).remove();
}

function addWarning(id, warningString){
	$("." + id + " p").html(" " + warningString);
}

function removeWarning(id){
	$("." + id + " p").html("");
}



