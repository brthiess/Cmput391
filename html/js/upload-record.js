var spinner;

$(document).ready( function() {
	$("body").on("click", ".upload-record-btn", function() {
		event.preventDefault();
		uploadRecord();
		var target = document.getElementById('radiology-form');
		spinner = new Spinner(opts).spin(target);
	});
	
		//Watches to make sure all fields in the form are filled out before allowing admin to add/edit a user
	$(".form-control").change(function() {
		if (allFieldsFilled()) {
			$(".upload-record-container").html('<button class="btn btn-info full-width-btn upload-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".upload-record-container").html('<fieldset disabled><button class="btn btn-info full-width-btn upload-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
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


//Uploads or updates a record that was just entered into the system
function uploadRecord(){
	var patientID = $("#patient-id").val();
	var doctorID = $("#doctor-id").val();
	var radiologistID = $("#radiologist-id").val();
	var testType = $("#test-type").val();
	var diagnosis = $("#diagnosis").val();
	var description = $("#description").val();
	var testDate = $("#test-date").val();
	var prescribingDate = $("#prescribing-date").val();
	
		$.ajax ({
			type: "post",
			url: "../php/upload-record.php",
			data: {"patient_id": patientID, "doctor_id": doctorID, "radiologist_id": radiologistID, "test_type": testType, "diagnosis": diagnosis, "description": description, "test_date": testDate, "prescribing_date": prescribingDate},
			success:function(data){	
				spinner.stop();
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

//Returns true if all fields in the form have a value
function allFieldsFilled() {
	if ($("#doctor-id").val().length > 0 &&
		$("#radiologist-id").val().length > 0 &&
		$("#test-type").val().length > 0 &&
		$("#diagnosis").val().length > 0 &&
		$("#description").val().length > 0	&&
		$("#prescribing-date").val().length > 0	&&	
		$("#test-date").val().length > 0		
		) {
			console.log("true");
			return true;
		}
	else {
		console.log("False");
		false;
	}
}