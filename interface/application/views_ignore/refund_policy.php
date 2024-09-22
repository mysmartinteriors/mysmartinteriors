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
                        <li class="breadcrumb-item active" aria-current="page">Cancellation and Refund Policy</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="about-section">
                <div class="container">
                    <h2 class="subtitle">Cancellation & Refund Policy</h2>
                    <p>Nalaa organic doesn’t allow its customer to cancel the order under any circumstances no order will be cancelled. Since there is no cancellation of order, no refund will be processed.</p>
                    <p>Our standard delivery timeline guarantees that orders placed before 7 PM on our 4-day delivery schedule will be delivered by the following day. Orders placed after this time will be delivered within an additional day.</p>
                </div><!-- End .container -->
            </div><!-- End .about-section -->

            <div class="features-section">
                <div class="container">
                    <h2 class="subtitle">WHY CHOOSE US</h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="feature-box">
                                <i class="icon-shipped"></i>

                                <div class="feature-box-content">
                                    <h3>Free delivery order Above 375 /- RS</h3>
                                    <p>Enjoy complimentary shipping on orders totaling above &#8377; 375. Shop now and indulge in hassle-free delivery!</p>
                                </div><!-- End .feature-box-content -->
                            </div><!-- End .feature-box -->
                        </div><!-- End .col-lg-4 -->
                        
                        <div class="col-lg-4">
                            <div class="feature-box">
                                <i class="icon-us-dollar"></i>

                                <div class="feature-box-content">
                                    <h3>Online Payment / Cash on Delivery</h3>
                                    <p>Conveniently choose between secure online payment or opt for cash on delivery to suit your preference when placing your order.</p>
                                </div><!-- End .feature-box-content -->
                            </div><!-- End .feature-box -->
                        </div><!-- End .col-lg-4 -->

                        <div class="col-lg-4">
                            <div class="feature-box">
                                <i class="icon-online-support"></i>

                                <div class="feature-box-content">
                                    <h3>Online Support 24/7</h3>
                                    <p>Access round-the-clock online support to address any queries or concerns whenever you need assistance.</p>
                                </div><!-- End .feature-box-content -->
                            </div><!-- End .feature-box -->
                        </div><!-- End .col-lg-4 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .features-section -->
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