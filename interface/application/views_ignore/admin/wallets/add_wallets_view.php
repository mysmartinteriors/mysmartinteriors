<?php

$id = "";
$basic_amount = "";
$wallet_points = "";
$status = 44;

if (isset($dataQ) && !empty($dataQ)) {
	$id = $dataQ['id'];
	$basic_amount = $dataQ['basic_amount'];
	$wallet_points = $dataQ['wallet_points'];
	$status = $dataQ['status'];
}

?>
<form id="addedit_slider_form">
	<input type="hidden" name="id" value="<?= $id; ?>">
	<!-- Card body -->
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Subscription Amount</label>
				<input class="form-control" name="basic_amount" value="<?php echo $basic_amount; ?>" type="number"
					data-edit="<?= $basic_amount; ?>">
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Wallet Amount</label>
				<input class="form-control" name="wallet_points" value="<?php echo $wallet_points; ?>" type="number"
					data-edit="<?= $wallet_points; ?>">
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Status</label>
				<select class="form-control custom-select" name="status">
					<option value="44">Active</option>
					<option value="45">In Active</option>
				</select>
				<script>$('[name=status]').val(<?php echo $status ?>);</script>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 form-group text-center mt-3">
			<button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving"
				class="btn btn-primary">Save Subscription</button>
		</div>
	</div>
	</div>
</form>