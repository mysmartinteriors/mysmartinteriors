if ($("#usersTbl").length > 0) {
	get_data(1);
}

function filters() {
	$(".filter").on("click", function (e) {
		e.preventDefault();
		var page = 1;
		$("#pagenumber").val(page);
		get_data(page);
	});
	$("#clearFilter").on("click", function (e) {
		e.preventDefault();
		$('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
		$('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
		$(".filter").click();
	});
}

function get_data(page) {
	ajaxloading('Loading data...');
	var refine_filter_arr = [];
	var parameter = [];
	jQuery('.refine_filter option:selected,input.refine_filter').each(function () {
		refine_filter_arr.push({ 'type': $(this).attr("data-type"), 'value': $(this).val() });
		parameter.push({ 'name': $(this).attr('data-type'), 'value': $(this).val() });
	});
	var recursiveEncoded = $.param(parameter, true);
	//alert(recursiveEncoded);
	//window.location.hash = recursiveEncoded;
	$.post(urljs + "admin/adminusers/get_datas", { "csrf_test_name": csrf_test_name, "page": page, 'filter_data': refine_filter_arr, }, function (data) {
		$("#usersTbl").html(data.str);
		filters();
		closeajax();
		pagination();
		delete_user();
	}, "json");
}

function pagination() {
	$("#page_result").on("click", ".pagination a", function (e) {
		e.preventDefault();
		var page = $(this).attr("data-page");
		$("#pagenumber").val(page);
		//window.location = "#"+page;
		get_data(page);
	});
}

function delete_user() {
	$('.delData').click(function () {
		var mappingId = $(this).attr('data-id');
		swal({
			title: "You are about to delete the user",
			text: "It cannot be restored at a later time! Continue?",
			type: "warning",
			showCancelButton: true,
			cancelButtonClass: 'btn-secondary waves-effect',
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				//ajaxloading('Please wait...');
				$.post(urljs + "admin/adminusers/deluser", { 'id': mappingId }, function (data) {
					//closeajax();
					if ((data.result == -1) || (data.status == 'fail')) {
						swal("Sorry!", "You don\'t have permission!!!", "warning");
					} else {
						if (data.canDelete === 0) {
							swal("Failed!", "Record linked with other datas!!!", "warning");
						} else {
							swal("Success!", "Record deleted", "success");
							get_data();
						}
					}
				}, "json");
			}
		});
	});
}

if ($('#addedit_userform').length > 0) {
	save_user_data();
}

function save_user_data() {
	$('#addedit_userform').validate({
		rules: {
			firstName: { required: true },
			lastName: { required: true },
			username: {
				required: true,
			},
			email: {
				required: true,
				email: true,
			},
			password: { required: true, minlength: 6 },
			cpassword: { required: true, minlength: 6, equalTo: "[name=password]" },
			rid: { required: true }
		},
		messages: {
			firstName: { required: "Please enter first name" },
			lastName: { required: "Please enter last name" },
			username: { required: "Please enter username" },
			email: { required: "Please enter email address", email: "Please enter a valid email"},
			password: { required: "Please enter password", minlength: "Minimum length is 6" },
			cpassword: { required: "Please confirm the Password", minlength: "Password should contain atleast 6 characters!", equalTo: "Password mismatch!" },
			rid: { required: "Please select the role" }
		},
		errorClass: "help-block error",
		highlight: function (e) {
			$(e).closest(".form-group.row").addClass("has-error")
		},
		unhighlight: function (e) {
			$(e).closest(".form-group.row").removeClass("has-error")
		},
		submitHandler: function () {
			ajaxloading('Saving, please wait...');
			var admin = $('#addedit_userform').serializeArray();
			$.post(urljs + "admin/adminusers/saveuser", admin, function (data) {
				closeajax();
				if (data.status == 'success') {
					swal_alert('Success', data.message, 'success', 'admin/adminusers')
				}
				else {
					swal('Failed', data.message, 'warning');
				}
			}, "json");

		}
	});
}