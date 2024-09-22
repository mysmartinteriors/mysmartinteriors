jQuery(document).ready(function () {
    // menu items
    //var arrayjson =[{"icon":"","href":"","text":"Hai","id":"1","children":[{"icon":"","href":"","text":"Sub-hai","id":"2"}]},{"icon":"","href":"","text":"Sample Page","id":"3"},{"icon":"","href":"","text":"aaa","id":"4"}];

    // icon picker options
    var iconPickerOptions = {
        searchText: "Buscar...",
        labelHeader: "{0}/{1}"
    };
    // sortable list options
    var sortableListOptions = {
        placeholderCss: {
            'background-color': "#cccccc"
        }
    };

    var editor = new MenuEditor('load_menu_here', {
        listOptions: sortableListOptions,
        iconPicker: iconPickerOptions
    });
    editor.setForm($('#frmEdit'));

    //editor.setData(arrayjson);

    editor.setUpdateButton($('#btnUpdate'));
    $('#btnReload').on('click', function () {
        editor.setData(arrayjson);
    });

    if ($("#load_menu_here").length > 0) {
        get_menus();
    }

    $('#btnOutput').on('click', function () {
        var str = editor.getString();
        //alert(str);
        ajaxloading("Updating... Please Wait...");
        $.post(urljs + "admin/categories/save_categories", { 'str': str }, function (data) {
            closeajax();
            if (data.status == "success") {
                editor.setData(data.result.menus);
                show_toast('success', 'Categories has been updated');
            } else {
                show_toast('warning', 'Unable to update, please try later...');
            }
        }, "json");

    });

    function get_menus() {
        $["ajax"]({
            url: urljs + "admin/categories/get_datas",
            type: "POST",
            dataType: "json",
            data: '',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                ajaxloading("Getting datas... Please Wait...");
            },
            success: function (data) {
                closeajax();
                if (data.status == "success") {
                    editor.setData(data.menus);
                    category_operations();
                    save_addData();
                    console.log("HELLO");
                } else {
                    show_toast('warning', 'Unable to fetch categories...');
                }
            },
            error: function () {
                closeajax();
            }
        })
    }

    function category_operations() {
        $(".btnRemove").unbind().click(function (e) {
            var id = $(this).closest('li').attr('data-id');
            swal({
                title: "Are you sure?",
                text: "You cannot revert back at later time!",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    ajaxloading("Processing request... Please Wait...");
                    $.post(urljs + "admin/categories/delete_cat", { 'id': id }, function (data) {
                        closeajax();
                        if (data.status == "success") {
                            get_menus();
                            show_toast('success', data.message);
                        } else {
                            show_toast('warning', data.message);
                        }
                    }, "json");
                } else {
                    //swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        });

        $(".btnEdit").unbind().click(function (e) {
            var id = $(this).closest('li').attr('data-id');
            ajaxloading("Please Wait...");
            $.post(urljs + "admin/categories/edit_cat", { 'id': id }, function (data) {
                closeajax();
                if (data.status == "success") {
                    var dialog = bootbox.dialog({
                        title: 'Category settings',
                        message: data.str,
                        centerVertical: true,
                        closeButton: true
                    });
                    initImageUpload();
                    save_editData();
                    // get_menus();
                } else {
                    show_toast('warning', 'Unable to fetch category');
                }
            }, "json");
            //  swal("Deleted!", "Your imaginary file has been deleted.", "success");

        });
    }

    function initImageUpload() {
        var logo_exist = $("#imageinput").attr("data-logo");
        var logo_url = $("#imageinput").attr("data-url");
        if (logo_exist != "") {
            var imageFile = logo_url;
        }
        var drEvent = $('.dropify').dropify({
            defaultFile: imageFile,
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (25M max).'
            }
        });
        $('.dropify-filename-inner').html(logo_exist);
    }

    function save_addData() {
        console.log("HERE");
        $('#add_cat_form').validate({
            rules: {
                cat_name: {
                    required: true,
                    remote: {
                        url: urljs + "admin/categories/check_add_exists",
                        type: "post",
                        data: {
                            edit: function () {
                                return $("[name=cat_name]").attr("data-edit");
                            }
                        }
                    }
                }
            },
            messages: {
                cat_name: { required: "Please provide category name" }
            },
            errorClass: "help-block error",
            highlight: function (e) {
                $(e).closest(".form-group.row").addClass("has-error")
            },
            unhighlight: function (e) {
                $(e).closest(".form-group.row").removeClass("has-error")
            },
            submitHandler: function (e) {

                var form_btn = $('#add_cat_form').find('button[type="submit"]');
                var form_btn_old_msg = form_btn.html();

                //e.preventDefault();
                var formdata = new FormData($('#add_cat_form')[0]);
                $["ajax"]({
                    url: urljs + "admin/categories/save_update",
                    type: "POST",
                    dataType: "json",
                    data: formdata,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        button_load("#add_cat_form", 'Please wait...', '');
                        // form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                    },
                    success: function (data) {
                        end_button_load("#add_cat_form", '');
                        closeajax();
                        get_menus();
                        if (data.result == 'success') {
                            $('#add_cat_form').find('input').val('');
                            form_btn.prop('disabled', false).html(form_btn_old_msg);
                            bootbox.hideAll();
                            show_toast('success', data.msg);
                        }
                        else {
                            show_toast('warning', data.msg);
                        }
                    },
                    error: function () {
                        closeajax();
                    }
                })
            }
        });
    }

    function save_editData() {
        $('#edit_cat_form').validate({
            rules: {
                text: {
                    required: true,
                    remote: {
                        url: urljs + "admin/categories/check_exists",
                        type: "post",
                        data: {
                            edit: function () {
                                return $("[name=text]").attr("data-edit");
                            }
                        }
                    }
                }
            },
            messages: {
                text: { required: "Please provide category name", remote: "Category already exists!" }
            },
            errorClass: "help-block error",
            highlight: function (e) {
                $(e).closest(".form-group.row").addClass("has-error")
            },
            unhighlight: function (e) {
                $(e).closest(".form-group.row").removeClass("has-error")
            },
            submitHandler: function (e) {
                var form_btn = $('#edit_cat_form').find('button[type="submit"]');
                var form_btn_old_msg = form_btn.html();
                // e.preventDefault();
                var formdata = new FormData($('#edit_cat_form')[0]);
                $["ajax"]({
                    url: urljs + "admin/categories/save_update",
                    type: "POST",
                    dataType: "json",
                    data: formdata,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        // form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                        button_load("#edit_cat_form", 'Please wait...', '');
                    },
                    success: function (data) {
                        end_button_load("#edit_cat_form", '');
                        closeajax();
                        bootbox.hideAll();
                        console.log(data);
                        if (data.result == 'success') {
                            // form_btn.prop('disabled', false).html(form_btn_old_msg);
                            show_toast('success', data.msg);
                            get_menus();
                        }
                        else {
                            show_toast('warning', data.msg);
                        }
                    },
                    error: function () {
                        closeajax();
                    }
                })
            }
        });
    }

});

function button_load(form, msg, btn_elem) {
    if (btn_elem == '') {
        var btn = $(form).find('button[type=submit]');
    } else {
        var btn = $(btn_elem);
    }
    var old_msg = btn.html();
    btn.attr('data-html', old_msg);
    btn.attr('disabled', 'true');
    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + msg);
}

function end_button_load(form, btn_elem) {
    if (btn_elem == '') {
        var btn = $(form).find('button[type=submit]');
    } else {
        var btn = $(btn_elem);
    }
    var old_msg = btn.attr('data-html');
    btn.removeAttr('data-html');
    btn.removeAttr('disabled');
    btn.html(old_msg);
}