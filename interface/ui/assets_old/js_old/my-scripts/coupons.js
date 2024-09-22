//customers
function filters() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        get_datas(page);
    });
    $("#clearFilter").on("click",function(e){
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    });
    $("#result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        get_datas(page);
    });
}

if($("#couponsTbl").length>0){
    assignValueToFilter();    
    var pageNumber = (GetURLParameter("perpage"));
    get_datas(pageNumber);
}
       
function get_datas(page){
    var url="admin/coupons/get_datas";
    ajax_filter(url, page, render_data,false) 
}

function render_data(datas) {
    $("#couponsTbl").html(datas.str);
    filters();
    update_status();
    delete_data();
    add_data();
}


function add_data(){
    $(".btn-addData").on("click",function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        if(id!='' && id>0){
            var modal_title='Edit coupons data';
        } else{
            var modal_title='Add new coupon';
        } 
        var mediaDialog = bootbox.dialog({
            title: modal_title,
            size: 'medium',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        //console.log(boxId);
        mediaDialog.init(function(){
          setTimeout(function(){
              $.post(urljs+"admin/coupons/add_new",{'id':id},function(data){
                  if(data.status=='success'){
                    mediaDialog.find('.bootbox-body').html(data.msg);
                    init_timepickers();
                    initImageUpload();
                    save_add_data();
                  }
                  else{
                    mediaDialog.find('.bootbox-body').html(data.msg);
                  }
              },"json");
           },0);
        });
    });
}

function init_timepickers(){
    $(".valid_from").datetimepicker({
        format: 'dd-mm-yyyy hh:ii:ss'
    });
    $(".valid_to").datetimepicker({
        format: 'dd-mm-yyyy hh:ii:ss'
    });
}

function initImageUpload(){
    var logo_exist=$("#imageinput").attr("data-logo");
    var logo_url=$("#imageinput").attr("data-url");
    if(logo_exist!=""){
        var imageFile=logo_url+logo_exist;
    }
    var drEvent= $('.dropify').dropify({
        defaultFile:imageFile,
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

function save_add_data(){
    $('#addedit_slider_form').validate({
        rules: {
            coupon_code:{
                required:true,
                remote: {
                    url: urljs+"admin/coupons/check_add_exists",
                    type: "post",
                    data:{
                        edit: function() {                      
                            return $("[name=coupon_code]").attr("data-edit");
                        }                       
                    }
                }
            },
            price_type:{required:true},
            coupon_value:{required:true,number:true},
            min_purchase:{number:true},
            valid_from:{required:true},
            valid_to:{required:true},
            applicable_to:{required:true},
            status:{required:true}
        },
        messages:{
            coupon_code :{required :"Please provide coupon code",remote:"This coupon already exists!"},
            price_type:{required:"Please select the value type"},
            coupon_value:{required:"Please provide the value"},
            min_purchase:{number:"Please enter only integer value"},
            valid_from:{required:"Please select the date"},
            valid_to:{required:"Please select the date"},
            applicable_to:{required:"Please select any value"},
            status:{required:"Please select the status"}
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){

            var form_btn = $('#addedit_slider_form').find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            //e.preventDefault();
            var formdata = new FormData($('#addedit_slider_form')[0]);
            $["ajax"]({
                url: urljs+"admin/coupons/save_add_data",
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
                    closeajax();
                    get_datas();
                    if(data.result>0){
                        $('#addedit_colctn_form').find('input').val('');
                        form_btn.prop('disabled', false).html(form_btn_old_msg);
                        bootbox.hideAll();
                        show_toast('success',data.msg);
                    }
                    else{
                        show_toast('warning',data.msg);
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}

function update_status() {
    $('.statusData').unbind().click(function() {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        //ajaxloading('Please wait...');
        $.post(urljs+'admin/coupons/update_status',{'id':id,'status':status},function(data){
            //closeajax();
            if(data.status=='success'){  
                get_datas(); 
                show_toast('success',data.msg)                        
            }else{
                swal('Warning',data.msg,'warning')
            }
        },"json");  
    });
}

function delete_data() {
    $('.delData').unbind().click(function() {
        var mappingId = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this slider!" ,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        },function (isConfirm) {
          if (isConfirm) {
            ajaxloading('Please wait...');
            $.post(urljs+'admin/coupons/delete_coupon',{'id':mappingId},function(data){
                closeajax();
                if(data.status=='success'){  
                    get_datas(); 
                    show_toast('success',data.msg)                        
                }else{
                    swal('Warning',data.msg,'warning')
                }
            },"json");
          }            
        });  
    });
}
