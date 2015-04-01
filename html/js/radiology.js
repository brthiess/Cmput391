var allPatients = [];
$(document).ready(function(){
	
	getAllPatients();
	$("#patient-id").autocomplete({
		source: allPatients
	});

	
});

function getAllPatients(){
	allPatients = [];
	$.ajax({
		url: '../php/get-all-patients-ids.php',
		success:function(data){
			data = JSON.parse(data);
			for(var i = 0; i < data.length; i++){
				allPatients.push(data[i].PERSON_ID);
			}
			console.log(allPatients);
		}
	});	
}