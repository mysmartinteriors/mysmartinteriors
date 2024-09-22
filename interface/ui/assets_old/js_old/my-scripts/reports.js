$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();
    function cb(start, end) {
        //$('.reportrange input').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        minDate: '01/12/2019',
        dateLimit: {
            months: 2
        },
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);

    $('.rptsearch').click(function(){
      getreports();
    });

    $('.rptdownload').click(function(){
      download_report();
    });

      $('[name=module]').on('change',function(e){
        e.preventDefault();
        var o=$(this).val();
        if(o=='customers'){
          $("[name=status]").html('');
          $("[name=status]").append('<option value=>--All--</option>');
          $("[name=status]").append('<option value="1">Active</option>');
          $("[name=status]").append('<option value="0">In Active</option>');
          $("[name=dateType]").html('');
          $("[name=dateType]").append('<option value="createdDate">Created Date</option>');
          $("[name=dateType]").append('<option value="updatedDate">Updated Date</option>');
        }else if(o=='products'){
          $("[name=status]").html(''); 
          $("[name=status]").append('<option value=>--All--</option>');
          $("[name=status]").append('<option value="1">Active</option>');
          $("[name=status]").append('<option value="0">In Active</option>');
          $("[name=dateType]").html('');
          $("[name=dateType]").append('<option value="createdDate">Created Date</option>');
          $("[name=dateType]").append('<option value="updatedDate">Updated Date</option>');
        }else if(o=='prd_orders'){
          $("[name=status]").html(''); 
          $("[name=status]").append('<option value=>--All--</option>');
          $("[name=dateType]").html('');
          $("[name=dateType]").append('<option value="createdDate">Order Date</option>');
        }else if(o=='orders'){
          $("[name=status]").html(''); 
          $("[name=status]").append('<option value=>--All--</option>');
          $("[name=status]").append('<option value="-1">Cancelled</option>');
          $("[name=status]").append('<option value="0">Pending</option>');
          $("[name=status]").append('<option value="1">Payment Due</option>');
          $("[name=status]").append('<option value="2">Dispatched</option>');
          $("[name=status]").append('<option value="3">Completed & Billed</option>');
          $("[name=dateType]").html('');
          $("[name=dateType]").append('<option value="createdDate">Order Date</option>');
          $("[name=dateType]").append('<option value="updatedDate">Last Updated</option>');
        }

      });

    function getreports(){
      $('#report_form').validate({
        errorClass: 'error',
        validClass: 'valid',
        submitHandler: function(){
          if($("[name=module]").val() == ""){
            swal('Warning!','Please select the module','warning');
          }
          else {
            ajaxloading('Fetching data...<br>Please wait...');
            var admin=$('#report_form').serializeArray();
            $.post(urljs+"admin/reports/getreports",admin,function(data){
              closeajax();
              $(".display-report").show();
              $("#reportsTbl").html(data.str);
              $('[data-toggle="tooltip"]').tooltip(); 
              if(data.result=='success'){
                $(".rptdownload").show();
              }else{
                $(".rptdownload").hide();
              }
            },"json");
          }
        }
      });
    }


    function download_report(){
      event.preventDefault();
      if($("[name=module]").val() == ""){
        swal('Warning!','Please select the module','warning');
      }else if($("[name=from]").val() == "" || $("[name=to]").val() == ""){
        swal('Warning!','Please select the date range','warning');
      }
      else {
        ajaxloading('Preparing to download...<br>Please wait...');
        var admin=$('#report_form').serializeArray();
        $.post(urljs+"admin/reports/download",admin,function(data){
          closeajax();
          if(data.result=='success'){
            swal({
            title: "Selected report is ready for download!",
            type: "success",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Download",
            cancelButtonText: "Close",
            closeOnConfirm: false,
            closeOnCancel: false
            }).then (function (isConfirm) {   
            if (isConfirm.value) { 
              window.location.href = urljs+data.filepath;
            }
            });
          }else{
            swal("Failed!", "Data not found", "warning");
          }
        },"json");
      }
    }

});