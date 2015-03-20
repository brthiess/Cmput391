<?php 
function print_tile($record_id){
	//Get Information from DB based on Record ID

	echo '<div class="col-lg-3 col-md-4 col-sm-6">
	<div class="search-tile center-block" id="id-' . $record_id . '">
		<div class="name">
			Brad Thiessen
		</div>
		<div class="doctor">
			Dr. Smith
		</div>
		<div class="date">
			<div class="day">
				03
			</div>
			<div class="month">
				March
			</div>
			<div class="year">
				2015
			</div>
		</div>
		<div class="diagnosis">
			Chicken Pox
		</div>
	</div>
</div>';	
}
?>

