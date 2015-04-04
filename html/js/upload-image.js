$( document ).ready(function() {
    $(".medical-image").css("min-height", $(".radiology-form").height());
});

//Adds medical image to database and displays it to Radiologist
function addImage() {
	
	if (typeof imageNumber == "undefined") {
		imageNumber = 1;
	}
	
    var file    = document.querySelector('input[type=file]').files[0];

	//Check if file is image
	var re = /(?:\.([^.]+))?$/;	
	var ext = re.exec(file.name);
	if (ext[1] != 'jpg' 	&& 
		ext[1] != 'jpeg'	&& 
		ext[1] != 'gif' 	&& 
		ext[1] != 'png' 	&& 
		ext[1] != 'bmp' 	&& 
		ext[1] != 'dicom' 	){
		alert("Not an image or image is too large");
		return;
	}
      
	  var reader  = new FileReader();

       reader.onloadend = function () {	   
		   //Append New Image
		   $("#upload-images-div").append("<div class='col-sm-4 image-row' id='image-row-" + imageNumber + "'>\
													<div class='image-holder'>\
														<div class='delete-image-caption' onclick='deleteImage(" + imageNumber + ")'></div>\
														<img class='radiology-image' id='img-" + imageNumber + "'  height='100' src='" + reader.result + "' alt=''>\
													</div>\
											</div>");
			imageNumber += 1;
       }

       if (file) {
           reader.readAsDataURL(file); //reads the data as a URL
       } else {
           $("#img-" + imageNumber).attr("src", "");
       }
}

//Delete specified image
function deleteImage(imageNumber) {
	$("#image-row-" + imageNumber).remove();
}
