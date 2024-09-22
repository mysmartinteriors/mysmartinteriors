<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>
        <?php echo $title ?>
    </title>
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
                <h1 class="page-title">Customer Bookings</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item">Bookings</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Here are the customer bookings</div>
                            </div>
                            <div class="ibox-body adv_filter tblbox">
                                <div class="row mb-4">
                                    <div class="col-sm-10 col-11">
                                        <div class="input-group  pull-left" id="adv-search">
                                            <input type="text" class="form-control refine_filter clearAbleFilt"
                                                id="product" data-type="search"
                                                placeholder="Search for any records..." />
                                            <input type="hidden" name="page" id="pagenumber" data-type="page"
                                                data-id="page" class="refine_filter" />
                                            <div class="input-group-btn">
                                                <div class="btn-group" role="group">

                                                    <button type="button" class="btn btn-primary filter"><span
                                                            class="fa fa-search" aria-hidden="true"></span></button>
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
                                        <div id="completedordersTbl"></div>
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
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/orders_pending.js"></script>
</body>

</html>