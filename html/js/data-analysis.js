var data_cube;
var patient_items = [];
var test_type_items = [];
var date_items = [];

var patient_value = 'All';
var test_type_value = 'All';
var date_value = 'All';

var interval = 2;

$(document).ready(function() {
	
	getDataCube();

	
	$("#patient-list").change(function() {
		patient_value = $(this).val();	
		getSum();
	});
	$("#test-type-list").change(function() {
		test_type_value = $(this).val();
		getSum();
	});
	$("#date-list").change(function() {
		date_value = $(this).val();
		getSum();
	});
	$("body").on("click", "#yearly-btn", function() {
		interval = 2;
		getDataCube();
	});
	$("body").on("click", "#weekly-btn", function() {
		interval = 1;
		getDataCube();
	});
	$("body").on("click", "#daily-btn", function() {
		interval = 0;
		getDataCube();
	});
	

});



//Gets the sum from the data cube
function getSum(){
	numImages = 0;
	for(var i = 0; i < data_cube.length; i++){
		
		if (data_cube[i].PATIENT_NAME == patient_value &&
			data_cube[i].TEST_TYPE == test_type_value &&
			data_cube[i].DATSTR == date_value) {
				found = true;
				numImages = data_cube[i].CNT;
			}
	}
	$(".data-sum").html("<p>" + numImages + " </p>Images");
}

//Gets the data cube from the server
function getDataCube(){
	//Reset Variables
	data_cube = '';
	patient_items = [];
	test_type_items = [];
	date_items = [];
	patient_value = 'All';
	test_type_value = 'All';
	date_value = 'All';
	$("#patient-list").html('<option value="-1" selected>Choose a Patient</option>');
	$("#test-type-list").html('<option value="-1" selected>Choose a Test Type</option>');
	$("#date-list").html('<option value="-1" selected>Choose a Time Interval</option>');
	
		$.ajax({
		url:'../php/get-data-cube.php?interval=' + interval,
		success:function(data){
			data = JSON.parse(data);
			data = removeNullData(data);
			data_cube = data;
			getDataCubeItems();
			putItemsInSelect();
		}
	});
}

//Gets all of the items that are in the data cube
function getDataCubeItems(){
	for(var i = 0; i < data_cube.length; i++){
		if (patient_items.indexOf(data_cube[i].PATIENT_NAME) < 0) { //If the patients name has not been added yet to the select, then add it
			patient_items.push(data_cube[i].PATIENT_NAME);
		}
		if (test_type_items.indexOf(data_cube[i].TEST_TYPE) < 0) { //If the test_type has not been added yet to the select, then add it
			test_type_items.push(data_cube[i].TEST_TYPE);
		}
		if (date_items.indexOf(data_cube[i].DATSTR) < 0) { //If the date has not been added yet to the select, then add it
			date_items.push(data_cube[i].DATSTR);
		}
	}
}
//Populates the html with the proper items
function putItemsInSelect(){
	for(var i = 0; i < patient_items.length; i++){
		$("#patient-list").append("<option value='" + patient_items[i] + "'>" + patient_items[i] + "</options>");
	}
	for(var i = 0; i < test_type_items.length; i++){
		$("#test-type-list").append("<option value='" + test_type_items[i] + "'>" + test_type_items[i] + "</options>");
	}
	for(var i = 0; i < date_items.length; i++){
		$("#date-list").append("<option value='" + date_items[i] + "'>" + date_items[i] + "</options>");
	}
}

//Removes the null data from the data_cube.  Renames it to 'All'
function removeNullData(data){
	for (var i = 0; i < data.length; i++){
		if (data[i].PATIENT_NAME == null){
			data[i].PATIENT_NAME == "All";
		}
		if (data[i].TEST_TYPE == null){
			data[i].TEST_TYPE = "All";
		}
		if (data[i].DATSTR == null){
			data[i].DATSTR = "All";
		}		
	}
	return data;
}