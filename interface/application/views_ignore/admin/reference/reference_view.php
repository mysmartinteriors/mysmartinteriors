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
                <h1 class="page-title">Referral Comissions</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Referral Comissions</li>
                    <li class="breadcrumb-item">Plan Listing</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <!-- end row -->
                <div class="row">
                    <div class="col-12">                       
                        <div class="ibox">                            
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Here are the list of reference comission percentage for customers</div>
                            </div>
                                <div class="ibox-body adv_filter tblbox">
                                        <div class="row mb-4">
                                            <div class="col-sm-10 col-11">
                                            </div>
                                            <div class="col-sm-2 col-1">
                                                <a class="fullscreen-link float-right"><i class="ti-fullscreen"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="referenceTbl"></div>
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
    <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/adapters/jquery.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/reference.js"></script>
</body>
</html>
  