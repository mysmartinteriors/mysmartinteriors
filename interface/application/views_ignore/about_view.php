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
            <div class="page-header page-header-bg" style="background-image: url('<?php echo base_url(); ?>ui/frontend/images/about.jpg');">
                <div class="container">
                    <h1><span>ABOUT US</span>
                        OUR PRODUCTS</h1>
                    <a href="<?php echo base_url()?>Products" class="btn btn-dark">SHOP NOW</a>
                </div><!-- End .container -->
            </div><!-- End .page-header -->

            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">About Us</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="about-section">
                <div class="container">
                    <h2 class="subtitle">WHAT IS NALAA ORGANIC</h2>
                    <ul>
                        <li>Under NALAA ORGANIC.COM, We guide our civilians about the purchase and usage of organic fruits and vegetables from the farmers directly.</li>
                        <li>Our aim and purpose are to produce healthy food and supply to our lovely consumers.</li>
                        <li>Our First preference is to supply the super speciality Qualitative food items and maintain the Same.</li>
                    </ul>
                </div><!-- End .container -->
                <div class="container">
                    <h2 class="subtitle">ABOUT NALAA ORGANIC</h2>
                    <ul>
                        <li>Our purposes are to maintain the good health and hygiene.</li>
                        <li>We convert the farmers from Chemical farming to Organic agriculture system to maintain Fertile Lands.</li>
                        <li>From this Farmers, we can achieve and create peaceful environment and eco-friendly among the people.</li>
                        <li>There will be an orientation training programs to the deserving farmers regarding to retain the fertile land and to grow good crops, fruits, vegetables etc, and get excellent results.</li>
                    </ul>
                </div><!-- End .container -->
                <div class="container">
                    <h2 class="subtitle">CERTIFICATION</h2>
                    <p>We the authority of “BHOOMITHAAYI SAVAYAVA KRUSHI BELEGAARARA SANGHA(R)” has registered and obtained “REGISTRATION CERTIFICATE” from the “APPEDA”. This is to certify that the product(S) and area(S) of the mentioned organization inspected by Reliable organization certification organization(ROCO) are in accordance with requirements of India’s National Programme for organic production standards.</p>
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
            <?php if($testimonialQ['status'] == 'success' && !empty($testimonialQ['data_list'])){ ?>
                <div class="testimonials-section">
                    <div class="container">
                        <h2 class="subtitle text-center">HAPPY CLIENTS</h2>

                        <div class="testimonials-carousel owl-carousel owl-theme">
                            <?php
                                $s=1;
                                foreach ($testimonialQ['data_list'] as $testimonials) {
                            ?>
                            <div class="testimonial">
                                <div class="testimonial-owner">
                                    <figure>
                                        <?php if(!empty($testimonials['image'])){ ?>
                                             <img src="<?php echo base_url(); ?>uploads/testimonials/<?php echo $testimonials['image'] ?>" alt="">
                                         <?php }else{ ?>
                                             <img src="<?php echo base_url(); ?>ui/images/default_avathar.jpg" alt="">
                                          <?php } ?>
                                    </figure>

                                    <div>
                                        <h4 class="testimonial-title"><?php echo $testimonials['name'] ?>h</h4>
                                    </div>
                                </div><!-- End .testimonial-owner -->

                                <blockquote>
                                    <p><?php echo $testimonials['description'] ?></p>
                                </blockquote>
                            </div><!-- End .testimonial -->
                            <?php } ?>  
                        </div><!-- End .testimonials-slider -->
                    </div><!-- End .container -->
                </div><!-- End .testimonials-section -->
            <?php } ?>
            <div class="counters-section">
                <div class="container">
                    <div class="row">
                        <div class="col-6 col-md-4 count-container">
                            <div class="count-wrapper">
                                <span class="count" data-from="0" data-to="200" data-speed="2000" data-refresh-interval="50">200</span>+
                            </div><!-- End .count-wrapper -->
                            <h4 class="count-title">MILLION CUSTOMERS</h4>
                        </div><!-- End .col-md-4 -->

                        <div class="col-6 col-md-4 count-container">
                            <div class="count-wrapper">
                                <span class="count" data-from="0" data-to="1800" data-speed="2000" data-refresh-interval="50">1800</span>+
                            </div><!-- End .count-wrapper -->
                            <h4 class="count-title">TEAM MEMBERS</h4>
                        </div><!-- End .col-md-4 -->

                        <div class="col-6 col-md-4 count-container">
                            <div class="count-wrapper">
                                <span class="count" data-from="0" data-to="24" data-speed="2000" data-refresh-interval="50">24</span><span>HR</span>
                            </div><!-- End .count-wrapper -->
                            <h4 class="count-title">SUPPORT AVAILABLE</h4>
                        </div><!-- End .col-md-4 -->

                        <div class="col-6 col-md-4 count-container">
                            <div class="count-wrapper">
                                <span class="count" data-from="0" data-to="265" data-speed="2000" data-refresh-interval="50">265</span>+
                            </div><!-- End .count-wrapper -->
                            <h4 class="count-title">SUPPORT AVAILABLE</h4>
                        </div><!-- End .col-md-4 -->

                        <div class="col-6 col-md-4 count-container">
                            <div class="count-wrapper">
                                <span class="count" data-from="0" data-to="99" data-speed="2000" data-refresh-interval="50">99</span><span>%</span>
                            </div><!-- End .count-wrapper -->
                            <h4 class="count-title">SUPPORT AVAILABLE</h4>
                        </div><!-- End .col-md-4 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .counters-section -->
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