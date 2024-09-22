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
    <style>
        .payment_options-card{
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding:15px;   
        }
        .payment_option{
            border-bottom:1px solid #d3d3d3;
        }
    </style>
</head>

<body>
    <?php //echo $header_main ?>
        <main class="main">
            <div class="mb-1"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 order-1 mb-2 mb-md-0">
                        <section class="products_sec">
                        <div class="row application-for-online-registration">
                            <div class="col-md-6">
                                <div class="card-box text-center">
                                    <h5>Payment status is <?php echo $order_status ?></h5>
                                    <p><?php echo $message ?></p>
                                    <p>You can close this window now. Thank You.</p>
                                    <!-- <a href="myapp://">Open MyApp</a> -->
                                    <!-- <button class="btn btn-success" id="closeBrowser">Open MyApp Subscription Page</a> -->

                                </div>
                            </div>
                        </div>
                        </section>
                    </div>
                </div>
 
            </div><!-- End .container -->
        </main><!-- End .main -->
        <!-- End .main -->
        <?php //echo $footer ?>
        <!-- End .footer -->
    <?php //echo $mobile_menu ?>
    <?php echo $commonJs ?>

    <script>
        // $("#closeBrowser").on('click', function(){
        //     // alert(window)
        //     window.location.href="myapp://close"
        // })
    </script>
</body>

</html>