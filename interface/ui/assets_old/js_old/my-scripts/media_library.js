//media upload
function uploadMedia(param){
    var mediaDialog = bootbox.dialog({
        title: 'Upload files here',
        size: 'medium',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
    });
    var boxId=$(param).attr('box-id');
    //console.log(boxId);
    mediaDialog.init(function(){
      setTimeout(function(){
          $.post(urljs+"admin/media/uploadBox",{'boxId':boxId},function(data){
              if(data.status=='success'){
                mediaDialog.find('.bootbox-body').html(data.msg);
                mediaDialog.find('.bootbox-body').addClass('uploadModal');
                initMediaUpload();
              }
              else{
                mediaDialog.find('.bootbox-body').html(data.msg);
              }
          },"json");
       },0);
    });
}

function initMediaUpload(){
  Dropzone.autoDiscover = false;
  $(".dropzone").dropzone({
   addRemoveLinks: true,
   acceptedFiles: ".jpeg,.jpg,.png",
   url:urljs+'admin/media/saveLibrary',
   success: function (file, response) {
    var data = jQuery.parseJSON(response);
    $('.close-toastr').closest('.toast').remove();
    if(data.status=='success'){
        show_toast('success',data.msg);
    }else{
        show_toast('warning',data.msg);
    }
    getLibraries();
    //$('.uploadModal').parent().parent().parent().parent().closest('modal-backdrop').remove();
    //$('.dz-hidden-input').remove();
    //$('.uploadModal').closest('.bootbox').hide();
   }
  });
}

function saveLibrary() {
  setTimeout(initMediaUpload,0);
      $("#media_upload_form").on('submit', (function (e) {
          e.preventDefault();
          var formdata = new FormData($('#media_upload_form')[0]);
            $["ajax"]({
                url: urljs+"admin/media/saveLibrary",
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
                    closeBootbox();
                    if(data.status=='success'){
                        show_toast('success',data.msg) 
                    }else{
                        show_toast('danger',data.msg)    
                    }
                    getLibraries();
                },
                error: function() {
                    closeajax();
                }
            })
      }));
}

function closeBootbox(){
  $(document).ready(function(){
    $(".bootbox").find('.bootbox-close-button').trigger('click');
  })

}

//media modal list
function openFileManager(param){
    var mediaDialog = bootbox.dialog({
        title: 'Select media file',
        size: 'large',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
    });
    var boxId=$(param).attr('box-id');
    console.log(boxId);
    mediaDialog.init(function(){
        setTimeout(function(){
          $.post(urljs+"admin/media/mediaLibary",{'boxId':boxId},function(data){
              if(data.result>0){ 
                 mediaDialog.find('.bootbox-body').html(data.str);
                 $('[data-toggle="popover"]').popover();
                 $('.modal-dialog').addClass('custom-width');
                 $('.modal-dialog').addClass('media-select-modal');
                 loadLibraryFiles();                 
              }
              else{
                swal('Warning','Error occured!','warning'); 
              }
          },"json");                
        }, 1000);
    });
}

function filterMedias() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        getLibraries(page);
    });
    $("#page_result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        getLibraries(page);
    });
}

function loadLibraryFiles(){
    assignValueToFilter();    
    var pageNumber = (GetURLParameter("perpage"));
    getLibraries(pageNumber);
}
if($('#libraryTbl').length>0){
  ajaxloading('Loading library...');
  loadLibraryFiles();

  $("#clearFilter").on("click",function(e){
      e.preventDefault();
      $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
      $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
      $(".filter").click();
  });
}

function getLibraries(page){
    var refine_filter_arr = [];
    var parameter = [];
    jQuery('.refine_filter option:selected,input.refine_filter').each(function () {
        refine_filter_arr.push({'type':$(this).attr("data-type"),'value':$(this).val()});
        parameter.push({'name':$(this).attr('data-type'),'value':$(this).val()});
    });
    var recursiveEncoded = $.param(parameter,true);
    //alert(recursiveEncoded);
    //window.location.hash = recursiveEncoded;
    libraryLoader('Loading media files...Please wait...');
    $.post(urljs+"admin/media/loadLibaries",{"csrf_test_name":csrf_test_name,"page":page,'filter_data':refine_filter_arr,},function(data){
        $("#mediaTbl").html(data.str);
        $("#libraryTbl").html(data.str);
        $("#page_result").html(data.pagination);
        $('[data-toggle="popover"]').popover();
        closeLibLoader();
        closeajax();
        filterMedias();
        pickup_button();
        delete_media_file();
        edit_media_file();
    },"json");
}



function selectMediaFile(param){
    $('.file-select.selected').removeClass('selected');
    $(param).addClass('selected');
    var fileId=$(param).attr('data-id');
    var fileName=$(param).attr('data-name');
    var filePath=$(param).attr('data-path');
    var fileType=$(param).attr('data-type');
    $('[name=fileId]').val(fileId);
    $('[name=filePath]').val(filePath+fileName);
    $('[name=fileType]').val(fileType);
}

function pickup_button(){    
    $('.pickFile').css('display','inline-block');
    $('.pickFile').unbind().click(function(){
        pickup_File();
    });
}
function pickup_File(){
    if($('[name=fileId]').val()=="" || $('[name=filePath]').val()==""){
      swal('Warning','Please select the file to attach!!!','warning');
    }else {
      var boxId=$('[name=boxId]').val();
      var fileId=$('[name=fileId]').val();
      var filePath=$('[name=filePath]').val();
      var fileType=$('[name=fileType]').val();
    $('button[box-id='+boxId+']').siblings('.attachPath').val(filePath);
    $('button[box-id='+boxId+']').siblings('.mediaImgPrev').css("display","inline-block");
      var imagePath=urljs+filePath;
    $('button[box-id='+boxId+']').siblings('.mediaImgPrev').attr("src",imagePath);
    $remover  = $('input[box-id='+boxId+']').parent().parent().find('.remove_current_image');
    $remover.removeClass('disabled');
    removeLibFile();
       bootbox.hideAll();
    }
}

function removeLibFile(){
    $remover = $('[data-action="remove_current_image"]');
    $remover.on('click', function() {
      $(this).parent().parent().find('label .image_preview').css('background-image', '');
      $(this).parent().parent().find('label .attachPath').val('');
      $(this).parent().parent().find('label .image_preview').html('');
      //$droptarget.removeClass('dropped');
     $(this).addClass('disabled');
      $('.image_title input').val('');
    });
    
}

function edit_media_file(){
  $('.editFile').click(function(){
    console.log('edit');
    var fileId=$(this).attr('data-id');
    $.post(urljs+"admin/media/editDetail",{'fileId':fileId},function(data){
      closeajax();
      if(data.status=='success'){ 
         bootbox.alert(data.msg);
         $('.modal-footer').css('display','none');
         save_media_details();
         init_clipboard();
      }
      else{
        swal('Failed',data.msg,'warning'); 
      }
    },"json");
  });
}

function init_clipboard(){
  $('button').tooltip({
    trigger: 'click',
    placement: 'bottom'
  });

  function setTooltip(btn, message) {
    $(btn).tooltip('hide')
      .attr('data-original-title', message)
      .tooltip('show');
  }

  function hideTooltip(btn) {
    setTimeout(function() {
      $(btn).tooltip('hide');
    }, 100);
  }
  var clipboard = new Clipboard('[data-clipboard]');
  clipboard.on('success', function(e) {
    setTooltip(e.trigger, 'Copied!');
    hideTooltip(e.trigger);
  });

  clipboard.on('error', function(e) {
    setTooltip(e.trigger, 'Failed!');
    hideTooltip(e.trigger);
  });
}

function save_media_details(){   
    $('#edit_media_form').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            fileName:{
              required:true,
              remote: {
                  url: urljs+"admin/media/check_exists",
                  type: "post",
                  data:{
                      edit: function() {                      
                          return $("[name=fileName]").attr("data-edit");
                      }                       
                  }
              }
            },
            fileTitle:{required:true},
            fileId:{required:true}
        },
        messages:{
            fileName :{required :"Please provide file name",remote:"File already exists with this name..."},
            fileTitle :{required :"Please provide file title"},
            fileId:{required:"File not found"}
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            // event.preventDefault();
            var formdata = new FormData($('#edit_media_form')[0]);
            $["ajax"]({
                url: urljs+"admin/media/saveDetails",
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
                  console.log(data);
                    closeajax();
                    getLibraries();
                    bootbox.hideAll();
                    if(data.result=='success'){
                        show_toast('success',data.msg) 
                    }
                    else{
                        show_toast('warning',data.msg)  
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}

function delete_media_file(){
  $('.deleteFile').click(function(){
    console.log('delete');
    var fileId=$(this).attr('data-id');
    swal({
      title: "You are about to delete this file",
      text: "It cannot be restored at a later time! Continue?",
      type: "warning",
      showCancelButton: true,
      cancelButtonClass: 'btn-secondary waves-effect',
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnConfirm: true,
      closeOnCancel: true
    },function (isConfirm) {
      if (isConfirm) {
        $.post(urljs+"admin/media/deleteMedia",{'fileId':fileId},function(data){
          closeajax();
          getLibraries();
          if(data.status=='success'){
            show_toast('success',data.msg);
          }
          else{
            show_toast('warning',data.msg); 
          }
        },"json");
      } 
    });
  });
}