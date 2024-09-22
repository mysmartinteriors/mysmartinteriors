<?php
	$id = "";
	$type="";
	$page_id = "";
	$text = "";
	$href="";
	$image ="";
	$orders = "";
	$status=0;
	$code='';

	$id = $dataQ['id'];
	$text = $dataQ['text'];
	$href = $dataQ['href'];
	$orders = $dataQ['orders'];
	$image = $dataQ['image'];
	$status = $dataQ['status'];
	$code = $dataQ['code'];
?>
<form id="edit_cat_form">
	<div class="">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="">
	        <div class="form-group">
	          	<label class="form-control-label">Category Name</label>
	          	<input class="form-control" name="text" value="<?=$text; ?>" type="text" data-edit="<?=$text; ?>">
	        </div>
	        <div class="form-group">
	          	<label class="form-control-label">Category Code</label>
	          	<input class="form-control" name="code" value="<?=$code; ?>" type="text" data-edit="<?=$code; ?>" disabled>
	        </div>
            <div class="form-group">
                <label class="form-control-label">Status</label>
                <select class="form-control custom-select" name="status">
                    <option value="1">Active</option>
                    <option value="0">In Active</option>
                </select>
                <script>$('[name=status]').val(<?php echo $status?>);</script>
            </div> 
	        <div class="form-group">
	          	<label class="form-control-label">Category Image (Optional)</label>	
                <input type="file" id="imageinput" name="attachment[]" class="dropify" data-max-file-size="5M" data-height="180" data-logo="<?php echo base_url().$dataQ['image'] ?>" data-url="<?php echo base_url().$dataQ['image'] ?>" title="" accept=".jpg,.png.jpeg"/>
                <input type="hidden" name="image_old" value="<?php echo $dataQ['image'] ?>">
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