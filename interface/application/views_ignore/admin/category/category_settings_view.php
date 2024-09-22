<?php
	$id = "";
	$type="";
	$page_id = "";
	$text = "";
	$href="";
	$url_opens ="";
	$orders = "";

	foreach ($dataQ->result() as $rows) {
		$id = $rows->id;
		$text = $rows->text;
		$href = $rows->href;
		$orders = $rows->orders;
	}
?>
<form id="cat_sett_form">
	<div class="">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="">
	        <div class="form-group">
	          	<label class="form-control-label">Category Name</label>
	          	<input class="form-control" name="text" value="<?=$text; ?>" type="text" data-edit="<?=$text; ?>" readonly>
	        </div>
	        <div class="fallback dropzone">
				<input name="file" type="file" multiple="multiple">
			</div>
		  	<div class="form-group text-center">
                <div class="col-sm-12">
                    <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Update</button>
                </div>					
        	</div>
			<div id="mnresponse_result"></div>
      	</div>
	</div>

</form>