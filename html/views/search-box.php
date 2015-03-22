	<div class="col-md-4 top-buffer">		
		<div class="general-form col-sm-10">
		<div class="text-center col-md-12"><h2>Search</h2></div>
			<form class="form-group" action="../php/Search.php" method="post">
				<div class="col-sm-12">
					<label class="control-label" for="search-keywords"><?php echo $search_type;?></label>
					<input type="text" class="form-control" id="search-keywords" name="search-keywords">
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="start-date">Start Date</label>
					<input type="text" class="form-control" id="start-date" name="start-date">
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="end-date">End Date</label>
					<input type="text" class="form-control" id="end-date" name="end-date">
					<input type="hidden" class="form-control" id="search-type" value="<?php echo $search_type_short;?>" name="end-date">
				</div>
				<div class="col-sm-12">
					<div class="col-sm-12 text-center">
						<label>Sort Results By:</label>
					</div>

					<div class="btn-group text-center" data-toggle="buttons">
						<div class="col-sm-12">
							<label class="btn active radio-btn">
								<input type="radio" name="options" id="a" autocomplete="off" checked>Time Ascending
							</label>
						</div>
						<div class="col-sm-12">
							<label class="btn radio-btn">
								<input type="radio" name="options" id="d" autocomplete="off"> Time Descending
							</label>
						</div>
						<div class="col-sm-12">
							<label class="btn radio-btn">
								<input type="radio" name="options" id="n" autocomplete="off"> No Sort
							</label>
						</div>
					</div>
				</div>
				<div class="col-sm-12 top-buffer">
					<button type="submit" class="search-btn btn btn-info center-block"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search Records</strong></button>
				</div>
			</form>
		</div>
	</div>