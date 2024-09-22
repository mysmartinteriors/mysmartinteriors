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
                        <li class="breadcrumb-item active" aria-current="page">My Tickets</li>
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
                                <li><a href="<?php echo base_url()?>account/myorders">Your Orders</a></li>
                                <li><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <li class="active"><a href="<?php echo base_url()?>account/mytickets">Tickets</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 " >
                        <h2>My Tickets</h2> 
                        <div class="mb-4"></div>
                        <div class="row">
                            <div class="col-md-12">                   
                                <div id="user_ticketTbl"></div>
                            </div>         
                        </div>
                    </div>      
                </div>
            </div>

            <div class="mb-5"></div>
        </main><!-- End .main -->
         <!-- End .main -->
         <?php echo $footer ?>
         <!-- End .footer -->
      </div>
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
        <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/ckeditor.js"></script>
        <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/adapters/jquery.js"></script>
      <?php echo $commonJs ?>

   </body>
</html>