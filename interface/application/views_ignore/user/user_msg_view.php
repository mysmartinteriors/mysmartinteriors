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
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumb1 ?></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumb2 ?></li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="container">
                <div class="user-msg-page">
                    <div class="row">
                        <div class="col-lg-6 offset-3">
                            <div class="msg-content-box">
                                <h2><?php echo $statusHead ?></h2>
                                <p class="<?php echo $status ?>"><?php echo $message ?></p>
                            </div>
                        </div>
                    </div>
                </div><!-- End .container -->
            </div><!-- End .about-section -->

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