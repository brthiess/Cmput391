var spinner;
var allDoctors;
var allPatients;
var allRadiologists;

$(document).ready( function() {	
	//Set the upload record button to listen for clicks
	$("body").on("click", ".upload-record-btn", function(event) {
		event.preventDefault();
		uploadRecord();
		var target = document.getElementById('radiology-form');
		spinner = new Spinner(opts).spin(target);
	});
	
		//Watches to make sure all fields in the form are filled out before allowing admin to add/edit a user
	$(".form-control").change(function() {
		check_form();
	});
	
	allDoctors = getAllDoctors();
	allPatients = getAllPatients();
	allRadiologists = getAllRadiologists();
	

	
	//Check the form for validity
	check_form();
	
	$("#doctor-id").autocomplete({
		source: allDoctors,
		change: function(event, ui){
			check_form();
		}
	});
	
	$("#radiologist-id").autocomplete({
		source: allRadiologists,
		change: function(event, ui){
			check_form();
		}
	});
	
	$(".form-control").each(function() {
		$(this).addClass("input-wrong");
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
	
	
	imageArray = [];
	
	$(".radiology-image").each(function(index){
		imageArray.push($(this).attr("src"));		
	});
	
	var images = JSON.stringify(imageArray);
	console.log(images);
	
		$.ajax ({
			type: "post",
			url: "../php/upload-record.php",
			data: {"patient_id": patientID, "doctor_id": doctorID, "radiologist_id": radiologistID, "test_type": testType, "diagnosis": diagnosis, "description": description, "test_date": testDate, "prescribing_date": prescribingDate, "images": images},
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


//Checks if the given input contains a valid doctor id
function inputIsDoctor(input){
	var value = $("#" + input).val();
	
	if (allDoctors.indexOf(value) > -1){
		return true;
	}
	else {
		return false;
	}
}

//Checks if the given input contains a valid radiologist id
function inputIsRadiologist(input){
	var value = $("#" + input).val();
	
	if (allRadiologists.indexOf(value) > -1){
		return true;
	}
	else {
		return false;
	}
}

//Checks if the given input contains a valid patient ID
function inputIsPatient(input){
	var value = $("#" + input).val();
	if (allPatients.indexOf(value) > -1){
		return true;
	}
	else {
		return false;
	}
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
			return true;
		}
	else {
		false;
	}
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

function getAllRadiologists(){
	allRadiologists = [];
	$.ajax({
		url: '../php/get-all-radiologist-ids.php',
		success:function(data){
			allRadiologistsJSON = JSON.parse(data);
			for (var i = 0; i < allRadiologistsJSON.length; i++){
					allRadiologists[i] = allRadiologistsJSON[i].PERSON_ID;
			}
		}
	});
	return allRadiologists;
}

function getAllPatients() {
	allPatients = [];
	$.ajax({
		url: '../php/get-all-patients-ids.php',
		success:function(data){
			allPatientsJSON = JSON.parse(data);
			for (var i = 0; i < allPatientsJSON.length; i++){
					allPatients[i] = allPatientsJSON[i].PERSON_ID;
			}
			check_form();
		}
	});
	return allPatients;
}


function check_form() {
		//Check to see if every input has some input
		//If so, then put check mark beside it (for now)
		inputHasValue();
		
		
		if (inputIsDoctor('doctor-id')){
			setInputsCorrect(['doctor-id'], true, false);
		}
		else {
			setInputsCorrect(['doctor-id'], false, false);
		}
		
		if(inputIsPatient('patient-id')){
			setInputsCorrect(['patient-id'], true, false);
		}
		else {
			setInputsCorrect(['patient-id'], false, false);
		}
		
		if(inputIsRadiologist('radiologist-id')){
			setInputsCorrect(['radiologist-id'], true, false);
		}
		else {
			setInputsCorrect(['radiologist-id'], false, false);
		}
		
		if (testDateIsValid('test-date', 'prescribing-date')){
			setInputsCorrect(['test-date'], true, false);
			setInputsCorrect(['prescribing-date'], true, false);
		}
		else {
			setInputsCorrect(['test-date'], false, false);
			setInputsCorrect(['prescribing-date'], false, false);
		}
		
		if (allFieldsCorrect()) {
			$(".upload-record-container").html('<button class="btn btn-info upload-record-btn"><strong><span class="glyphicon glyphicon-floppy-save " aria-hidden="true"></span> Save Record</strong></button>');
		}
		else {
			$(".upload-record-container").html('<fieldset disabled><button class="btn btn-info upload-record-btn"><strong><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Save Record</strong></button></fieldset>');
		}
	}
	
	
//Makes sure that the test date is after the prescribing date
function testDateIsValid(testDate, prescribingDate){
	testDate = new Date($("#" + testDate ).val());
	prescribingDate = new Date($("#" + prescribingDate ).val());
	if (testDate >= prescribingDate){
		return true;			
	}
	else {
		return false;
	}
	
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