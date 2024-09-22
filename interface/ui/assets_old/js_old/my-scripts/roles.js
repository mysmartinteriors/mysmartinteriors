if ($("#rolesTbl").length > 0) {
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
	$.post(urljs + "admin/roles/get_datas", { "csrf_test_name": csrf_test_name, "page": page, 'filter_data': refine_filter_arr, }, function (data) {
		$("#rolesTbl").html(data.str);
		filters();
		closeajax();
		pagination();
		delete_role();
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

function delete_role() {
	$('.delrole').click(function () {
		console.log('delete');
		var mappingId = $(this).attr('data-id');
		swal({
			title: "You are about to delete the role",
			text: "It cannot be restored at a later time! Continue?",
			type: "warning",
			showCancelButton: true,
			cancelButtonClass: 'btn-secondary waves-effect',
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: true,
			closeOnCancel: true
		}, function (isConfirm) {
			if (isConfirm) {
				//ajaxloading('Please wait...');
				$.post(urljs + "admin/roles/del_role", { 'id': mappingId }, function (data) {
					//closeajax();
					if (data.status == 'fail') {
						show_toast('warning', 'You do not have permissions!');
					} else if (data.canDelete == 0) {
						show_toast("warning", "You cannot delete!<br>The role has been assigned to admin user(s)");
					} else {
						swal("Success!", "Record deleted", "success");
						get_data();
					}
				}, "json");
			}
		});
	});
}

if ($('#addedit_rolesform').length > 0) {
	save_roles();
	init_allcheckbox();
}

function init_allcheckbox() {
	$('.selectall').click(function () {
		var id = $(this).attr('data-id');
		if ($(this).prop("checked")) {
			$('[name=view_' + id + ']').each(function () {
				$(this).prop("checked", true);
			});
			$('[name=add_' + id + ']').each(function () {
				$(this).prop("checked", true);
			});
			$('[name=edit_' + id + ']').each(function () {
				$(this).prop("checked", true);
			});
			$('[name=delete_' + id + ']').each(function () {
				$(this).prop("checked", true);
			});
		} else {
			$('[name=view_' + id + ']').each(function () {
				$(this).prop("checked", false);
			});
			$('[name=add_' + id + ']').each(function () {
				$(this).prop("checked", false);
			});
			$('[name=edit_' + id + ']').each(function () {
				$(this).prop("checked", false);
			});
			$('[name=delete_' + id + ']').each(function () {
				$(this).prop("checked", false);
			});
		}
	});
}

function save_roles() {
	$('#addedit_rolesform').validate({
		rules: {
			name: {
				required: true
				// remote: {
				// 	url: urljs+"admin/roles/checkrolename",
				// 	type: "post",
				// 	data:{
				// 		edit: function() {
				// 			return $("[name=name]").attr("data-edit");
				// 		}
				// 	}
				// }					
			}
		},
		messages: {
			name: { required: "Please enter role name" }
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
			var admin = $('#addedit_rolesform').serializeArray();
			$.post(urljs + "admin/roles/saverole", admin, function (data) {
				closeajax();
				if (data.result == 'success') {
					swal({
						title: "Succees!",
						text: data.msg,
						type: "success",
						showCancelButton: false,
						cancelButtonClass: 'btn-secondary waves-effect',
						confirmButtonColor: "#3085d6",
						confirmButtonText: "Ok",
						cancelButtonText: "Close",
						closeOnConfirm: false,
						closeOnCancel: false
					}, function (isConfirm) {
						if (isConfirm) {
							window.location = urljs + "admin/roles";
						} else {
							window.location = urljs + "admin/roles";
						}
					});
				} else {
					swal("Failed!", "Please try later!!!", "warning");
				}
			}, "json");

		}
	});
}