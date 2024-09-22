<?php

$id = "";
$name = "";
$percentage = "";
$status = 52;

if (isset($dataQ) && !empty($dataQ)) {
	$id = $dataQ['id'];
	$name = $dataQ['name'];
	$code = $dataQ['code'];
	$percentage = $dataQ['percentage'];
	$status = $dataQ['status'];
}

?>
<form id="addedit_reference_form">
	<input type="hidden" name="id" value="<?= $id; ?>">
	<!-- Card body -->
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Code</label>
				<input class="form-control" name="code" value="<?php echo $name; ?>" type="text"
					data-edit="<?php echo $dataQ['code']; ?>">
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Name</label>
				<input class="form-control" name="name" value="<?php echo $name; ?>" type="text"
					data-edit="<?php echo $dataQ['name']; ?>">
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Percentage</label>
				<input class="form-control" name="percentage" value="<?php echo $percentage; ?>" type="number"
					data-edit="<?php echo $dataQ['percentage'] ?>">
			</div>
		</div>
		<!-- <div class="col-lg-6">
			<div class="form-group">
				<label class="form-control-label">Status</label>
				<select class="form-control custom-select" name="status">
					<option value="52">Active</option>
					<option value="53">In Active</option>
				</select>
				<script>$('[name=status]').val(<?php //echo $status ?>);</script>
			</div>
		</div> -->
	</div>
	<div class="row">
		<div class="col-sm-12 form-group text-center mt-3">
			<button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving"
				class="btn btn-primary">Save</button>
		</div>
	</div>
	</div>
</form>



<script>
	save_add_data();
	function save_add_data() {
		$('#addedit_reference_form').validate({
			rules: {
				name: { required: true },
				percentage: { required: true },
				status: { required: true }
			},
			messages: {
				name: { required: "Please provide the main text of a repurchase" },
				percentage: { required: "Please enter percentage" },
				status: { required: "Please provide the status" },
			},
			errorClass: "help-block error",
			highlight: function (e) {
				$(e).closest(".form-group.row").addClass("has-error")
			},
			unhighlight: function (e) {
				$(e).closest(".form-group.row").removeClass("has-error")
			},
			submitHandler: function (e) {

				var form_btn = $('#addedit_reference_form').find('button[type="submit"]');
				var form_btn_old_msg = form_btn.html();
				var formdata = new FormData($('#addedit_reference_form')[0]);
				$["ajax"]({
					url: urljs + "admin/reference/save_add_data",
					type: "POST",
					dataType: "json",
					data: formdata,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
					},
					success: function (data) {
						closeajax();
						get_datas();
						if (data.status == 'success') {
							bootbox.hideAll();
							swal_alert(data.status, data.message, 'success', '');
						} else {
							show_toast('warning', data.message);
						}
					},
					error: function () {
						closeajax();
					}
				})
			}
		});
	}

</script>