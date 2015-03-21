$(document).ready( function() {
	
	$('body').on('click','.save-record-btn',function(){	
		event.preventDefault();
		saveRecord();
	});	
});

function saveRecord() {
	var username = $("#username").val();	
	var password = $("#password").val();	
	var type = $("#type").val();		
	var startDate= $("#start-date").val();		
	var firstName = $("#first-name").val();		
	var lastName = $("#last-name").val();		
	var address = $("#address").val();		
	var email = $("#email").val();		
	var phone = $("#phone").val();		
	var doctor = $("#family-doctor").val();	
	
	console.log(firstName);

	$.ajax ({
			type:"post",
			url: '../php/create-user.php',
			data:{"username": username, "password": password, "clss": type,
					"start_date": startDate, "first_name": firstName, "last_name": lastName,
					"address": address, "email": email, "phone": phone,
					"doctor": doctor},
			success:function(data){
						$("body").html(data);			
			}
	})	
}
