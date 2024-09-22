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
                        <li class="breadcrumb-item active" aria-current="page">My Repurchase Earning</li>
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
                                <li><a href="<?php echo base_url()?>account/wallets">Wallet Purchase</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li class="active"><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 " >
                        <!-- <div class="d-flex"> -->
                        
                        <?php $userId=get_userId(); ?>
                        <div class="mb-2"></div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="user-single-tabs user-single-tabs_new">
                                          <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                            <a class="nav-link active" id="user-referal-sec" data-toggle="tab" href="#my_referals" role="tab" aria-controls="my_referals" aria-selected="false">Repurchase Bonus Earning</a>
                                            </li>
                                            <li class="nav-item">
                                            <a class="nav-link" id="user-order-sec" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">Repurchase Bonus Percentage</a>
                                            </li>
                                          </ul>
                                          <div class="tab-content mt-2">
                                          <div class="tab-pane fade show active" id="my_referals" role="tabpanel" aria-labelledby="user-referals-sec">
                                                    <div class="orders">    
                                                      <?php if(isset($repurchase_customers)){ ?>
                                                      <div class="table-responsive">
                                                          <table class="table table-bordered">
                                                              <thead class="thead-light">
                                                                  <tr>
                                                                      <th>Sl. No.</th>
                                                                      <th>Level</th>
                                                                      <th>Earning</th>          
                                                                      <th>Purchased By</th>          
                                                                      <th>Purchased On</th>          
                                                                      <th>Repurchasable Products Amount</th>          
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                                <?php 
                                                                $i=1; 
                                                                foreach($repurchase_customers as $repurchasedCustomer){ ?>
                                                                  <tr>
                                                                    <td><?php echo $i; ?></td>
                                                                    <td><?php echo $repurchasedCustomer['level'] ?></td>
                                                                    <td><?php echo $repurchasedCustomer['amount'] ?></td>
                                                                    <td><?php echo $repurchasedCustomer['ref_by_first_name']. ' '.$repurchasedCustomer['ref_by_last_name'] ?></td>
                                                                    <td><?php echo $repurchasedCustomer['createdDate'] ?></td>
                                                                    <td><?php echo $repurchasedCustomer['orderAmount'] ?></td>
                                                                  </tr>
                                                              <?php $i++; } ?>
                                                              </tbody>
                                                          </table>
                                                        </div>
                                                         
                                                         <?php 
                                                      }else{?>                                                  
                                                        <p class="text-center">You don't have any earning from repurchase bonus</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                              <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="user-order-sec">
                                                  <div class="orders">    
                                                    <?php if($reference['status'] == 'success' && !empty($reference['data_list'])){ ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Sl. No.</th>
                                                                    <th>Name</th>
                                                                    <th>Percentage</th>          
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                              <?php 
                                                              $i=1;
                                                              foreach($reference['data_list'] as $referenceData){ 
                                                              ?>
                                                                <tr>
                                                                    <td><?php echo $i ?></td>
                                                                    <td><?php echo $referenceData['name'] ?></td>
                                                                    <td><?php echo $referenceData['percentage'] . " %" ?></td>
                                                                </tr>
                                                            <?php $i++; } ?>
                                                            </tbody>
                                                        </table>
                                                      </div>
                                                       
                                                       <?php 
                                                    }else{?>                                                  
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