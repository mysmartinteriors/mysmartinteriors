<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>uiuploads/logo/favicon.png">
    <?php echo $commonCss ?>   
     <!-- PLUGINS STYLES-->
    <link href="<?php echo base_url()?>ui/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
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
                        <a href="<?php echo base_url(). "delivery/orders" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($pord_count)?$pord_count:'0' ?></h2>
                                    <div class="text-muted">Pending Orders</div><i class="ti-package widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?php echo base_url(). "delivery/orders" ?>">
                            <div class="ibox">
                                <div class="ibox-body">
                                    <h2 class="mb-1"><?php echo !empty($cord_count)?$cord_count:'0' ?></h2>
                                    <div class="text-muted">Completed Orders</div><i class="ti-menu-alt widget-stat-icon text-success"></i>
                                </div>
                            </div>
                        </a>
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
  