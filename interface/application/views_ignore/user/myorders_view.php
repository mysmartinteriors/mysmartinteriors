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
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="<?php echo base_url() ?>account">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                    </ol>
                </div><!-- End .container -->
            </nav>
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-lg-3 order-sm-1 ">
                        <div class="widget widget-dashboard">
                            <ul class="list">
                                <li><a href="<?php echo base_url() ?>account/dashboard">Dashboard</a></li>
                                <li><a href="<?php echo base_url() ?>account/myprofile">Edit Profile </a></li>
                                <li class="active"><a href="<?php echo base_url() ?>account/myorders">My Orders</a></li>
                                <li><a href="<?php echo base_url() ?>account/myaddresses">Address List</a></li>
                                <li><a href="<?php echo base_url() ?>account/wallets">Wallet Purchase</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url() ?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9  dashboard-content order-md-12 order-sm-12 ">
                        <h2>My Orders</h2>
                        <div class="mb-2"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="user-single-tabs">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="user-book-sec" data-toggle="tab"
                                                href="#bookings" role="tab" aria-controls="bookings"
                                                aria-selected="true">Bookings</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-2">
                                        <div class="tab-pane fade show active" id="bookings" role="tabpanel"
                                            aria-labelledby="user-book-sec">
                                            <div class="bookings">
                                                <?php if ($bookOrderQ['status'] && !empty ($bookOrderQ['data_list'])) { ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Sl. No.</th>
                                                                    <th>Order ID</th>
                                                                    <th>Date</th>
                                                                    <th>Delivery Status</th>
                                                                    <th>Payment Status</th>
                                                                    <th>Total</th>
                                                                    <th>View</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($bookOrderQ['data_list'] as $bookings) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $i ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $bookings['orderId'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s", $bookings['createdDate']); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($bookings['status'] == 26) { ?>
                                                                                <span
                                                                                    class="badge badge-danger badge-pill">Cancelled</span>
                                                                            <?php }
                                                                            if ($bookings['status'] == 25) { ?>
                                                                                <span
                                                                                    class="badge badge-danger badge-pill">Pending</span>
                                                                            <?php } else if ($bookings['status'] == 27) { ?>
                                                                                    <span
                                                                                        class="badge badge-info badge-pill">Dispatched</span>
                                                                            <?php } else if ($bookings['status'] == 28) { ?>
                                                                                        <span
                                                                                            class="badge badge-success badge-pill">Completed
                                                                                            / Delivered</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($bookings['pay_status'] == 1) { ?>
                                                                                <span
                                                                                    class="badge badge-success badge-pill">Paid</span>
                                                                            <?php } else { ?>
                                                                                    <span
                                                                                        class="badge badge-warning badge-pill">Pending</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo '<i class="fa fa-inr"></i>' . $bookings['actualAmountToPay'] . ' for ' . $bookings['total_items'] . ' item(s)' ?>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-actions-sm edit-action btn-edit btn-getBookData"
                                                                                data-id="<?php echo $bookings['id'] ?>"
                                                                                data-status="<?php echo $bookings['status'] ?>"><i
                                                                                    class="fa fa-search-plus"></i></button>

                                                                            <button
                                                                                class="btn btn-actions-sm edit-action btn-edit"
                                                                                onclick="window.open('<?php echo base_url() ?>account/pdf/<?php echo $bookings['id'] ?>')"
                                                                                title="Generate Invoice">
                                                                                <i class="fa fa-print"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $i++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <p class="text-center">You don't have any orders!<br><a
                                                            href="<?php echo base_url() ?>Products">Shop now</a> </p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="orders" role="tabpanel"
                                            aria-labelledby="user-order-sec">
                                            <div class="orders">
                                                <p class="text-center">You don't have any orders!<br><a
                                                    href="<?php echo base_url() ?>Products">Shop now</a> </p>
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
        $(".pay-nowBtn").on('click', function(e){
            e.preventDefault();
            const orderid = $(this).attr('data-orderId');
            // console.log(urljs)
            const url=`${urljs}checkout/payorderamount?oId=${orderid}`
            location.href=url
        })
    </script>

</body>

</html>