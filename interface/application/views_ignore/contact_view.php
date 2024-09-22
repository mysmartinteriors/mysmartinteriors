<?php
$name='';
$email='';
$phone='';
// if($userQ){
//   if($userQ->num_rows()>0){
//     foreach ($userQ->result() as $userRow) {
//       $name=$userRow->firstName.' '.$userRow->lastName;
//       $email=$userRow->email;
//       $phone=$userRow->phone;
//     }
//   }
// }
  
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
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Support</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="container">
                     <div class="mb-5"></div>

                <div class="row">
                    <div class="col-md-8">

                    <div class="form-box">
                        <h2 class="light-title">Write to <strong>Support</strong></h2>

                        <form id="contact_support_form" role="form" method="post">
                          <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="contact-name">Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name" value="<?php echo $name ?>">
                            </div><!-- End .form-group -->
                            </div>
                      <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="contact-email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="<?php echo $email ?>">
                            </div><!-- End .form-group -->
                        </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 ">
                            <div class="form-group ">
                            <label for="contact-phone">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo $phone ?>">
                            </div><!-- End .form-group -->
                          </div>
                          <div class="col-md-6 ">
                            <div class="form-group ">
                                <label for="contact-Subject">Subject <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="subject">
                            </div><!-- End .form-group -->
                          </div>
                            </div>
                            <div class="row">
                            <div class="col-md-12 ">
                            <div class="form-group">
                                <label for="contact-message">How can we help you? <span class="text-danger">*</span></label>
                                <textarea   class="form-control" name="message"></textarea>
                            </div><!-- End .form-group -->
                            </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Submitting...">Submit</button>
                            </div><!-- End .form-footer -->
                        </form>
                        </div>
                    </div><!-- End .col-md-8 -->

                    <div class="col-md-4 ">
             

                        <div class="contact-info contact-box ">
                            <h2 class="light-title">Contact <strong>Details</strong></h2>
                            <div>
                              <i class="icon-location"></i>
                              <p><a target="_blank" href="https://google.com/maps/search/13.143353955435991,77.3802448703644">Machonayakanahalli, Nelamangala, Bengaluru, Karnataka-562 123 </a></p>
                            </div>
                            <div>
                                <i class="icon-mobile"></i>
                                <p><a href="tel:">+91-8762463738</a></p>
                            </div>
                            <div>
                                <i class="icon-mail-alt"></i>
                                <p><a href="mailto:#">nalaaorganic@gmail.com</a></p>
                            </div>
                 
                        </div><!-- End .contact-info -->
                    </div><!-- End .col-md-4 -->
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

   </body>
</html>