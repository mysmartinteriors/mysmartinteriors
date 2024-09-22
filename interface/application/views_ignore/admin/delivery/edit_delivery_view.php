<?php

$id = "";
$name = "";
$code="";
$email ="";
$phone='';
$address='';
$pincode='';
$password='';
$availability='';
$profile_picture='';
$aadhaar_card='';
$pan_card='';
$driving_licence='';
$vehicle_rc='';
$status=40;

if(isset($dataQ) && !empty($dataQ)){
	// foreach ($dataQ as $rows) {
		$id = $dataQ['id'];
		$name = $dataQ['name'];
		$code = $dataQ['code'];
		$email = $dataQ['email'];
		$phone = $dataQ['phone'];
		$address=$dataQ['address'];
		$pincode = $dataQ['pincode'];
		$password = $dataQ['password'];
		$availability = $dataQ['availability'];
		$profile_picture=$dataQ['profile_picture'];
		$aadhaar_card = $dataQ['aadhaar_card'];
		$pan_card = $dataQ['pan_card'];
		$driving_licence = $dataQ['driving_licence'];
		$vehicle_rc = $dataQ['vehicle_rc'];
		$status = $dataQ['status'];
	// }
}

?>
<form id="edit_delivery_form">
	  	<input type="hidden" name="id" value="<?=$id; ?>" >
      	<!-- Card body -->
      	<div class="row">
      		<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Full Name <span class="text-danger">*</span> </label>
		          	<input class="form-control" name="name" value="<?php echo $name; ?>" type="text" data-edit="<?=$name; ?>" required>
		        </div>	  
			</div>      
			<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Code <span class="text-danger">*</span> </label>
		          	<input class="form-control" name="code" value="<?php echo $code; ?>" type="text" data-edit="<?=$code; ?>" required>
		        </div>	    
				</div>      
			<div class="col-lg-6">    
				<div class="form-group">
					  <label class="form-control-label">Email <span class="text-danger">*</span> </label>
					  <input class="form-control" name="email" value="<?php echo $email; ?>" type="text" data-edit="<?=$email; ?>" required>
				</div> 
				</div>      
			<div class="col-lg-6">
				<div class="form-group">
					  <label class="form-control-label">Phone <span class="text-danger">*</span> </label>
					  <input class="form-control" name="phone" value="<?php echo $phone; ?>" type="text" data-edit="<?=$phone; ?>" required>
				</div> 
				</div>      
			<div class="col-lg-6">
				<div class="form-group">
					  <label class="form-control-label">Password </label>
					  <input class="form-control" name="password" value="" type="password" >
				</div> 
				</div>      
			<div class="col-lg-6">
		        <div class="form-group">
		          	<label class="form-control-label">Address <span class="text-danger">*</span> </label>
		          	<textarea class="form-control" name="address" required><?php echo $address; ?></textarea>
		        </div> 
				</div>      
			<div class="col-lg-6">
				<div class="form-group">
					  <label class="form-control-label">Pincode <span class="text-danger">*</span> </label>
					  <input class="form-control" name="pincode" value="<?php echo $pincode; ?>" type="text" data-edit="<?=$pincode; ?>" required>
				</div> 
				</div>      
			<div class="col-lg-6">
				<div class="form-group">
					  <label class="form-control-label">Availability <span class="text-danger">*</span> </label>
					  <select class="form-control" name="availability" id="">
						<option value="">-- select --</option>
						<option value="fulltime">Full Time [9 Hours]</option>
						<option value="parttime">Part Time [4 - 5 Hours]</option>
						<option value="sunday">Sunday Only</option>
					  </select>
				</div> 
				</div>      
				<div class="col-lg-6">
					<div class="ibox"> 
						<div class="ibox-head">                                          
							<label class="col-form-label">Profile Picture <span class="text-danger">*</span></label>
						</div>
						<div class="ibox-body">
							<input type="file" id="imageinput" name="profile_picture[]" class="dropify" data-max-file-size="2M" data-height="180" data-url="<?php echo base_url()?>uploads/delivery/" data-logo="<?php echo $profile_picture ?>" title="" accept=".jpg,.png.jpeg"/>
							<input type="hidden" name="profile_picture" value="<?php echo $profile_picture ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ibox"> 
						<div class="ibox-head">                                          
							<label class="col-form-label">Aadhaar Card <span class="text-danger">*</span></label>
						</div>
						<div class="ibox-body">
							<input type="file" id="imageinput" name="aadhaar_card[]" class="dropify" data-max-file-size="2M" data-height="180" data-url="<?php echo base_url()?>uploads/delivery/" data-logo="<?php echo $aadhaar_card ?>" title="" accept=".jpg,.png.jpeg"/>
							<input type="hidden" name="aadhaar_card" value="<?php echo $aadhaar_card ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ibox"> 
						<div class="ibox-head">                                          
							<label class="col-form-label">PAN Card <span class="text-danger">*</span></label>
						</div>
						<div class="ibox-body">
							<input type="file" id="imageinput" name="pan_card[]" class="dropify" data-max-file-size="2M" data-height="180" data-url="<?php echo base_url()?>uploads/delivery/" data-logo="<?php echo $pan_card ?>" title="" accept=".jpg,.png.jpeg"/>
							<input type="hidden" name="pan_card" value="<?php echo $pan_card ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ibox"> 
						<div class="ibox-head">                                          
							<label class="col-form-label">Driving Licence <span class="text-danger">*</span></label>
						</div>
						<div class="ibox-body">
							<input type="file" id="imageinput" name="driving_licence[]" class="dropify" data-max-file-size="2M" data-height="180" data-url="<?php echo base_url()?>uploads/delivery/" data-logo="<?php echo $driving_licence ?>" title="" accept=".jpg,.png.jpeg"/>
							<input type="hidden" name="driving_licence" value="<?php echo $driving_licence ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ibox"> 
						<div class="ibox-head">                                          
							<label class="col-form-label">Vehicle RC <span class="text-danger">*</span></label>
						</div>
						<div class="ibox-body">
							<input type="file" id="imageinput" name="vehicle_rc[]" class="dropify" data-max-file-size="2M" data-height="180" data-url="<?php echo base_url()?>uploads/delivery/" data-logo="<?php echo $vehicle_rc ?>" title="" accept=".jpg,.png.jpeg">
							<input type="hidden" name="vehicle_rc" value="<?php echo $vehicle_rc ?>">
						</div>
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
                <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving" class="btn btn-primary">Save Delivery Boy</button>
        	</div>
		</div>
	</div>
</form>