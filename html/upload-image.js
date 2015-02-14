var numberOfImages = 1;

//Adds medical image to database and displays it to Radiologist
function addImage() {
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
		alert("Not an image");
		return;
	}
      
	  var reader  = new FileReader();

       reader.onloadend = function () {
           $("#img-" + numberOfImages).attr("src", reader.result);
		   numberOfImages += 1;		   
       }

       if (file) {
           reader.readAsDataURL(file); //reads the data as a URL
       } else {
           $("#img-" + numberOfImages).attr("src", "");
       }
}
