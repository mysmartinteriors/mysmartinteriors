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
    <div class="page-wrapper">
    <?php echo $header_main ?>
    <input type="hidden" id="latLongVal" name="addresLatLong">
        <main class="main">
            <div class="mb-1"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 order-1 mb-2 mb-md-0">
                        <section class="products_sec">
                        <div class="row application-for-online-registration">
                            <div class="col-md-6 offset-3">
                                <div class="card-box text-center">
                                    <h5>Payment status is <?php echo $order_status ?></h5>
                                    <p><?php echo $message ?></p>
                                    <p>You'll be redirect to the homepage in <span id="timeoutTime"></span> Seconds. If notghing happens, <a href="<?php echo base_url() ?>">Click Here</a></p>
                                </div>
                            </div>
                        </div>
                        </section>
                    </div>
                </div>
 
            </div><!-- End .container -->
            <div class="mb-5"></div>
        </main><!-- End .main -->
        <!-- End .main -->
        <?php echo $footer ?>
        <!-- End .footer -->
    </div>
    <?php echo $mobile_menu ?>
    <?php echo $commonJs ?>

    <script>
        let timeoutSeconds = 8;
        setInterval(() => {
            $("#timeoutTime").html(timeoutSeconds);
            timeoutSeconds--;
            if(timeoutSeconds<1){
                location.href = '<?php echo base_url() ?>';
            }
        }, 1000);
    </script>

</body>

</html>