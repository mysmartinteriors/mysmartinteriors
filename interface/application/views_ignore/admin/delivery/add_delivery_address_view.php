<?php

$id = "";
$address = "";
$delivery_charge="";
$pincode="";
$status=40;

if(isset($dataQ) && !empty($dataQ)){
	// foreach ($dataQ as $rows) {
		$id = $dataQ['id'];
		$address=$dataQ['address'];
		$delivery_charge = $dataQ['delivery_charge'];
		$pincode = $dataQ['pincode'];
		$status = $dataQ['status'];
	// }
}

?>
<form id="addedit_delivery_form">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Address <span class="text-danger">*</span> </label>
		          	<input class="form-control" name="address" value="<?php echo $address; ?>" type="text" data-edit="<?=$address; ?>" required>
		        </div>	  
			</div>      
			<div class="col-lg-6">
				<div class="form-group">
					  <label class="form-control-label">Pincode</label>
					  <input class="form-control" name="pincode" value="<?php echo $pincode; ?>" type="text" data-edit="<?=$pincode; ?>">
				</div> 
				</div>
			<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Delivery Charge <span class="text-danger">*</span> </label>
		          	<input class="form-control" name="delivery_charge" value="<?php echo $delivery_charge; ?>" type="text" data-edit="<?=$delivery_charge; ?>" required>
		        </div>	    
				</div>      
			  
			     
			      
				
			<div class="col-lg-6">
	            <div class="form-group">
	                <label class="form-control-label">Status <span class="text-danger">*</span> </label>
	                <select class="form-control custom-select" name="status" required>
	                    <option value="40">Active</option>
	                    <option value="41">In Active</option>
	                </select>
	                <script>$('[name=status]').val(<?php echo $status?>);</script>
	            </div>
				</div>
		    </div>
      		
	    </div>
	    <div class="row">
		  	<div class="col-sm-12 form-group text-center mt-3">
                <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Save</button>
        	</div>
		</div>
	</div>
</form>