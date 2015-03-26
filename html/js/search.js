var input="";
var start="";
var end="";
var tileID=0;

$(document).ready( function() {
	
	$('body').on('click','.search-btn',function(){	
		event.preventDefault();
		getInput();
	});
	
	$('body').on('click','.search-tile',function(){	
		event.preventDefault();
		tileID = $(this).attr("id");
		console.log(tileID); 
		showTileData();
	});
	
	
	//Modal Image
	$('body').on('click', '.thumbnail', function() {
		var imageSrc = $(this).find('img').attr("src");
		$('#modal-image').attr("src", imageSrc);
		console.log(imageSrc);
	});
	
});

function getInput() {
	input = $("#search-keywords").val();	
	start = $("#start-date").val();	
	end = $("#end-date").val();		
	searchType = $("#search-type").val();
	
	var sort_type = 'n';	
		
	if($('#a').is(':checked')){
		sort_type = 'a';
	}
	else if ($("#d").is(':checked')) {
		sort_type = 'd';
	}
	
	console.log(sort_type);

	
	$.ajax ({
			type:"post",
			url: 'search-results.php',
			data:{'input': input, 'start_date': start, 'end_date': end, 'search_type': searchType, 'sort_type':sort_type},
			success:function(data){
						$("#search-results").html(data);
			}
	});
}




function showTileData() {
	
	$.ajax ({		
			type:"post",
			url: 'tile-result.php',
			data: {'id': tileID},
			success:function(data){
					$("#search-results").html(data);
			}
	})
	
}