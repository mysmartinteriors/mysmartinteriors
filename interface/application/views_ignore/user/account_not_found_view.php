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
      <div class="page-wrapper login-pages">
         <!-- start header-->
         <?php echo $header_main ?>
         <!-- End .header -->
         <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Account</li>
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </div><!-- End .container -->
            </nav> 
            <div class="container">
                <div class="mb-1"></div>
                <div class="row">
                  <div class="col-12">
                        <h3 class="alert bg-danger text-white text-center">We couldn't find your account. Please register yourself first to login.</h3>
                  </div>
                </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-box">
                        <h2 class="light-title"><strong>Login</strong> to your account</h2>
                        <form id="user_login_form" role="form" method="post">
                          <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="contact-email required-field">Email address/Mobile Number </label>
                                    <input type="email" class="form-control" name="email" value="">
                                </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10">
                              <div class="form-group Togglepwd">
                              <label for="contact-phone">Password</label>
                                  <input type="password" class="form-control" name="password" value="">
                                <i class="fa fa-eye-slash pwd-input"></i>
                              </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="form-footer">
                                <button type="submit" class="btn btn-primary" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Logging in..."><i class='fa fa-sign-in'></i> Login</button>
                          </div><!-- End .form-footer -->
                        </form>
                      <!-- <a href="javascript:void(0);" class="forget-password" id="forgot_pwd_btn"> Forgot your password?</a> -->

                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-box">
                        <h2 class="light-title">Don't have account? <strong>Register Now!</strong></h2>
                        <form id="user_regstr_form" role="form" method="post">
                          <div class="error"></div>
                          <div class="errors"></div>
                          <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="contact-email required-field">First Name</label>
                                    <input type="text" class="form-control" name="firstName" value="">
                                </div><!-- End .form-group -->
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="contact-email required-field">Last Name</label>
                                    <input type="text" class="form-control" name="lastName" value="">
                                </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="contact-email required-field">Email Address</label>
                                    <input type="email" class="form-control" name="email" value="">
                                </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="contact-email required-field">Mobile Number</label>
                                    <input type="phone" class="form-control" name="phone" value="">
                                </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10">
                              <div class="form-group Togglepwd">
                              <label for="contact-phone">Choose Password</label>
                                  <input type="password" class="form-control" name="password" value="">
                                <i class="fa fa-eye-slash pwd-input"></i>
                              </div><!-- End .form-group -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10"> 
                              <div class="form-group">
                                  <label class="col-form-label">Reference Code <span class="required">(Please enter if you have)</span></label>
                                    <input type="text" class="col-12 form-input form-wide" name="referral_code" data-edit="">
                              </div>
                            </div>
                          </div>
                          <div class="form-footer">
                                <button type="submit" class="btn btn-primary" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Logging in..."><i class='fa fa-check-square-o'></i> Register</button>
                          </div><!-- End .form-footer -->
                        </form>               
                      </div>
                    </div>
                </div><!-- End .row -->
            </div><!-- End .container -->

            <div class="mb-8"></div><!-- margin -->
        </main><!-- End .main -->             
         
         <!-- End .main -->
         <?php echo $footer ?>
         <!-- End .footer -->
      </div>
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
      
      <?php echo $commonJs ?>
      <script type="text/javascript">
        
          login_auth();
          user_forgot_pwd();
          user_register();
      </script>
   </body>
</html>