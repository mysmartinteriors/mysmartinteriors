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
                        <li class="breadcrumb-item active" aria-current="page">My Wallet</li>
                    </ol>
                </div><!-- End .container -->
            </nav>
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-lg-3 order-sm-1 " >
                        <div class="widget widget-dashboard">
                            <ul class="list">
                                <li><a href="<?php echo base_url()?>account/dashboard">Dashboard</a></li>
                                <li><a href="<?php echo base_url()?>account/myprofile">Edit Profile </a></li>
                                <li><a href="<?php echo base_url()?>account/myorders">My Orders</a></li>
                                <li><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <li class="active"><a href="<?php echo base_url()?>account/wallets">Wallet Purchase</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 " >
                        <div class="mb-2"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="user-single-tabs">
                                          <ul class="nav nav-tabs" role="tablist">
                                              <li class="nav-item">
                                                  <a class="nav-link active" id="user-book-sec" data-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="true">Wallet Purchase</a>
                                              </li>
                                              <!-- <li class="nav-item">
                                                  <a class="nav-link" id="user-order-sec" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">Re-Purchase</a>
                                              </li> -->
                                          </ul>
                                          <div class="tab-content mt-2">
                                              <div class="tab-pane fade show active" id="bookings" role="tabpanel" aria-labelledby="user-book-sec">
                                                  <div class="bookings">
                                                    <?php if($customerQ['status'] && !empty($customerQ['data_list'])){ 
                                                        $customerData = $customerQ['data_list'];
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card text-center">
                                                                    <div class="card-header">Wallet Purchase</div>
                                                                    <div class="card-body" style="font-size:30px">&#8377; <?php echo !empty($customerData['subscriptionAmount'])?$customerData['subscriptionAmount']:0 ?> </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card text-center">
                                                                    <div class="card-header">Wallet Purchase Bonus</div>
                                                                    <div class="card-body" style="font-size:30px"><?php echo !empty( $customerData['subscriptionPoints'])?$customerData['subscriptionPoints']:0 ?> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                      
                                                   <?php }else{ ?>
                                                      <p class="text-center">You don't have any orders!<br><a href="<?php echo base_url()?>Products">Shop now</a>  </p>
                                                   <?php } ?>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                            </div><!-- End .col-md-6 -->          
                        </div><!-- End .row -->
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