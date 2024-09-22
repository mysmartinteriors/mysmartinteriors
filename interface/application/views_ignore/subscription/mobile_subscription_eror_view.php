<?php
$name = '';
$email = '';
$phone = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>
      <?php echo $title ?>
   </title>
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
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="icon-home"></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">Subscription & Plans</li>
               </ol>
            </div><!-- End .container -->
         </nav>

         <div class="container">
            <div class="mb-5"></div>
            <div class="row">
                <div class="col-12">
                    <span class="alert bg-danger">We couldn't verify you. please try again later. <br> You can close this window.</span>
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


   <script>
      $(document).ready(function(){
         $(".subscribePlanBtn").on('click', function(e){
            e.preventDefault();
            const subscriptionId = $(this).attr('data-id');
            location.href = urljs+'subscription/subscribe?subscriptionId='+subscriptionId;
         })
      })
   </script>

</body>

</html>