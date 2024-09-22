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
    <style>
        .table-borderless td,
        .table-borderless th {
            border: none;
        }
    </style>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <?php echo $header_main ?>
        <?php echo $header_menu ?>
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Customers Referrals</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/customers">Customers</a></li>
                    <li class="breadcrumb-item">Referral View</li>
                </ol>
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title text-danger">Viewing Referral Details of Customer
                                    <?php echo (!empty($customerData) && !empty($customerData['firstName'])) ? ('(' . $customerData['firstName'] . ' ' . $customerData['lastName']) . ')' : '' ?>
                                    Details
                                </div>
                            </div>
                            <div class="ibox-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th rowspan="2">Sl.No</th>
                                                    <th rowspan="2">Level</th>
                                                    <th colspan="2">Customers</th>
                                                </tr>
                                                <!-- <tr>
                                                    <th>Customers Name</th>
                                                </tr> -->
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if (empty($referralData)) { ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Referral Data Found</td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <?php $i = 1;
                                                    foreach ($referralData as $key => $val) {
                                                        if (!empty($val)) { ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $key; ?></td>
                                                                <td colspan="2">
                                                                    <table class="table-borderless" style="width: 100%;">
                                                                        <tbody>
                                                                            <?php $j = 1;
                                                                            foreach ($val as $k => $v) { ?>
                                                                                <tr>
                                                                                    <td style="width: 90%;">
                                                                                        <table>
                                                                                            <tr>
                                                                                                <td style="width: 30%">
                                                                                                    <?php echo '<strong>'.$v['firstName'] . ' ' . $v['lastName'].'</strong>'; ?> 
                                                                                                </td>
                                                                                                <td style="width: 20%">
                                                                                                    <span style="cursor: pointer" data-id="<?php echo $v['id'] ?>" class="subscriptions badge <?php echo ($v['is_subscribed']>0?'bg-success':'bg-danger') ?>"> <?php echo $v['is_subscribed']?> Subscriptions </span> 
                                                                                                </td>
                                                                                                <td style="width: 20%">
                                                                                                    <span> Registered At <?php echo $v['createdDate'] ?> </span> 
                                                                                                </td>
                                                                                                <td  style="width: 20%">
                                                                                                    <button class="tbl-action-btn btn btn-info btn-circle mb-2" onclick="window.location.href='<?php echo base_url().'admin/customers/referrals/'.$v['id'] ?>'" title="View"><i class="ti-eye"></i> View
                                                                                                    </button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                </td>
                                                                                </tr>
                                                                                <?php $j++;
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <?php $i++;
                                                        }
                                                    } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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

            $(".subscriptions").on('click', function (e) {
                e.preventDefault();
                const customerId = $(this).attr('data-id');
                var dataModal = bootbox.dialog({
                    title: "",
                    message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
                    closeButton: true,
                    size: 'large',
                    animate: true,
                    centerVertical: true,
                    className: "userModalView",
                });
                $.post(urljs + 'admin/customers/check_subscriptions', { customerId }, function (response) {
                    if (response.status == 'success') {
                        dataModal.find('.bootbox-body').html(response.message);
                    } else {
                        bootbox.cloaseAll();
                        swal('No detail found', '', 'warning');
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
            // $(".settleRepurchases").on('click', function (e) {
            //     e.preventDefault();
            //     const customerId = $(this).attr('data-userId');
            //     var referralSettlementModal = bootbox.dialog({
            //         title: 'Settle Repurchase comission',
            //         message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
            //         closeButton: true,
            //         size: 'extra-large',
            //         animate: true,
            //         className: "largeWidth",
            //     });
            //     $.post(urljs + 'admin/customers/settleRepurchaseView', { customerId }, function (response) {
            //         if (response.status == 'success') {
            //             referralSettlementModal.find('.bootbox-body').html(response.message);
            //         } else {
            //             bootbox.hideAll();
            //             swal('Failed', response.message, 'warning');
            //         }
            //     }, 'json')
            // })

            // $('#previousMonthsSelect').on('change', function (e) {
            //     e.preventDefault();
            //     var daterange = $(this).val();
            //     const userId = '<?php //echo $customerId ?>';
            //     if (daterange) {
            //         $.post(urljs + 'admin/customers/getReferralBonus', { daterange, userId }, function (response) {
            //             if (response.status == 'success') {
            //                 $("#referralDataView").html(response.message);
            //             } else {
            //                 swal('Fail', 'No Data Found', 'warning');
            //             }
            //         }, 'json')
            //     }
            // });

            // $('#previousMonthsSelectForRepurchase').on('change', function (e) {
            //     e.preventDefault();
            //     var daterange = $(this).val();
            //     const userId = '<?php //echo $customerId ?>';
            //     if (daterange) {
            //         $.post(urljs + 'admin/customers/getRepurchaseBonus', { daterange, userId }, function (response) {
            //             console.log(response);
            //             if (response.status == 'success') {
            //                 $("#repurchaseDataView").html(response.message);
            //             } else {
            //                 swal('Fail', 'No Data Found', 'warning');
            //             }
            //         }, 'json')
            //     }
            // });


            // $(".settleReferenceAmount").on('click', function (e) {
            //     e.preventDefault();
            //     const customerId = '<?php //echo $customerId ?>';
            //     if (customerId) {
            //         $.post(urljs + 'admin/customers/settleReferalAmount', { customerId }, function (response) {
            //             if (response.status == 'success') {
            //                 swal('Success', response.message, response.status);
            //                 setTimeout(() => {
            //                     location.reload();
            //                 }, 3000);
            //             } else {
            //                 swal('Fail', response.message, 'error')
            //             }
            //         }, 'json')
            //     }
            // })

            // $(".settleRepurchaseAmount").on('click', function (e) {
            //     e.preventDefault();
            //     const customerId = '<?php //echo $customerId ?>';
            //     if (customerId) {
            //         $.post(urljs + 'admin/customers/settleRepurchaseAmount', { customerId }, function (response) {
            //             if (response.status == 'success') {
            //                 swal('Success', response.message, response.status);
            //                 setTimeout(() => {
            //                     location.reload();
            //                 }, 3000);
            //             } else {
            //                 swal('Fail', response.message, 'error')
            //             }
            //         }, 'json')
            //     }
            // })
        })
    </script>
</body>

</html>