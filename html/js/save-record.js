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
		if (allFieldsFilled()) {
			$(".save-record-container").html('<button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".save-record-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
		}
		
		if (inputsMatch(passwordArray)){
			setInputsCorrectOrWrong(passwordArray, true);
		}
		else {
			setInputsCorrectOrWrong(passwordArray, false);
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
			if ($("#" + inputArray[i]).val() != $("#" + inputArray[j]).val()){
				console.log($(inputArray[i]).val());
				console.log($(inputArray[j]).val());
				return false;
			}
		}
	}
	return true;
}

//Sets the input to either correct or wrong
//Takes the div ID as input
function setInputsCorrectOrWrong(inputArray, correct){
	var input = '';
	if (correct == true){
		input = 'input-correct';
	}
	else {
		input = 'input-wrong';
	}
	for (var i = 0; i < inputArray.length; i++){
		$("#" + inputArray[i]).attr("class", "form-control " +  input);
	}
}


//If the input has a length > 0 then set the input to a checkmark
function inputHasValue() {
	$('.form-control').each(function() {
		if ($(this).val().length > 0){
			setInputsCorrectOrWrong([$(this).attr("id")], true);
		}
		else {
			setInputsCorrectOrWrong([$(this).attr("id")], false);
		}
		
	});
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
	
	console.log("TYPE: "  + type);

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

function allFieldsFilled() {
	if ($("#username").val().length > 0 &&
		$("#password").val().length > 0 &&
		$("#start-date").val().length > 0 &&
		$("#first-name").val().length > 0 &&
		$("#last-name").val().length > 0 &&
		$("#address").val().length > 0 &&
		$("#phone").val().length > 0	 &&	
		$("#email").val().length > 0	&&	
		$("#family-doctor").val().length > 0		
		) {
			return true;
		}
	else {
		false;
	}
	
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
				console.log(allUsers);
			}
	});
	
	return allUsers;
	
}



