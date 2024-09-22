<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>   
     <!-- PLUGINS STYLES-->
    <link href="<?php echo base_url()?>ui/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
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
            <!-- START PAGE CONTENT-->
                <div class="page-content">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="ibox">
                                <div class="ibox-body permission-page">
                                    <div class="ex-page-content text-center">
                                        <i class="fa fa-info-circle"></i>
                                        <h3 class="text-error shadow-text">Access Denied!</h3>
                                        <p class="m-t-30">You do not have permission to access this resource</p>
                                        <p class="small">*Please contact admin for access permissions</p>
                                        <br>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo $footer ?>
            </div>
        </div>
        <?php echo $commonJs ?>
       
    </body>
</html>