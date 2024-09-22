<?php
    if($userQ['status']=='success' && !empty($userQ['data_list'])){
        $userData = $userQ['data_list'];
        $customerId=$userData['id'];
        $firstName=$userData['firstName'];
        $lastName=$userData['lastName'];
        $email=$userData['email'];
        $phone=$userData['phone'];
        $address=$userData['address'];
        $city=$userData['city'];
        $country=$userData['country'];
        $pincode=$userData['postalCode'];
    }else{
        redirect(base_url().'home');
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
                        <li class="breadcrumb-item active" aria-current="page">Account</li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div><!-- End .container -->
            </nav>
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-lg-3 order-sm-1 " >
                        <div class="widget widget-dashboard">
                            <ul class="list">
                                <li class="active"><a href="<?php echo base_url()?>account/dashboard">Dashboard</a></li>
                                <li><a href="<?php echo base_url()?>account/myprofile">Edit Profile </a></li>
                                <li><a href="<?php echo base_url()?>account/myorders">My Orders</a></li>
                                <li><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <li><a href="<?php echo base_url()?>account/wallets">Wallet Purchase</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 " >
                        <h2>Hello, <?php echo get_userName() ?>!</h2>
                        <div class="mb-4"></div><!-- margin -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Contact Information
                                        <a href="<?php echo base_url()?>account/myprofile" class="card-edit">Edit</a>
                                    </div><!-- End .card-header -->

                                    <div class="card-body">
                                        <p>
                                            <strong><?php echo $firstName.' '.$lastName ?></strong><br>
                                            <?php echo $email ?><br>
                                            <?php echo $phone ?><br>
                                            <a href="<?php echo base_url()?>account/change_pass">Change Password</a>
                                        </p>
                                    </div><!-- End .card-body -->
                                </div><!-- End .card -->
                            </div><!-- End .col-md-6 -->

                            <!-- <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Default Billing Address
                                        <a href="<?php echo base_url()?>account/myaddresses" class="card-edit">Edit</a>
                                    </div>
                                    <div class="card-body">
                                      <?php 
                                        // if($billAddrQ['status']=='success' && !empty($billAddrQ['data_list'])){ 
                                        //     $billAddr = $billAddrQ['data_list'][0];
                                            // print_r($billAddr);
                                            // echo "<hr>";
                                        ?>
                                        <p>
                                            <strong><?php //echo $billAddr['name'] ?></strong><br>
                                            <?php //echo $billAddr['address'] ?><br>
                                            <?php //echo $billAddr['city'] ?><br>
                                            <?php //echo $billAddr['country'].'-'.$billAddr['postalCode'] ?>
                                        </p>
                                      <?php //  }?>
                                    </div>
                                </div>
                            </div> -->
                            <!-- End .col-md-6 -->
                        </div><!-- End .row -->

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