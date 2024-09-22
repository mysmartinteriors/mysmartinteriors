<?php

$id = "";
$slider_name = "";
$slide_image="";
$main_text ="";
$sub_text='';
$shop_url='';
$id_ads='no';
$status=36;
$order=1;

if(isset($dataQ) && !empty($dataQ)){
	$id = $dataQ['id'];
	$slider_name = $dataQ['slider_name'];
	$slide_image = $dataQ['slide_image'];
	$main_text = $dataQ['main_text'];
	$sub_text = $dataQ['sub_text'];
	$shop_url=$dataQ['shop_url'];
	$is_ads=$dataQ['is_ads'];
	$status = $dataQ['status'];
	$order = $dataQ['orders'];
}

?>
<form id="addedit_slider_form">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Slider Name</label>
		          	<input class="form-control" name="slider_name" value="<?php echo $slider_name; ?>" type="text" data-edit="<?=$slider_name; ?>">
		        </div>	        
		        <div class="form-group">
		          	<label class="form-control-label">Main Text</label>
		          	<textarea class="form-control" name="main_text"><?php echo $main_text; ?></textarea>
		        </div>	        
		        <div class="form-group">
		          	<label class="form-control-label">Sub Text (Optional)</label>
		          	<textarea class="form-control" name="sub_text"><?php echo $sub_text; ?></textarea>
		        </div> 
		        <div class="form-group">
		          	<label class="form-control-label">Order</label>
		          	<input class="form-control" name="order" type="number" value="<?php echo $order; ?>">
		        </div> 
	            <div class="form-group">
	                <label class="form-control-label">Is Advertise</label>
	                <select class="form-control custom-select" name="is_ads">
	                    <option value="yes">Yes</option>
	                    <option value="no">No</option>
	                </select>
	                <script>$('[name=status]').val(<?php echo $status?>);</script>
	            </div>
	            <div class="form-group">
	                <label class="form-control-label">Status</label>
	                <select class="form-control custom-select" name="status">
	                    <option value="36">Active</option>
	                    <option value="37">In Active</option>
	                </select>
	                <script>$('[name=status]').val(<?php echo $status?>);</script>
	            </div>
		    </div>
      		<div class="col-lg-6">
		        <div class="form-group">d
		          	<label class="form-control-label required">Slider Image</label>	
	                <input type="file" id="imageinput" name="attachment[]" class="dropify" data-max-file-size="2M" accept="jpg,jpeg,png,PNG"  data-url="<?php echo base_url()?>uploads/sliders/" data-logo="<?php echo $slide_image ?>">
	                <input type="hidden" name="image_old" value="<?php echo $slide_image ?>">
				</div>
	        </div>
      		<div class="col-lg-12">	        
		        <div class="form-group">
			        <label class="form-control-label">Shop URL</label>
	                <div class="input-group form-control-solid mb-3">
	                    <span class="input-group-addon"><?php echo base_url()?>products?</span>
	                    <input class="form-control" name="shop_url" type="text" value="<?php echo $shop_url ?>" >
	                </div>
		        </div>
      		</div>
	    </div>
	    <div class="row">
		  	<div class="col-sm-12 form-group text-center mt-3">
                <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Save Slider</button>
        	</div>
		</div>
	</div>
</form>