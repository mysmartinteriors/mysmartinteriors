<?php

$id = "";
$coupon_code = "";
$price_type="";
$coupon_value ="";
$min_purchase='';
$valid_from='';
$valid_to='';
$applicable_to='';
$status=1;

if(isset($dataQ)){
	foreach ($dataQ->result() as $rows) {
		$id = $rows->id;
		$coupon_code = $rows->coupon_code;
		$price_type = $rows->price_type;
		$coupon_value = $rows->coupon_value;
		$min_purchase = $rows->min_purchase;
		$valid_from=getMyDbDate('%d-%m-%Y %H:%i:%s',$rows->valid_from);
		$valid_to=getMyDbDate('%d-%m-%Y %H:%i:%s',$rows->valid_to);
		$applicable_to=$rows->applicable_to;
		$status = $rows->status;
	}
}

?>
<form id="addedit_slider_form">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Coupon Code</label>
		          	<input class="form-control" name="coupon_code" value="<?php echo $coupon_code; ?>" type="text" data-edit="<?=$coupon_code; ?>">
		        </div>
		    </div>
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Applicable To</label>
		          	<select class="form-control" name="applicable_to">
		          		<option value="0">All</option>
		          		<option value="1">New customers</option>
		          	</select>
	                <script>$('[name=applicable_to]').val(<?php echo $applicable_to?>);</script>
		        </div>
		    </div>
		</div>
		<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Value Type</label>
		          	<select class="form-control" name="price_type">
		          		<option value="perc">Percentage</option>
		          		<option value="inr">Price</option>
		          	</select>
	                <script>$('[name=price_type]').val('<?php echo $price_type?>');</script>
		        </div>
		    </div>
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Value</label>
		          	<input class="form-control" name="coupon_value" value="<?php echo $coupon_value; ?>" type="text">
		        </div>
	        </div>
	    </div> 
		<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		            <label class="form-control-label">Valid From</label>
	      			<div class="input-group date valid_from">
	                    <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
	                    <input class="form-control" value="<?php echo $valid_from?>" name="valid_from">
	                </div>
	            </div>
	        </div>
      		<div class="col-lg-6">
		        <div class="form-group">
	            <label class="form-control-label">Valid Upto</label>
	      			<div class="input-group date valid_to">
	                    <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
	                    <input class="form-control" value="<?php echo $valid_to?>" name="valid_to">
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">On Min Purchase</label>
		          	<input class="form-control" name="min_purchase" value="<?php echo $min_purchase; ?>" type="text">
		        </div>
	        </div>
      		<div class="col-lg-6">
	            <div class="form-group">
	                <label class="form-control-label">Status</label>
	                <select class="form-control custom-select" name="status">
	                    <option value="1">Active</option>
	                    <option value="0">In Active</option>
	                </select>
	                <script>$('[name=status]').val(<?php echo $status?>);</script>
	            </div>
		    </div>
		</div>
	    <div class="row">
		  	<div class="col-sm-12 form-group text-center mt-3">
                <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Submit</button>
        	</div>
		</div>
	</div>
</form>