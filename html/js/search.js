var input="";
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
	
}


function showResult() {
	
	$.ajax ({
			type:"post",
			url: 'search-results.php',
			data:'input='+input,
			success:function(data){
						$("#search-results").html(data);				
			}
	})
}

function showTileData() {
	
	$.ajax ({		
			type:"post",
			url: 'tile-result.php',
			data: 'id='+tileID,
			success:function(data){
					$("#search-results").html(data);
			}
	})
	
}