<?php
$settingId='';
$companyName='';
$caption='';
$GSTNO='';
$email='';
$phone='';
$mobile='';
$website='';
$address='';
$host='';
$port='';
$hostUsername='';
$hostPassword='';
$from='';
$fromText='';
$signature='';
$logo='';

// foreach ($dataQ->result() as $rowData) {
  if(!empty($dataQ)){
  $settingId=$dataQ['id'];
  $companyName=$dataQ['name'];
  $caption=$dataQ['caption'];
  $GSTIN=$dataQ['GSTIN'];
  $email=$dataQ['email'];
  $phone=$dataQ['phone'];
  $mobile=$dataQ['mobile'];
  $website=$dataQ['website'];
  $address=$dataQ['address'];
  $signature=$dataQ['name'];
  // $branches=$dataQ['branches'];
  $logo=$dataQ['logo'];
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
  <link href="<?php echo base_url()?>ui/assets/plugins/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
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
                                <div class="ibox-title">Company Details Here:</div>
                            </div>
                             <div class="ibox-body">
                             <form role="form" id="save_company_sett">
                                <input type="hidden" name="settingId" value="<?php echo $settingId ?>">
                                <div class="row">
                                    <div class="col-lg-6">
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Company Name<span class="text-danger">*</span></label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="companyName" type="text" value="<?php echo $companyName ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Caption</label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="caption" type="text" value="<?php echo $caption ?>">
                                          </div>
                                      </div>                                      
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">GSTIN</label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="GSTIN" type="text" value="<?php echo $GSTIN ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Email Address<span class="text-danger">*</span></label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="email" type="text" value="<?php echo $email ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Phone<span class="text-danger">*</span></label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="phone" type="text" value="<?php echo $phone ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Mobile<span class="text-danger">*</span></label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="mobile" type="text" value="<?php echo $mobile ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Website</label>
                                          <div class="col-sm-8">
                                            <input class="form-control" name="website" type="text" value="<?php echo $website ?>">
                                          </div>
                                      </div>
                                    </div>

                                    <div class="col-lg-6">
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Address<span class="text-danger">*</span></label>
                                          <div class="col-sm-8">
                                            <textarea class="form-control" name="address"><?php echo $address ?></textarea>
                                          </div>
                                      </div>
                                      <!-- <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Branch Locations</label>
                                          <div class="col-sm-8">
                                            <textarea class="form-control" name="branches"><?php //echo $branches ?></textarea>
                                          </div>
                                      </div> -->
                                      <div class="row">
                                        <div class="col-sm-12">
                                          <div class="ibox"> 
                                            <div class="ibox-head">                                          
                                              <label class="col-form-label">Company Logo</label>
                                            </div>
                                            <div class="ibox-body">
                                                <input type="file" id="logoinput" name="attachment[]" class="dropify" data-max-file-size="5M" data-height="150" data-logo="<?php echo $logo ?>" title="" accept=".jpg,.png.jpeg"/>
                                                <input type="hidden" name="logo_old" value="<?php echo $logo ?>">
                                            </div>
                                          </div>
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
    <script src="<?php echo base_url() ?>ui/assets/plugins/dropify/js/dropify.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/settings.js"></script>
</body>
</html>
  