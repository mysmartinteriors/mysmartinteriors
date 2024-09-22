<?php
$settingId="";
$host="";
$port="";
$hostUsername="";
$hostPassword="";
$from="";
$fromText="";
$signature="";
$automatedReply="";
$ccEmails="";
$smtpSecure="";
$smtpAuth="";

foreach ($dataQ->result() as $row) {
  $settingId=$row->settingId;
  $host=$row->host;
  $port=$row->port;
  $smtpSecure=$row->smtpSecure;
  $smtpAuth=$row->smtpAuth;
  $hostUsername=$row->hostUsername;
  $hostPassword=$row->hostPassword;
  $from=$row->from;
  $fromText=$row->fromText;
  $signature=$row->signature;
  $automatedReply=$row->automatedReply;
  $ccEmails=$row->ccEmails;
}
?>

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
      .mediaImgPrev {width: 30%;}
      .openMediaModal { margin-bottom: 0px;}
      .mediaImgPrev.imgExist {display: inline;}
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
                <h1 class="page-title">Settings</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/customers">Settings</a></li>
                    <li class="breadcrumb-item">Company</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Email Settings Here:</div>
                            </div>
                             <div class="ibox-body">
                             <form role="form" id="save_email_settings">
                                <input type="hidden" name="settingId" value="<?php echo $settingId ?>">
                                <div class="row">
                                    <div class="col-lg-6">                        
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label col-form-label">Host<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="host" type="text" value="<?php echo $host ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Port<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="port" type="text" value="<?php echo $port ?>">
                                          </div>
                                      </div>                                      
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Host Username<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="hostUsername" type="text" value="<?php echo $hostUsername ?>">
                                          </div>
                                      </div>                                   
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Host Password<span class="text-danger">*</span></label>
                                          <div class="col-sm-7 Togglepwd">
                                          <input class="form-control" name="hostPassword" type="password" value="<?php echo $hostPassword ?>">
                                            <i class="fa fa-eye-slash pwd-input"></i>
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">SMTP Authentication<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="smtpAuth" type="text" value="<?php echo $smtpAuth ?>">
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">SMTP Secure<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="smtpSecure" type="text" value="<?php echo $smtpSecure ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">From Email<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="from" type="text" value="<?php echo $from ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">From Text<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="fromText" type="text" value="<?php echo $fromText ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">CC Email</label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="ccEmails" type="text" value="<?php echo $ccEmails ?>">
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-12">
                                      <div class="text-center">
                                          <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                                      </div>
                                    </div>
                                </div>
                            </form>
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
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/settings.js"></script>
</body>
</html>
  