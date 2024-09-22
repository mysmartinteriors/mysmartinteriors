<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">

    <?php echo $commonCss ?>
    <style type="text/css">
        .datepicker td, .datepicker th {
            width: 25px;
            height: 25px;
            font-size: 12px;
        }
    </style>
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
                <h1 class="page-title">Reports</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Reports</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content reports-page">
                <!-- end row -->
                <div class="row">
                    <div class="col-12">                       
                        <div class="ibox">                            
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">
                                    <div class="row">
                                        <div class="col-sm-10 col-11">
                                            Here you can see & download all the reports
                                        </div>
                                        <div class="col-sm-2 col-1">
                                            <a class="fullscreen-link float-right"><i class="ti-fullscreen"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-body adv_filter tblbox">
                                <form role="form" method="post" id="report_form" class="mb-3">
                                    <div class="row">
                                        <div class="col-sm-2 col-12">
                                            <div class="clearfix">
                                                <label for="priority"> Select module<span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-10 col-12">
                                            <div class="clearfix">
                                                <select class="custom-select mb-3 rptmodule" name="module">
                                                    <option value="">--Module--</option>
                                                    <option value="customers">Customers</option>
                                                    <option value="products">Products</option>
                                                    <option value="prd_orders">Products Sale</option>
                                                    <option value="orders">Orders</option>
                                                </select>
                                                <select class="custom-select mb-3 rptstatus" name="status">
                                                    <option value="">--All--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                                   
                                        <div class="col-sm-2 col-2">
                                            <div class="form-group clearfix">
                                                <label for="note">Select date range<span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-10 col-12">
                                            <div class="form-inline clearfix">
                                                <select class="custom-select mb-3 rptdate" name="dateType">
                                                    <option value="">--Date Type--</option>
                                                </select>
                                                <span class="rprtequals mb-3">&nbsp;=</span>
                                                <div class="report-dateinput">
                                                    <div class="reportrange">                       
                                                        <input id="reportrange" class="form-control mb-3 ml-1 input-limit-datepicker" type="text" name="daterange" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                           
                                        <div class="col-sm-6 col-12 text-center">
                                            <button type="submit" class="btn btn-primary btn-report rptsearch">Search</button>
                                            <button type="button" class="btn btn-primary btn-report rptdownload" style="display: none;">Download</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="reportsTbl">
                                            
                                        </div>
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

    <script src="<?php echo base_url()?>ui/assets/plugins/moment/min/moment.min.js"></script>
    <link href="<?php echo base_url()?>ui/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>ui/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>ui/assets/plugins/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>ui/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />

    <script src="<?php echo base_url()?>ui/assets/plugins/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url()?>ui/assets/plugins/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url()?>ui/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url()?>ui/assets/plugins/clockpicker/dist/bootstrap-clockpicker.min.js"></script>
    <script src="<?php echo base_url()?>ui/assets/plugins/multiselect/js/jquery.multi-select.js"></script>


    <script src="<?php echo base_url()?>ui/assets/js/my-scripts/reports.js"></script>

</body>
</html>
  