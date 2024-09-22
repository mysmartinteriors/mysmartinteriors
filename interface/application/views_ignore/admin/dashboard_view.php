<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
    <!-- PLUGINS STYLES-->
    <link href="<?php echo base_url() ?>ui/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper dashboard_pages">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="<?php echo base_url() . "admin/products" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($prd_count) ? $prd_count : 'N/A' ?></h2>
                                    <div class="text-muted">TOTAL PRODUCTS</div><i
                                        class="ti-package widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?php echo base_url() . "admin/categories" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($cats_count) ? $cats_count : 'N/A' ?></h2>
                                    <div class="text-muted">TOTAL CATEGORIES</div><i
                                        class="ti-menu-alt widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?php echo base_url() . "admin/customers" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($cust_count) ? $cust_count : 'N/A' ?></h2>
                                    <div class="text-muted">TOTAL CUSTOMERS</div><i
                                        class="ti-user widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?php echo base_url() . "admin/orders" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($order_count) ? $order_count : 'N/A' ?></h2>
                                    <div class="text-muted">TOTAL ORDERS</div><i
                                        class="ti-shopping-cart widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="ibox">
                        <div class="ibox-body">
                            <h2 class="mb-1">Orders Today</h2>
                            <div><?php echo $orderView ?></div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="ibox">
                        <div class="ibox-body">
                            <h2 class="mb-1">Whatsapp Logs</h2>
                            <div><?php echo $whatsappLogView  ?></div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
        <!-- END PAGE CONTENT-->
        <?php echo $footer ?>
    </div>
    </div>
    <?php echo $commonJs ?>

</body>

</html>