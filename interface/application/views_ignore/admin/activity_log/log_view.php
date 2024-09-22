<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Activitylog</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Activitylog</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">

                <!-- end row -->
                <div class="row">
                    <div class="col-12">                       
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">System and user activity logs</div>
                                <!--<div class="btn btn-pink btn-labeled btn-labeled-left btn-icon exportData">
                                    <span class="btn-label"><i class="la la-upload"></i></span>Export
                                </div>-->
                            </div>
                                <div class="ibox-body adv_filter tblbox">
                                        <div class="row mb-4">
                                            <div class="col-sm-10 col-11">
                                                <div class="input-group  pull-left" id="adv-search">
                                                    <input type="text" class="form-control refine_filter clearAbleFilt" id="product" data-type="search" placeholder="Search for any records..." />
                                                    <input type="hidden" name="page" id="pagenumber" data-type="page" data-id="page"  class="refine_filter" />
                                                    <div class="input-group-btn">
                                                        <div class="btn-group" role="group">
                                                            <div class="dropdown dropdown-lg">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                                                                <div class="dropdown-menu">
                                                                    <form class="form-horizontal" role="form">
                                                                        <div class="row">
                                                                            <div class="col-6 form-group">
                                                                                <label for="contain">Search by customer</label>
                                                                                <input class="form-control refine_filter clearAbleFilt" data-type="user" id="user" placeholder="Enter customer email...">
                                                                            </div>
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Log Type</label>
                                                                                <select class="form-control refine_filter clearAbleFilt" data-type="logType" id="logType">
                                                                                    <option data-type="logType" value="">--All logs--</option>
                                                                                    <option data-type="logType" value="admin">Admin logs</option>
                                                                                    <option data-type="logType" value="customer">Customer logs</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Filter by module</label>
                                                                                    <select class="form-control refine_filter clearAbleFilt" data-type="module" id="module">
                                                                                        <option value="" selected data-type="module">--All modules--</option>
                                                                                    <?php 
                                                                                        if(!empty($moduleQ)){
                                                                                            foreach ($moduleQ as $row) {
                                                                                    ?>
                                                                                        <option value="<?php echo $row['module'] ?>" data-type="module"><?php echo ucfirst($row['module']) ?></option>
                                                                                    <?php
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Filter by action</label>
                                                                                    <select class="form-control refine_filter clearAbleFilt" data-type="action" id="action">
                                                                                        <option value="" selected data-type="action">--All actions--</option>
                                                                                    <?php 
                                                                                        if(!empty($actionQ)){
                                                                                            foreach ($actionQ as $row) {
                                                                                    ?>
                                                                                        <option value="<?php echo $row['action'] ?>" data-type="action"><?php echo ucfirst($row['action']) ?></option>
                                                                                    <?php
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            </div>
                                                                        <div class="row">
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Sort by</label>
                                                                                <select class="form-control refine_filter" data-type="sortBy" id="sortBy">
                                                                                    <option data-type="sortBy" value="">Newest First</option>
                                                                                    <option data-type="sortBy" value="createdAsc">Oldest First</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Records per page</label>
                                                                                <select class="form-control refine_filter float-right" data-type="perpage" id="perpage">
                                                                                    <option data-type="perpage" value="10">10</option>
                                                                                    <option data-type="perpage" value="25">25</option>
                                                                                    <option data-type="perpage" value="50">50</option>
                                                                                    <option data-type="perpage" value="100">100</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear-btn-holder text-right mb-2">
                                                                            <a class="" id="clearFilter"><i class="ti-close"></i> Clear Search</a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-primary filter"><span class="fa fa-search" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-1">
                                                <a class="fullscreen-link float-right"><i class="ti-fullscreen"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="activityTbl"></div>
                                            </div>
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                        </div> <!-- end row -->

            </div>
            <!-- END PAGE CONTENT-->
            <?php echo $footer ?>
        </div>
    </div>
    <?php echo $commonJs ?>
<script>

if($("#activityTbl").length>0){ 
    getActivityLogs(1);
}
function activitylogFilter(){
    $(".filter").on("click",function(e){
        e.preventDefault();
        var page=1;
        $("#pagenumber").val(page);
        getActivityLogs(page);
    });

    $("#clearFilter").on("click",function(e){
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    }); 

    $("#result").on( "click",".pagination a", function (e){
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        //window.location = "#"+page;
        getActivityLogs(page);
    });
}

function getActivityLogs(page){ 
    ajaxloading('Loading logs...'); 
    var refine_filter_arr = [];
    var parameter = [];
    jQuery('.refine_filter option:selected,input.refine_filter').each(function () {
        refine_filter_arr.push({'type':$(this).attr("data-type"),'value':$(this).val()});
        parameter.push({'name':$(this).attr('data-type'),'value':$(this).val()});
    });
    var recursiveEncoded = $.param(parameter,true);
    //window.location.hash = recursiveEncoded;
    $.post(urljs+"admin/activitylog/getActivity_Logs",{"page":page,'filter_data':refine_filter_arr,},function(data){
        $("#activityTbl").html(data.str);
        $('[data-toggle="tooltip"]').tooltip(); 
        $('[data-toggle="popover"]').popover();
        activitylogFilter();
        closeajax();
    },"json");
}


</script>
</body>
</html>
  