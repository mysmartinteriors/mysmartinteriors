//product ratings
if($('#prd_user_ratings').length>0){
  get_prduser_ratings();
}

function get_prduser_ratings(){
	var id = $("#prd_user_ratings").find('[name="pid"]').val();
  	$.post(urljs+"products/get_ratings",{'id':id},function(data){
      $("#prd_rating_tabs").html(data.str);
      loginPopup();
      init_prd_rate();
    },"json");
}


if($('#prd_user_ratings').length>0){
  init_prd_rate();
  save_user_ratings();
}

function init_prd_rate(){
  $('.prdrateit').rateit();
}

function save_user_ratings(){ 
    $('#prd_user_ratings').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            reviewSummary:{required:true},
            ratingValue:{required:true}
        },
        messages:{
            reviewSummary: { required: "Please provide your review summary"},
            ratingValue: { required: "Please provide your rating"}
        },
        submitHandler: function(e){
          var value = $("#prd_user_ratings").find('[name="ratingValue"]').val();
          console.log(value);
          if(value=='' || value==undefined || value==0){
            swal('warning','Please select your ratings','warning');
          }else{
            event.preventDefault();
              var form_btn = $("#prd_user_ratings").find('button[type="submit"]');
              var form_btn_old_msg = form_btn.html();
              form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
              var formdata = new FormData($('#prd_user_ratings')[0]);
              $["ajax"]({
                  url: urljs+"products/save_ratings",
                  type: "POST",
                  dataType: "json",
                  data: formdata,
                  contentType: false,
                  cache: false,
                  processData: false,
                  beforeSend: function(){
                     ajaxloading("Saving... Please Wait...");
                  },
                  success: function(data){
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    closeajax();
                    if(data.result>0){
                    get_prduser_ratings();
                      $("#prd_user_ratings").find("input,select,textarea").val("");
                       show_toast('success','Your ratings has been saved')  
                    }
                    else{
                    get_prduser_ratings();
                        show_toast('warning',data.msg)  
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

  $(".notify-to-buy").on("click", function(){
    var notification = $(".notify-to-buy").attr('data-loading-text');
    $(".notify-to-buy").html(notification);
    show_toast('success', "We will notify you once this item is available again");
    $(".notify-to-buy").html("<i class='fa fa-bell'></i>");
  })