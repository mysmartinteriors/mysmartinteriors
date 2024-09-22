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
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        }, 
        submitHandler: function(e){            
            var editorInput = CKEDITOR.instances['details'].getData();
            var form_btn = $("#prd_feature_form").find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            event.preventDefault();
            var formdata = new FormData($('#prd_feature_form')[0]);
            // console.log(formdata);

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
                    show_toast('success',data.message);
                    window.location.reload();
                }
                else{
                    show_toast('error',data.message);
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
            if(data.status=='success'){
                window.location.reload();
                show_toast('success',data.message)                        
            }else{
                swal('Warning',data.message,'warning')
            }
        },"json"); 
    });
}