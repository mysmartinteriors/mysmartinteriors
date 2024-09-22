<?php 
    if($userQ['status']=='success' && !empty($userQ['data_list'])){
        $userData = $userQ['data_list'];
            $customerId=$userData['id'];
            $firstName=$userData['firstName'];
            $lastName=$userData['lastName'];
            $email=$userData['email'];
            $phone=$userData['phone'];
            $address=$userData['address'];
            $state=$userData['state'];
            $city=$userData['city'];
            $country=$userData['country'];
            $postalCode=$userData['postalCode'];
    }else{
        redirect(base_url().'account/login');
    }

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo $title ?></title>
      <meta name="keywords" content="" />
      <meta name="description" content="">
      <meta name="author" content="">      
      <!-- Plugins CSS File -->
      <?php echo $commonCss ?>
   </head>
   <body>
      <div class="page-wrapper">
         <!-- start header-->
         <?php echo $header_main ?>
         <!-- End .header -->

         <main class="main">

            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?php echo base_url()?>account">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                    </ol>
                </div><!-- End .container -->
            </nav>
            <div class="container">
                <div class="row">

                    <aside class="sidebar col-lg-3">
                        <div class="widget widget-dashboard">
                           <ul class="list">
                                <li><a href="<?php echo base_url()?>account/dashboard">Dashboard</a></li>
                                <li class="active"><a href="<?php echo base_url()?>account/myprofile">Edit Profile </a></li>
                                <li><a href="<?php echo base_url()?>account/myorders">Your Orders</a></li>
                                <li><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <!-- <li><a href="<?php //echo base_url()?>account/mytickets">Tickets</a></li> -->
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9 order-lg-last dashboard-content">
                        <h2>Edit Account Information</h2>                        
                        <form id="user_cpass_form" method="post" role="form">
                          <input type="hidden" name="customerId" value="<?php echo $customerId ?>">                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group Togglepwd required-field">
                                        <label class="col-form-label">Current Password</label>
                                        <input type="password" class="form-control form-input form-wide"  name="old_pass" value="">
                                        <i class="fa fa-eye-slash pwd-input"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group Togglepwd required-field">
                                        <label class="col-form-label">New Password</label>
                                        <input type="password" class="form-control form-input form-wide"  name="new_pass" value="">
                                        <i class="fa fa-eye-slash pwd-input"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group Togglepwd required-field">
                                        <label class="col-form-label">Confirm New Password</label>
                                        <input type="password" class="form-control form-input form-wide"  name="cnew_pass" value="">
                                        <i class="fa fa-eye-slash pwd-input"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="required text-right">* Required Fields</div>
                            <div class="form-footer">
                                <div class="form-footer-right">
                                    <button type="submit" class="btn btn-primary" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Updating...">Update</button>
                                </div>
                            </div><!-- End .form-footer -->
                        </form>
                    </div><!-- End .col-lg-9 -->



        
                </div><!-- End .row -->
            </div><!-- End .container -->

            <div class="mb-5"></div>
        </main><!-- End .main -->
         <!-- End .main -->
         <?php echo $footer ?>
         <!-- End .footer -->
      </div>
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
      
      <?php echo $commonJs ?>

   </body>
</html>