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
            <div class="page-header page-header-bg" style="background-image: url('<?php echo base_url(); ?>ui/frontend/images/page-header-bg.jpg');">
                <div class="container">
                    <h1><span>Our</span>
                        SERVICES/REPAIRS</h1>
                    <a href="<?php echo base_url()?>contact" class="btn btn-dark">Contact</a>
                </div><!-- End .container -->
            </div><!-- End .page-header -->

            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Services</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="about-section">
                <div class="container">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

                    <p class="lead">“ Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model search for evolved over sometimes by accident, sometimes on purpose ”</p>
                </div><!-- End .container -->
            </div><!-- End .about-section -->

            <div class="testimonials-section">
                <div class="container">
                    <h2 class="subtitle text-center">HAPPY CLIENTS</h2>

                    <div class="testimonials-carousel owl-carousel owl-theme">
                        <div class="testimonial">
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="<?php echo base_url(); ?>ui/frontend//images/clients/client1.png" alt="client">
                                </figure>

                                <div>
                                    <h4 class="testimonial-title">john Smith</h4>
                                    <span>Proto Co Ceo</span>
                                </div>
                            </div><!-- End .testimonial-owner -->

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur elitad adipiscing Cras non placerat mipsum dolor sit amet, consectetur elitad adipiscing.</p>
                            </blockquote>
                        </div><!-- End .testimonial -->

                        <div class="testimonial">
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="<?php echo base_url(); ?>ui/frontend//images/clients/client2.png" alt="client">
                                </figure>

                                <div>
                                    <h4 class="testimonial-title">Bob Smith</h4>
                                    <span>Proto Co Ceo</span>
                                </div>
                            </div><!-- End .testimonial-owner -->

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur elitad adipiscing Cras non placerat mipsum dolor sit amet, consectetur elitad adipiscing.</p>
                            </blockquote>
                        </div><!-- End .testimonial -->

                        <div class="testimonial">
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="<?php echo base_url(); ?>ui/frontend//images/clients/client1.png" alt="client">
                                </figure>

                                <div>
                                    <h4 class="testimonial-title">john Smith</h4>
                                    <span>Proto Co Ceo</span>
                                </div>
                            </div><!-- End .testimonial-owner -->

                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur elitad adipiscing Cras non placerat mipsum dolor sit amet, consectetur elitad adipiscing.</p>
                            </blockquote>
                        </div><!-- End .testimonial -->
                    </div><!-- End .testimonials-slider -->
                </div><!-- End .container -->
            </div><!-- End .testimonials-section -->

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