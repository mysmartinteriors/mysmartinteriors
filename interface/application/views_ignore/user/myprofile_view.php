<?php 
    if($userQ['status']=='success' && !empty($userQ['data_list'])){
        $customerId=$userQ['data_list']['id'];
        $firstName=$userQ['data_list']['firstName'];
        $lastName=$userQ['data_list']['lastName'];
        $email=$userQ['data_list']['email'];
        $phone=$userQ['data_list']['phone'];
        $address=$userQ['data_list']['address'];
        $state=$userQ['data_list']['state'];
        $city=$userQ['data_list']['city'];
        $country=$userQ['data_list']['country'];
        $postalCode=$userQ['data_list']['postalCode'];
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
                                <li><a href="<?php echo base_url()?>account/myorders">My Orders</a></li>
                                <li><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <li><a href="<?php echo base_url()?>account/wallets">Wallet Purchase</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9 order-lg-last dashboard-content">
                        <h2>Edit Account Information</h2>                        
                        <form id="user_profile_form" method="post" role="form">
                            <div class="row" id="error"></div>
                          <input type="hidden" name="customerId" value="<?php echo $customerId ?>">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide"  name="firstName" value="<?php echo $firstName ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide" name="lastName" value="<?php echo $lastName ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Email address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-input form-wide"  name="email"  value="<?php echo $email ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Phone number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide" name="phone" value="<?php echo $phone ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if(empty($billAddrQ['data_list'])){ ?>

                            <div class="row">
                              <div class="col-lg-12">
                                <div class="form-divider">
                                  <h5 class="head">Your address <span class="text-danger">*</span></h5>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Area/Lane address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide"  name="address" value="<?php echo $address ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">City <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide" name="city" value="<?php echo $city ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">State <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide"  name="state" value="<?php echo $state ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Country <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide"  name="country" value="<?php echo $country ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Postal code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-input form-wide" name="postalCode" value="<?php echo $postalCode ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

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