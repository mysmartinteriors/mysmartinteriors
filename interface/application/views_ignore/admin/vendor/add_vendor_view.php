<?php

$id = "";
$name = "";
$code = "";
$email = "";
$phone = '';
$address = '';
$pincode = '';
$status = 42;

if (isset($dataQ) && !empty($dataQ)) {
	$id = $dataQ['id'];
	$name = $dataQ['name'];
	$code = $dataQ['code'];
	$email = $dataQ['email'];
	$phone = $dataQ['phone'];
	$address = $dataQ['address'];
	$pincode = $dataQ['pincode'];
	$status = $dataQ['status'];
}

?>
<form id="addedit_vendor_form">
	<input type="hidden" name="id" value="<?= $id; ?>">
	<!-- Card body -->
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Name<span class="text-danger">*</span></label>
				<input class="form-control" name="name" value="<?php echo $name; ?>" type="text"
					data-edit="<?= $name; ?>" required>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Code<span class="text-danger">*</span></label>
				<input class="form-control" name="code" value="<?php echo $code; ?>" type="text"
					data-edit="<?= $code; ?>" required>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Email</label>
				<input class="form-control" name="email" value="<?php echo $email; ?>" type="text"
					data-edit="<?= $email; ?>">
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Phone<span class="text-danger">*</span></label>
				<input class="form-control" name="phone" value="<?php echo $phone; ?>" type="text"
					data-edit="<?= $phone; ?>" required>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Password<span class="text-danger">*</span></label>
				<input class="form-control" name="password" type="password" required>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Address<span class="text-danger">*</span></label>
				<textarea class="form-control" name="address" required><?php echo $address; ?></textarea>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Pincode<span class="text-danger">*</span></label>
				<input class="form-control" name="pincode" value="<?php echo $pincode; ?>" type="text"
					data-edit="<?= $pincode; ?>" required>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Status<span class="text-danger">*</span></label>
				<select class="form-control custom-select" name="status">
					<option value="42">Active</option>
					<option value="43">In Active</option>
				</select>
				<script>$('[name=status]').val(<?php echo $status ?>);</script>
			</div>
		</div>
	</div>

	</div>
	<div class="row">
		<div class="col-sm-12 form-group text-center mt-3">
			<button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving"
				class="btn btn-primary">Save Vendor</button>
		</div>
	</div>
	</div>
</form>