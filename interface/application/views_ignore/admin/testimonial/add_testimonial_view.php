<?php

$id = "";
$name = "";
$image="";
$description ="";
$status=46;

if(isset($dataQ) && !empty($dataQ)){
	// foreach ($dataQ as $rows) {
		$id = $dataQ['id'];
		$name = $dataQ['name'];
		$image = $dataQ['image'];
		$description = $dataQ['description'];
		$status = $dataQ['status'];
	// }
}

?>
<form id="addedit_slider_form">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Name</label>
		          	<input class="form-control" name="name" value="<?php echo $name; ?>" type="text" data-edit="<?=$name; ?>">
		        </div>	        
		        <div class="form-group">
		          	<label class="form-control-label">Description</label>
		          	<textarea class="form-control" name="description"><?php echo $description; ?></textarea>
		        </div>	        
		        
	            <div class="form-group">
	                <label class="form-control-label">Status</label>
	                <select class="form-control custom-select" name="status">
	                    <option value="46">Active</option>
	                    <option value="47">In Active</option>
	                </select>
	                <script>$('[name=status]').val(<?php echo $status?>);</script>
	            </div>
		    </div>
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label required">Image</label>	
	                <input type="file" id="imageinput" name="attachment[]" class="dropify"/ data-max-file-size="2M" accept="jpg,jpeg,png,PNG" data-max-width="1970" data-ma-height="800"  data-min-width="v" data-min-height="800" data-url="<?php echo base_url()?>uploads/testimonials/" data-logo="<?php echo $image ?>">
	                <input type="hidden" name="image_old" value="<?php echo $image ?>">
				</div>
	        </div>
	    </div>
	    <div class="row">
		  	<div class="col-sm-12 form-group text-center mt-3">
                <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Save Testimonial</button>
        	</div>
		</div>
	</div>
</form>