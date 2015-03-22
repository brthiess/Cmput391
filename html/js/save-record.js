var spinner;
$(document).ready( function() {


	$('body').on('click','.save-record-btn',function(){	
		event.preventDefault();
		saveRecord();
		var target = document.getElementById('form');
		spinner = new Spinner(opts).spin(target);
	});	
	
	$(".form-control").change(function() {
		if (allFieldsFilled()) {
			$(".save-record-container").html('<button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".save-record-container").html('<fieldset disabled><button class="btn btn-info full-width-btn save-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
		}
	});

	
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
	
	console.log("TYPE: "  + type);

	$.ajax ({
			type:"post",
			url: '../php/create-user.php',
			data:{"username": username, "password": password, "clss": type,
					"start_date": startDate, "first_name": firstName, "last_name": lastName,
					"address": address, "email": email, "phone": phone,
					"doctor": doctor},
			success:function(data){
				spinner.stop();
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
			console.log("true");
			return true;
		}
	else {
		console.log("False");
		false;
	}
	
}



