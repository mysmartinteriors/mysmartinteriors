if($("#featureprdTbl").length>0){
    assignValueToFilter();
    var pageNumber = (GetURLParameter("perpage"));
    getFeaturedProducts(pageNumber);
}

function filterFeatureProducts() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        getFeaturedProducts(page);
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
        $("#pagenumber").val(page);
        getFeaturedProducts(page);
    });
}

function getFeaturedProducts(page){
    ajaxloading('Loading data...Please wait...');
    var url="admin/products/get_FeatureProducts";
    ajax_filter(url, page, renderFeaturePrds,false) 
}

function renderFeaturePrds(datas) {
    $("#featureprdTbl").html(datas.str);
    filterFeatureProducts();
    deleteFeaturePrd();
    saveFeaturePrd();
    featurePrdStatus();
}

function saveFeaturePrd(){ 
    $('#add_featureprd_form').validate({  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            event.preventDefault();
            if($("[name=productCode]").val()==''){
                swal('Cannot Add!','Please enter product code','warning');
            }else{
            var formdata = new FormData($('#add_featureprd_form')[0]);
            $["ajax"]({
                url: urljs+"admin/products/save_Featureproduct",
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
                    // console.log(data);
                    closeajax();
                    if(data.result=='success'){
                        getFeaturedProducts();
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
        }
    });
}

function deleteFeaturePrd() {
    $('.delData').unbind().click(function() {
        var mappingId = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You wanted to remove this product from featured list?" ,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        },function (isConfirm) {
          if (isConfirm) {
            ajaxloading('Please wait...');
            $.post(urljs+'admin/products/removeFeaturePrd',{'id':mappingId},function(data){
                closeajax();
                if(data.status=='success'){   
                    show_toast('success',data.msg)
                    getFeaturedProducts();                       
                }else{
                    show_toast('warning',data.msg)
                }
            },"json");
          }            
        });  
    });
}

function featurePrdStatus(){
    $('.statusData').unbind().click(function() {
        var mappingId = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        $.post(urljs+'admin/products/statusFeaturePrd',{'id':mappingId,'status':status},function(data){
            closeajax();
            if(data.result=='success'){   
                show_toast('success',data.msg)
                getFeaturedProducts();
            }else{
                show_toast('warning',data.msg)
            }
        },"json");
     });
}
