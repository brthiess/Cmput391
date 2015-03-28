var spinner;


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
								console.log(userInfo);
						}		
			})		
		}
    });
	
	$(".form-control").each(function() {
		$(this).attr("class", "form-control input-wrong");
	});
	
	

	//Saves a record when admin clicks on save record
	$('body').on('click','.save-record-btn',function(){	
		event.preventDefault();
		saveRecord();
		var target = document.getElementById('form');
		spinner = new Spinner(opts).spin(target);
	});	
	
	var passwordArray = ["password", "password-again"];
	
	//Watches to make sure all fields in the form are filled out before allowing admin to add/edit a user
	$(".form-control").change(function() {
		//Check to see if every input has some input
		//If so, then put check mark beside it (for now)
		inputHasValue();
		
		//Give the input a checkmark if input is email
		if(inputIsEmail('email')){
			setInputsCorrect(['email'], true, false);
		}
		else {
			setInputsCorrect(['email'], false, false);
		}
		
		//Give the input a checkmark if the input is a phone number
		if(inputIsPhone('phone')){
			setInputsCorrect(['phone'], true, false);
		}
		else {
			setInputsCorrect(['phone'], false, false);
		}
		
				//Give the input a checkmark if the input is a phone number
		if(inputIsType('type')){
			setInputsCorrect(['type'], true, true);
		}
		else {
			setInputsCorrect(['type'], false, true);
		}
		
		if (inputsMatch(passwordArray)){
			setInputsCorrect(passwordArray, true, false);
		}
		else {
			setInputsCorrect(passwordArray, false, false);
		}
		if (allFieldsCorrect()) {
			$(".save-record-container").html('<button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".save-record-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
		}
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
	if (correct == true){
		input = 'input-correct';
	}
	else {
		input = 'input-wrong';
	}
	if (list) {  //If inputs are in list form (as opposed to normal text input)
		for (var i = 0; i < inputArray.length; i++){
			$("#" + inputArray[i]).parent().attr("class", "form-control " + input);
		}
	}
	else {
		for (var i = 0; i < inputArray.length; i++){
			$("#" + inputArray[i]).attr("class", "form-control " +  input);
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
	var doctor = $("#family-doctor").val();	
	

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
					"doctor": doctor},
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
			console.log("FALSE");
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
				console.log(allUsersJSON);
				for (var i = 0; i < allUsersJSON.length; i++){
					allUsers[i] = allUsersJSON[i].USER_NAME;
				}
			}
	});
	
	return allUsers;
	
}


//Returns true if the string is a number
function isNumeric(num){
    return !isNaN(num);
}



