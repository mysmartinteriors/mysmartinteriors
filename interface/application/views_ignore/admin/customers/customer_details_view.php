<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>
        <?php echo $title ?>
    </title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <?php echo $header_main ?>
        <?php echo $header_menu ?>
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Customers</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/customers">Customers</a></li>
                    <li class="breadcrumb-item">Details</li>
                </ol>
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">View Customer
                                    <?php echo (!empty($customerQ) && !empty($customerQ['firstName'])) ? ('(' . $customerQ['firstName'] . ' ' . $customerQ['lastName']) . ')' : '' ?>
                                    Details
                                </div>
                            </div>
                            <div class="ibox-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="text-primary" style="text-decoration: underline;">Referral Bonus
                                            OverView</h4>
                                            <table class="table table-bordered">
                                            <thead>
                                                <th>Level</th>
                                                <th>Previous Month Bonus</th>
                                                <th>Current Month Bonus</th>
                                                <th>Overall Bonus</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $totalPreviousMonthBonus = 0;
                                                $totalCurrentMonthBonus = 0;
                                                $totalOverallBonus = 0;
                                                $apidata = $this->curl->execute('customer_reference_amount/level_wise_customer_reference/' . $customerQ['id'], 'GET');
                                                if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                                                    foreach ($apidata['data_list'] as $repurchaseData) {
                                                        $prev_month_amount = $repurchaseData['previous_month_amount']?$repurchaseData['previous_month_amount']:0; 
                                                        $prev_month_count = $repurchaseData['previous_month_count']?$repurchaseData['previous_month_count']:0;
                                                        $totalPreviousMonthBonus += ($prev_month_amount);
                                                        $cur_month_amount = $repurchaseData['current_month_amount']?$repurchaseData['current_month_amount']:0; 
                                                        $cur_month_count = $repurchaseData['current_month_count']?$repurchaseData['current_month_count']:0;
                                                        $totalCurrentMonthBonus += ($cur_month_amount);
                                                        $overall_amount = $repurchaseData['total_amount']?$repurchaseData['total_amount']:0; 
                                                        $totalOverallBonus += ($overall_amount);
                                                        ?>

                                                        <tr>
                                                            <td>
                                                                <?php echo $repurchaseData['level'] ?>
                                                            </td>
                                                            <td>
                                                            <?php echo 'Total Referrals :'. $prev_month_count.'<br>' ?>  &#8377; <?php echo round($prev_month_amount, 1) ?>
                                                            </td>
                                                            <td>
                                                            <?php echo 'Total Referrals :'. $cur_month_count.'<br>' ?>  &#8377; <?php echo round($cur_month_amount, 1) ?>
                                                            </td>
                                                            <td> &#8377; <?php echo round($repurchaseData['total_amount'], 1) ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>
                                                            &#8377; <?php echo round($totalPreviousMonthBonus, 1) ?>
                                                        </td>
                                                        <td>
                                                            &#8377; <?php echo round($totalCurrentMonthBonus, 1) ?>
                                                        </td>
                                                        <td>
                                                            &#8377; <?php echo round($totalOverallBonus, 1) ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($totalPreviousMonthBonus > 0) { ?>
                                                        <tr>
                                                            <?php if($customer_reference_last_month['status']=='success'){ ?>
                                                            <td colspan="4" class="text-right">
                                                                <button class="btn btn-success settleReferenceAmount"
                                                                    data-id="<?php echo $customerQ['id'] ?>">Settle Previous Month Reference
                                                                    Amount</button>
                                                            </td>
                                                            <?php }else{ ?>
                                                                <td colspan="4" class="text-right">
                                                                <button class="btn btn-success" disabled
                                                                    data-id="<?php echo $customerQ['id'] ?>">Settled</button>
                                                            </td> 
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4">No Reference Amount</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <h4 class="text-primary" style="text-decoration: underline;">Repurchase Bonus
                                            OverView</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Level</th>
                                                <th>Previous Month Bonus</th>
                                                <th>Current Month Bonus</th>
                                                <th>Overall Bonus</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $totalPreviousMonthBonus = 0;
                                                $totalCurrentMonthBonus = 0;
                                                $totalOverallBonus = 0;
                                                $apidata = $this->curl->execute('customer_repurchase_amount/level_wise_customer_repurchase/' . $customerQ['id'], 'GET');
                                                // print_R($apidata);
                                                // echo "<hr>";
                                                if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                                                    foreach ($apidata['data_list'] as $repurchaseData) {
                                                        // echo 'LEVEL - '.$repurchaseData['level'].' --- '.$repurchaseData['current_month_count']. ' * '.$repurchaseData['current_month_amount'];
                                                        // echo "<hr>";
                                                        $prev_month_amount = $repurchaseData['previous_month_amount']?$repurchaseData['previous_month_amount']:0; 
                                                        $prev_month_count = $repurchaseData['previous_month_count']?$repurchaseData['previous_month_count']:0;
                                                        $totalPreviousMonthBonus += ($prev_month_amount);
                                                        $cur_month_amount = $repurchaseData['current_month_amount']?$repurchaseData['current_month_amount']:0; 
                                                        $cur_month_count = $repurchaseData['current_month_count']?$repurchaseData['current_month_count']:0;
                                                        $totalCurrentMonthBonus += ($cur_month_amount);
                                                        //echo "Current Month Bonus ".($cur_month_amount * $cur_month_count);
                                                        //echo "<hr>";
                                                        $overall_amount = $repurchaseData['total_amount']?$repurchaseData['total_amount']:0; 
                                                        $totalOverallBonus += ($overall_amount);
                                                        ?>

                                                        <tr>
                                                            <td>
                                                                <?php echo $repurchaseData['level'] ?>
                                                            </td>
                                                            <td>
                                                            <?php echo "Total Bonuses : ".$prev_month_count. ' <br>' ?>  &#8377; <?php echo round($prev_month_amount, 1) ?>
                                                            </td>
                                                            <td>
                                                            <?php echo "Total Bonuses :" .$cur_month_count. ' <br>' ?> &#8377; <?php echo round($cur_month_amount, 1) ?>
                                                            </td>
                                                            <td> &#8377; <?php echo round($repurchaseData['total_amount'], 1) ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php //echo "Total Cur Month Bonus ".$totalCurrentMonthBonus; ?>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>
                                                            &#8377; <?php echo round($totalPreviousMonthBonus, 1) ?>
                                                        </td>
                                                        <td>
                                                            &#8377; <?php echo round($totalCurrentMonthBonus, 1) ?>
                                                        </td>
                                                        <td>
                                                            &#8377; <?php echo round($totalOverallBonus, 1) ?>
                                                        </td>
                                                    </tr>
                                                    <?php //print_r($totalPreviousMonthBonus); echo "<hr>"; ?>
                                                    <?php if ($totalPreviousMonthBonus > 0) { ?>
                                                        <tr>
                                                            <?php if($customer_repurchase_last_month['status']=='success' && !empty($customer_repurchase_last_month['data_list'])){ ?>
                                                            <td colspan="4" class="text-right">
                                                                <button class="btn btn-success settleRepurchaseAmount"
                                                                    data-id="<?php echo $customerQ['id'] ?>">Settle Previous Month Repurchase
                                                                    Amount</button>
                                                            </td>
                                                            <?php }else{ ?>
                                                                <td colspan="4" class="text-right">
                                                                <button class="btn btn-success" disabled
                                                                    data-id="<?php echo $customerQ['id'] ?>">Settled</button>
                                                            </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="2">No Repurchase Amount</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-primary" style="text-decoration: underline;">Wallet Purchase
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card text-center">
                                                    <div class="card-header">Wallet Purchase</div>
                                                    <div class="card-body" style="font-size:30px">
                                                        <?php echo $customerQ['subscriptionAmount'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card text-center">
                                                    <div class="card-header">Wallet Purchase Bonus</div>
                                                    <div class="card-body" style="font-size:30px">
                                                        <?php echo $customerQ['subscriptionPoints'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h4 class="text-primary" style="text-decoration: underline;">Order Details</h4>
                                        <div class="tab-pane fade show active" id="orders" role="tabpanel"
                                            aria-labelledby="user-order-sec">
                                            <div class="orders">
                                                <?php if ($orders['status'] == 'success' && !empty($orders['data_list'])) { ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Sl. No.</th>
                                                                    <th>Order ID</th>
                                                                    <th>Delivery Address</th>
                                                                    <th>Products</th>
                                                                    <th>Ordered AT</th>
                                                                    <th>Payment Status</th>
                                                                    <th>Delivery Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($orders['data_list'] as $ordersData) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $i ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $ordersData['orderId'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $ordersData['deliveryAddress'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <button
                                                                                class="tbl-action-btn btn btn-info btn-viewProducts"
                                                                                data-toggle="tooltip" title="View Products"
                                                                                data-id="<?php echo $ordersData['id'] ?>">
                                                                                <i class="fa fa-eye"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>
                                                                            <?php if (!empty($ordersData['createdDate'])) {
                                                                                echo custom_date('d-M-Y h:i A', $ordersData['createdDate']);
                                                                            } else {
                                                                                echo '---';
                                                                            } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            if (!empty($ordersData['pay_status']) && $ordersData['pay_status'] == 1) { ?>
                                                                                <span class="badge bg-success">Paid</span>
                                                                            <?php } else { ?>
                                                                                <span class="badge bg-success">Pending</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo '<span class="' . $ordersData['status_color_name'] . '">' . $ordersData['status_name'] . '</span>' ?>
                                                                            <?php if (!empty($ordersData['comments'])) {
                                                                                echo $ordersData['comments'];
                                                                            } ?>
                                                                        </td>
                                                                        <td>
                                                                            <div class="tbl-action-holder">
                                                                                <button
                                                                                    class="tbl-action-btn btn btn-purple mt-2"
                                                                                    onclick="window.open('<?php echo base_url() ?>admin/orders/pdf/<?php echo $ordersData['id'] ?>')"
                                                                                    title="Generate Invoice">
                                                                                    View Invoice
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $i++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php
                                                } else { ?>
                                                    <p class="text-center">Customer Doesn't have any orders!</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- End .col-lg-9 -->
                        </div><!-- End .row -->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
    <?php echo $footer ?>
    </div>
    </div>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/customers.js"></script>

    <script>
        $(document).ready(function () {

            $(".btn-viewProducts").on('click', function (e) {
                e.preventDefault();
                const orderId = $(this).attr('data-id');
                var dataModal = bootbox.dialog({
                    title: "",
                    message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
                    closeButton: true,
                    size: 'large',
                    animate: true,
                    centerVertical: true,
                    className: "userModalView",
                });
                $.post(urljs + 'admin/orders/productDetails', { orderId }, function (response) {
                    if (response.status == 'success') {
                        dataModal.find('.bootbox-body').html(response.message);
                    } else {
                        bootbox.cloaseAll();
                        swal('No Products Found for the order', '', 'warning');
                    }
                }, 'json')
            })

            $(".settleReferrals").on('click', function (e) {
                e.preventDefault();
                const customerId = $(this).attr('data-userId');
                var referralSettlementModal = bootbox.dialog({
                    title: 'Settle Referral comission',
                    message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
                    closeButton: true,
                    size: 'extra-large',
                    animate: true,
                    className: "largeWidth",
                });
                $.post(urljs + 'admin/customers/settleReferralsView', { customerId }, function (response) {
                    if (response.status == 'success') {
                        referralSettlementModal.find('.bootbox-body').html(response.message);
                    } else {
                        bootbox.hideAll();
                        swal('Failed', response.message, 'warning');
                    }
                }, 'json')
            })
            $(".settleRepurchases").on('click', function (e) {
                e.preventDefault();
                const customerId = $(this).attr('data-userId');
                var referralSettlementModal = bootbox.dialog({
                    title: 'Settle Repurchase comission',
                    message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
                    closeButton: true,
                    size: 'extra-large',
                    animate: true,
                    className: "largeWidth",
                });
                $.post(urljs + 'admin/customers/settleRepurchaseView', { customerId }, function (response) {
                    if (response.status == 'success') {
                        referralSettlementModal.find('.bootbox-body').html(response.message);
                    } else {
                        bootbox.hideAll();
                        swal('Failed', response.message, 'warning');
                    }
                }, 'json')
            })

            $('#previousMonthsSelect').on('change', function (e) {
                e.preventDefault();
                var daterange = $(this).val();
                const userId = '<?php echo $customerId ?>';
                if (daterange) {
                    $.post(urljs + 'admin/customers/getReferralBonus', { daterange, userId }, function (response) {
                        if (response.status == 'success') {
                            $("#referralDataView").html(response.message);
                        } else {
                            swal('Fail', 'No Data Found', 'warning');
                        }
                    }, 'json')
                }
            });

            $('#previousMonthsSelectForRepurchase').on('change', function (e) {
                e.preventDefault();
                var daterange = $(this).val();
                const userId = '<?php echo $customerId ?>';
                if (daterange) {
                    $.post(urljs + 'admin/customers/getRepurchaseBonus', { daterange, userId }, function (response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $("#repurchaseDataView").html(response.message);
                        } else {
                            swal('Fail', 'No Data Found', 'warning');
                        }
                    }, 'json')
                }
            });


            $(".settleReferenceAmount").on('click', function (e) {
                e.preventDefault();
                const customerId = '<?php echo $customerId ?>';
                if (customerId) {
                    $.post(urljs + 'admin/customers/settleReferalAmount', { customerId }, function (response) {
                        if (response.status == 'success') {
                            swal('Success', response.message, response.status);
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            swal('Fail', response.message, 'error')
                        }
                    }, 'json')
                }
            })

            $(".settleRepurchaseAmount").on('click', function (e) {
                e.preventDefault();
                const customerId = '<?php echo $customerId ?>';
                if (customerId) {
                    $.post(urljs + 'admin/customers/settleRepurchaseAmount', { customerId }, function (response) {
                        if (response.status == 'success') {
                            swal('Success', response.message, response.status);
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            swal('Fail', response.message, 'error')
                        }
                    }, 'json')
                }
            })
        })
    </script>
</body>

</html>