//products
function filterProducts(){
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
		var pageNumber = (GetURLParameter("page"));
        getProducts(pageNumber);
    });
    $("#clearFilter").on("click",function(e){
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    });
    $("#page_result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
		setCustomParameter("page",page);
        getProducts(page);
    });
}

if($("#productsTbl").length>0){
    assignValueToFilter();    
    var pageNumber = (GetURLParameter("page"));
    console.log(pageNumber);
    getProducts(pageNumber);
}
       
function getProducts(page=1){
    ajaxloading('Loading...');
    var url="admin/products/get_products";
    ajax_filter(url, page, renderProducts,false)  
}

function renderProducts(datas) {
    $("#productsTbl").html(datas.str);
    deleteProduct();
    filterProducts();
}

function deleteProduct() {
    $('.delData').unbind().click(function() {
        var mappingId = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this product" ,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        },function (isConfirm) {
          if (isConfirm) {
            ajaxloading('Please wait...');
            $.post(urljs+'admin/products/deleteProduct',{'id':mappingId},function(data){
                closeajax();
                if(data.status=='success'){  
                    getProducts(); 
                    show_toast('success',data.message)                        
                }else{
                    swal('Warning',data.message,'warning')
                }
            },"json");
          }            
        });  
    });
}

 if($("#addedit_product_form").length>0){
    init_tags();
    save_product();
    init_color_picker();
    $( 'textarea.textEditor').ckeditor();
    $(".select2_cat").select2({
        placeholder: "Select a category",
        allowClear: false
    });

    $(".select2_cat").change(function(){
        $('#categoryId-error').remove();
        var element = $(this).find('option:selected'); 
        var parent = element.attr("data-parent");
        $('[name=parentId]').val(parent); 
    });
}


function init_color_picker(){
    $('.minicolors').each(function(){
        $(this).minicolors({
            theme:"bootstrap",
            control:$(this).attr("data-control")||"hue",
            format: $(this).attr('data-format') || 'hex',
            opacity:$(this).attr("data-opacity"),
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            position: $(this).attr('data-position') || 'bottom left',
        });
    });
}

function init_tags(){
    $('.tagsinput').tagsinput({
        tagClass: 'label label-primary'
    });
    $('.tagsinput.form-control-solid').siblings('.bootstrap-tagsinput').addClass('form-control-solid');
}

function update_prd_url(){
    var prdName=$('[name=productName]').val();
    var prdCode=$('[name=productCode]').val();
    prdURL=prdName.replace(/[\. ,:-]+/g, "-");
    $('[name=productURL]').val(prdURL+'-'+prdCode);
}

function save_product(){ 
    $('#addedit_product_form').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            categoryId:{required:true},
            productCode:{
                required:true,
                remote: {
                    url: urljs+"admin/products/check_exists",
                    type: "post",
                    data:{ 
                        edit: function() {                      
                            return $("[name=productCode]").attr("data-edit");
                        }                       
                    }
                }
            },
            productName:{required:true},
            productPrice:{required:true,number:true},
            CGST:{required:true,number:true},
            SGST:{required:true,number:true},
            description:{required:true},
            productURL:{required:true},
            color_code:{required:true},
            color_name:{required:true},
            is_primary:{required:true},
            in_stock:{required:true},
            status:{required:true},
            productImage:{required: true}
        },
        messages:{
            categoryId:{required:"Product select the category"},
            productCode :{required :"Please provide product code",remote:"Product exists with this code!"},
            productName :{required :"Please provide product name"},
            productPrice:{required:"Please provide product price",number:"Please provide valid price"},
            CGST:{required:"Please provide the central tax in %",number:"Please provide valid tax price"},
            SGST:{required:"Please provide the state tax in %",number:"Please provide valid tax price"},
            description:{required:"Please provide product description"},
            productURL:{required:"Product page URL is required"},
            color_code:{required:"Provide product color code"},
            color_name:{required:"Provide product color name"},
            is_primary:{required:"Select display type"},
            in_stock:{required:"Select stock status"},
            status:{required:"Select status"},
            productImage: {required: 'Please select / upload the image to continue'}
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            //event.preventDefault();
            var editorInput = CKEDITOR.instances['description'].getData();
            var formdata = new FormData($('#addedit_product_form')[0]);
            formdata.append('description',editorInput);
            $["ajax"]({
                url: urljs+"admin/products/save_product",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false, 
                beforeSend: function() {
                   ajaxloading("Saving... Please Wait...");
                },
                success: function(data) {
                    closeajax();
                    console.log(data);
                    if(data.status=='success'){
                        page = 1;
						if(page!=''){
							var redirect_uri='admin/products#page='+page;
						}else{
							var redirect_uri='admin/products';
						}
                        swal_alert('Success',data.message,'success',redirect_uri);
                    }else{
                        console.log(data);
                        return;
                        show_toast('warning',data.message);
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}

function getCustomParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
}

function setCustomParameter(sParam,value) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('#'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            sParameterName[1] =value;
        }
    }
}

function uploadMdl(){
    var module='products';
    $.post(urljs+"admin/excelimport/importModal",{'module':module},function(data){
        if(data.status=='success'){
            var dialog = bootbox.dialog({
                title: 'Upload your products data file here',
                message: data.msg,
                closeButton: true
            });
            init_excel_dropify();
            import_data();
        }else{
            swal('Warning','Error occured!','warning'); 
        }
    },"json");
}


function import_data(){
    $('#import_prd_form').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            file:{required:true}
        },
        messages:{
            file:{required:"Please select the file!"}
        },  
        submitHandler: function(e){
            var form_btn = $("#import_prd_form").find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            event.preventDefault();
            var formdata = new FormData($('#import_prd_form')[0]);
            $["ajax"]({
            url: urljs+"admin/excelimport/products",
            type: "POST",
            dataType: "json",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                excel_data_timer();
            },
            success: function(data) {
                form_btn.prop('disabled', false).html(form_btn_old_msg);
                //closeajax();
                if(data.status=='success'){
                    bootbox.hideAll();
                    getProducts();
                    swal('Success',data.msg,'success');
                }
                else{
                    $('.excel_progress').html('');
                    delete_timer();
                    swal('Error',data.msg,'warning');
                }
            },
            error: function() {}
        })
        }
    });
}

//product details
if($('#prd_feature_form').length>0){
    init_editor();
    save_feature_data();
    init_rating_table();
    save_images_data();
    rmProductImage();
}

function init_editor(){
    var editor = $( 'textarea.textEditor').ckeditor();
} 

function init_rating_table(){
    var $selectAll = $('#selectAll'); // main checkbox inside table thead
    var $table = $('.table'); // table selector 
    var $tdCheckbox = $table.find('tbody input:checkbox'); // checboxes inside table body
    var $tdCheckboxChecked = []; // checked checbox arr

    //Select or deselect all checkboxes on main checkbox change
    $selectAll.on('click', function () {
        $tdCheckbox.prop('checked', this.checked);
    });

    //Switch main checkbox state to checked when all checkboxes inside tbody tag is checked
    $tdCheckbox.on('change', function(){
        $tdCheckboxChecked = $table.find('tbody input:checkbox:checked');//Collect all checked checkboxes from tbody tag
    //if length of already checked checkboxes inside tbody tag is the same as all tbody checkboxes length, then set property of main checkbox to "true", else set to "false"
        $selectAll.prop('checked', ($tdCheckboxChecked.length == $tdCheckbox.length));
    })
}

function save_feature_data(){
    $('#prd_feature_form').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            details:{required:true}
        },
        messages:{
            details:{required:"Please write something to update!"}
        },  
        submitHandler: function(e){            
            var editorInput = CKEDITOR.instances['details'].getData();
            var form_btn = $("#prd_feature_form").find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            event.preventDefault();
            var formdata = new FormData($('#prd_feature_form')[0]);
            formdata.append('details',editorInput);
            $["ajax"]({
            url: urljs+"admin/products/save_details_data",
            type: "POST",
            dataType: "json",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
            },
            success: function(data) {
                form_btn.prop('disabled', false).html(form_btn_old_msg);
                //closeajax();
                if(data.result=='success'){
                    $("#prd_feature_form").find('[name=fId]').val(data.dataId);
                    show_toast('success',data.msg);
                }
                else{
                    show_toast('error',data.msg);
                }
            },
            error: function() {}
        })
        }
    });
}

function save_images_data(){
    $('#prd_image_form').validate({
        errorClass: 'error',
        validClass: 'valid', 
        submitHandler: function(e){
            var form_btn = $("#prd_image_form").find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            event.preventDefault();
            var formdata = new FormData($('#prd_image_form')[0]);
            $["ajax"]({
            url: urljs+"admin/products/save_images_data",
            type: "POST",
            dataType: "json",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
            },
            success: function(data) {
                form_btn.prop('disabled', false).html(form_btn_old_msg);
                //closeajax();
                if(data.status=='success'){
                    // show_toast('success',data.message);
                    swal.alert('Success', data.message, 'success')
                    window.location.reload();
                }
                else{
                    // show_toast('error',data.message);
                    swal.alert('Error', data.message, 'error')
                }
            },
            error: function() {}
        })
        }
    });
}

function rmProductImage() {
    $('.rmPrdImage').unbind().click(function() {
        var mappingId = $(this).attr('data-id');
        var pId = $("#prd_image_form").find('[name="pId"]').val();
        $.post(urljs+'admin/products/rm_prd_image',{'id':mappingId,'pId':pId},function(data){
            //closeajax();
            if(data.result>0){
                window.location.reload();
                show_toast('success',data.msg)                        
            }else{
                swal('Warning',data.msg,'warning')
            }
        },"json"); 
    });
}

function addCustomField() {
    var count = $(".appendRows:last").attr('data-count');
    // button_load('', 'Adding...', '.btn-addCustomField');
    $.post(urljs + "admin/products/add_custom_field", { 'count': count }, function (data) {
        // end_button_load('', '.btn-addCustomField');
        if (data.result == 'success') {
            var del_html = '<label class="form-control-label">Remove</label><br>';
            del_html += '<span class="text-danger btn-delField" onclick="delCustomField(this);" title="Remove"><i class="fa fa-times"></i></span>';
            $(".appendRows:last").find(".action-block").html(del_html);
            $(".appendRows:last").after(data.msg);
        } else {
            show_toast('warning', data.msg);
        }
    }, "json");
}

function delCustomField(param) {
    $(param).parent().parent().parent().remove();
    var card_length = $('#custom_field_sec').find('.appendRows').length;
    console.log(card_length);
    if (card_length <= 0 || card_length == undefined) {
        $.post(urljs + "verifications/add_custom_field", { 'count': card_length }, function (data) {
            if (data.result == 'success') {
                $("#custom_field_sec .card-box").html(data.msg);
            } else {
                show_toast('warning', data.msg);
            }
        }, "json");
    }
}