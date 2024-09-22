<?php
$adminId='';
$username='';
$email='';
$firstName='';
$lastName='';
$password='';
$roleId='';
$status='';
$picture='';

if(!empty($dataQ)) {
  $adminId=$dataQ['id'];
  $username=$dataQ['login_id'];
  $email=$dataQ['email'];
  $firstName=$dataQ['first_name'];
  $lastName=$dataQ['last_name'];
  // $password=$dataQ['password'];
  $roleId=$dataQ['roles_id'];
  $status=$dataQ['status'];
  $picture=$dataQ['picture'];
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
                <h1 class="page-title">My Profile</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/customers">Settings</a></li>
                    <li class="breadcrumb-item">Profile</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Your Profile Details Here:</div>
                            </div>
                             <div class="ibox-body">
                             <form role="form" id="save_profile_form">
                                <input type="hidden" name="id" value="<?php echo $adminId ?>">
                                <div class="row">
                                  <div class="col-sm-6">
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Username<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="username" type="text" value="<?php echo $username ?>" readonly>
                                          </div>
                                      </div>                                     
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">First Name<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="firstName" type="text" value="<?php echo $firstName ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Last Name<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="lastName" type="text" value="<?php echo $lastName ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Email Address<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="email" type="text" value="<?php echo $email ?>">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Password<span class="text-danger">*</span></label>
                                          <div class="col-sm-7 Togglepwd">
                                            <input class="form-control" name="password" type="password" value="">
                                            <i class="fa fa-eye-slash pwd-input"></i>
                                          </div>
                                      </div>  
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Confirm Password<span class="text-danger">*</span></label>
                                          <div class="col-sm-7 Togglepwd">
                                            <input class="form-control" name="c_password" type="password" value="">
                                            <i class="fa fa-eye-slash pwd-input"></i>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="row mb-4">
                                        <div class="col-sm-12">
                                          <div class="ibox"> 
                                            <div class="ibox-head">                                          
                                              <label class="col-form-label">Profile Picture</label>
                                            </div>
                                            <div class="ibox-body">
                                                <input type="file" id="logoinput" name="attachment[]" class="dropify" data-max-file-size="5M" data-height="180" data-logo="<?php echo $picture ?>" title="" accept=".jpg,.png.jpeg"/>
                                                <input type="hidden" name="logo_old" value="<?php echo $picture ?>">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                      <div class="col-lg-12">
                                        <div class="text-center mt-0">
                                            <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                                        </div>
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
  