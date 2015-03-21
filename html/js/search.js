var input="";
var start="";
var end="";
var tileID=0;

$(document).ready( function() {
	
	$('body').on('click','.search-btn',function(){	
		event.preventDefault();
		getInput();
		showResult();
	});
	
	$('body').on('click','.search-tile',function(){	
		event.preventDefault();
		tileID = $(this).attr("id");
		console.log(tileID); 
		showTileData();
	});

	
});

function getInput() {
	input = $("#search-keywords").val();	
	start = $("#start-date").val();	
	end = $("#end-date").val();		
}


function showResult() {
	
	$.ajax ({
			type:"post",
			url: 'search-results.php',
			data:{'input': input, 'start_date': start, 'end_date': end, 'username': username}
			success:function(data){
						$("#search-results").html(data);				
			}
	})
}

function showTileData() {
	
	$.ajax ({		
			type:"post",
			url: 'tile-result.php',
			data: {'id': tileID}
			success:function(data){
					$("#search-results").html(data);
			}
	})
	
}