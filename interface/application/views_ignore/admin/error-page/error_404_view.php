<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">  

    <link href="<?php echo base_url() ?>ui/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <?php echo $commonCss ?>
        <style>
        .content-wrapper {
            background-color: #fff;
            background-repeat: no-repeat;
            background-image: url(../ui/assets/img/icons/search-document-3.svg);
            background-position: 80% 0;
        }

        .error-content {
            max-width: 620px;
            margin: 200px auto 0;
        }

        .error-icon {
            height: 160px;
            width: 160px;
            background-image: url(../ui/assets/img/icons/search-document.svg);
            background-size: cover;
            background-repeat: no-repeat;
            margin-right: 80px;
        }

        .error-code {
            font-size: 120px;
            color: #5c6bc0;
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
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12"> 
                      <div class="error-content">
                          <div class="flexbox">
                              <span class="error-icon"></span>
                              <div class="flex-1">
                                  <h1 class="error-code">404</h1>
                                  <h3 class="font-strong">NOT FOUND</h3>
                                  <p>Sorry, the page you were looking for could not found.</p>
                              </div>
                          </div>
                          <div class="text-center mb-3 mt-5">
                              <a class="text-primary" href="<?php echo base_url()?>admin/dashboard">Goto Homepage</a></div>
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
  