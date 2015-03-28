var spinner;


$(document).ready( function() {
	//Saves a record when admin clicks on save record
	$('body').on('click','.save-password-btn',function(){	
		event.preventDefault();
		savePassword();
		var target = document.getElementById('form');
		spinner = new Spinner(opts).spin(target);
	});	
	
		$('body').on('click','.save-information-btn',function(){	
		event.preventDefault();
		saveInformation();
		var target = document.getElementById('form');
		spinner = new Spinner(opts).spin(target);
	});	
	
	//Watches to make sure all fields in the form are filled out before allowing admin to add/edit a user
	$(".form-control").change(function() {
		if (allFieldsFilled()) {
			$(".save-password-container").html('<button class="btn btn-info full-width-btn save-password-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save New Password</strong></button>');
		}
		else {
			$(".save-password-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-password-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save New Password</strong></button></fieldset>');
		}
		if (allNameFieldsFilled()) {
			$(".change-information-container").html('<button class="btn btn-info full-width-btn save-information-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Information</strong></button>');
		}
		else {
			$(".change-information-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-information-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Information</strong></button></fieldset>');
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

function savePassword() {
	var new_password = $("#new-password").val();	
	var old_password = $("#current-password").val();	

	


	$.ajax ({
			type:"post",
			url: "../php/edit-user.php",
			data:{"new_password": new_password, "old_password": old_password},
			success:function(data){
				spinner.stop();
				//Show confirmation 
				$(".confirmation-container").css("opacity", "1");
				$(".confirmation-container").css("z-index", "999");
				setTimeout(function() {
					$(".confirmation-container").css("opacity","0");
					$(".confirmation-container").css("z-index","-1");
				}, 2000);
				$(".confirmation-container").html("<p>Password Saved</p>");
				
				$(".error-log").html(data);
				$("#new-password").val('');
				$("#old-password").val('');
				$("#new-password-again").val('');
			},
			error:function(data){
				//Show confirmation 
				$(".confirmation-container").css("opacity", "1");
				$(".confirmation-container").css("z-index", "999");
				setTimeout(function() {
					$(".confirmation-container").css("opacity","0");
					$(".confirmation-container").css("z-index","-1");
				}, 2000);
				$(".confirmation-container").html("<p>Wrong Old Password</p>");
			}
	})
}

function saveInformation() {
	var email = $("#email").val();	
	var phone = $("#phone").val();	
	var first_name = $("#first-name").val();	
	var last_name = $("#last-name").val();	
	var address = $("#address").val();	

	


	$.ajax ({
			type:"post",
			url: "../php/edit-user-information.php",
			data:{"email": email, "phone": phone, "first_name": first_name, "last_name": last_name, "address": address},
			success:function(data){
				spinner.stop();
				//Show confirmation 
				$(".confirmation-container-name").css("opacity", "1");
				$(".confirmation-container-name").css("z-index", "999");
				setTimeout(function() {
					$(".confirmation-container-name").css("opacity","0");
					$(".confirmation-container-name").css("z-index","-1");
				}, 2000);
				$(".confirmation-container-name").html("<p>Information Saved</p>");
				
				$(".error-log").html(data);
				$("#email").val('');	
				$("#phone").val('');	
				$("#first-name").val('');	
				$("#last-name").val('');
				$("#address").val('');	
			}
	})
}

function allFieldsFilled() {
	if ($("#current-password").val().length > 0 &&
		$("#new-password").val().length > 0 &&
		$("#new-password-again").val().length > 0 &&
		($("#new-password").val() == $("#new-password-again").val())
		) {
			console.log("true");
			return true;
		}
	else {
		console.log("False");
		false;
	}
	
}

function allNameFieldsFilled() {
	if ($("#first-name").val().length > 0 &&
		$("#last-name").val().length > 0 &&
		$("#email").val().length > 0 &&
		$("#phone").val().length > 0 &&
		$("#address").val().length > 0 
		) {
			console.log("true");
			return true;
		}
	else {
		console.log("False");
		false;
	}
	
}




