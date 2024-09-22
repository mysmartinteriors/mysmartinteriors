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
                        <li class="breadcrumb-item active" aria-current="page">My Referals Earnings</li>
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
                                <li class="active"><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 " >
                        <!-- <div class="d-flex"> -->
                        <?php $userId=get_userId(); ?>
                        <?php
                           $whatsappmsg = "Hey, I wanted to share this amazing referral for Nalaaorganic for fresh fruits and vegetables at your doorsteps and get amazing discounts on every order.\n\n*Use my mobile number as referral code*\n\nAndroid \n https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en\n\nIOS \nhttps://apps.apple.com/in/app/nalaa-organic/id6499208933\n\nWEB\nhttps://nalaaorganic.com/account/login\n\nPlease reply 'OK' to enable the link.\n\nThank You";
                              $whatsappmsg = rawurlencode($whatsappmsg);
                        ?>
                        <a class="btn btn-success" target="_blank" href="<?php echo 'https://wa.me/?text='.$whatsappmsg ?>" customer-id="<?php echo $userId ?>">
                        <i class="fa fa-paper-plane"></i>&nbsp; Refer And Earn</a>

                        <a class="referral_view btn btn-warning" href="javascript:;" customer-id="<?php echo $userId ?>">
                        <i class="fa fa-users"></i> &nbsp;View Your Referrals</a>

                        <div class="mb-2"></div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="user-single-tabs user-single-tabs_new">
                                          <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                            <a class="nav-link active" id="user-referal-sec" data-toggle="tab" href="#my_referals" role="tab" aria-controls="my_referals" aria-selected="false">Referals Bonus Earning</a>
                                            </li>
                                            <li class="nav-item">
                                            <a class="nav-link" id="user-order-sec" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">Referals Bonus Percentage</a>
                                            </li>
                                          </ul>
                                          <div class="tab-content mt-2">
                                          <div class="tab-pane fade show active" id="my_referals" role="tabpanel" aria-labelledby="user-referals-sec">
                                                    <div class="orders">    
                                                      <?php if(isset($refered_customers)){ ?>
                                                      <div class="table-responsive">
                                                          <table class="table table-bordered">
                                                              <thead class="thead-light">
                                                                  <tr>
                                                                      <th>Sl. No.</th>
                                                                      <th>Level</th>
                                                                      <th>Earning</th>          
                                                                      <th>Subscription By</th>          
                                                                      <th>Purchased On</th>          
                                                                      <th>Purchased Amount</th>       
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                                <?php 
                                                                $i=1;
                                                                foreach($refered_customers as $referedCustomers){ ?>
                                                                  <tr>
                                                                    <td><?php echo $i; ?></td>
                                                                    <td><?php echo $referedCustomers['level'] ?></td>
                                                                    <td><?php echo $referedCustomers['amount'] ?></td>
                                                                    <td><?php echo $referedCustomers['ref_by_first_name']. ' '.$referedCustomers['ref_by_last_name'] ?></td>
                                                                    <td><?php echo $referedCustomers['createdDate'] ?></td>
                                                                    <td><?php echo $referedCustomers['subscriptionAmount'] ?></td>
                                                                  </tr>
                                                              <?php $i++; } ?>
                                                              </tbody>
                                                          </table>
                                                        </div>
                                                         
                                                         <?php 
                                                      }else{?>                                                  
                                                        <p class="text-center">You don't have any referals bonus earning</p>
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

    <script>

        $(".referral_view").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('customer-id');
        var mediaDialog = bootbox.dialog({
            title: 'View Referrals Levels',
            size: 'extra-large',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        mediaDialog.init(function () {
            // setTimeout(function () {
                $.post(urljs + "account/referrals_level", { id }, function (data) {
                    if (data.status=='success') {
                        mediaDialog.find('.bootbox-body').html(data.message);
                        // mediaDialog.find("div.modal-dialog").addClass("sliderModal");
                        // initImageUpload();
                        // save_add_data();
                    }else {
                        swal('Warning', 'Error occured!', 'warning');
                    }
                }, "json");
            // }, 0);
        });
    });
    </script>

   </body>
</html>