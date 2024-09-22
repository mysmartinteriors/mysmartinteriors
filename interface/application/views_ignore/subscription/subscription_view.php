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
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <style>
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 1% auto;
            padding: 5px;
            border: 1px solid #888;
            width: 25%;
            border-radius: 10px;
        }

        @media only screen and (max-width: 600px) {
            .modal-content {
                background-color: #fefefe;
                margin: 1% auto;
                padding: 5px;
                border: 1px solid #888;
                width: 100%;
                border-radius: 10px;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription & Plans</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="container">
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div id="checkoutView"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="mb-5"></div>
                <div class="row">
                    <?php if ($subscriptionQ['status'] == 'success' && !empty($subscriptionQ['data_list'])) { ?>
                        <?php foreach ($subscriptionQ['data_list'] as $subscription) { ?>
                            <div class="col-md-4">
                                <div class="card subscription-card">
                                    <div class="card-body">
                                        <?php if (!empty($subscription['name'])) { ?>
                                            <h2 class="card-title"><?php echo strtoupper($subscription['name']) ?>
                                                <?php echo (!empty($customer_subscription_data) && !empty(get_userId()) && ($subscription['id'] == $customer_subscription_data['subscription_id'])) ? '<span class="badge badge-success">Active</span>' : '' ?>
                                            </h2>
                                        <?php } ?>
                                        <h6 class="card-subtitle mb-2 text-muted">Extra Wallet Points</h6>
                                        <h2 class="price">&#8377; <?php echo $subscription['basic_amount'] ?></h2>
                                        <ul class="features">
                                            <li>Get <?php echo $subscription['wallet_points'] ?> extra Wallet Points</li>
                                            <li>Get 10% discounts on every transaction from the Wallet Points once the wallet
                                                amount is over</li>
                                        </ul>
                                        <button type="button" class="btn btn-primary subscribePlanBtn" <?php //echo (!empty($customer_subscription_data) && !empty(get_userId()))?'disabled':'' ?>         <?php echo !empty(get_userId()) ? '' : 'disabled' ?>
                                            data-walletPoints="<?php echo $subscription['wallet_points'] ?>"
                                            data-userId="<?php echo get_userId(); ?>"
                                            data-id="<?php echo $subscription['id'] ?>"
                                            data-amount="<?php echo $subscription['basic_amount'] ?>">
                                            <?php
                                            // if(!empty($customer_subscription_data) && !empty(get_userId() )) {
                                            //    echo 'Subscription activated';
                                            // }else{
                                            echo !empty(get_userId()) ? 'Subscribe Now' : 'Login to Subscribe';
                                            // }
                                            ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
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
        $(document).ready(function () {
            $(".subscribePlanBtn").on('click', function (e) {
                e.preventDefault();
                const subscriptionId = $(this).attr('data-id');
                initiatePayment(subscriptionId);
            })

            const modal = document.getElementById("paymentModal");
            const span = document.getElementsByClassName("close")[0];

            span.onclick = function () {
                modal.style.display = "none";
                swal_alert('Payment Process Cancelled', 'You have closed the payment modal.', 'warning', 'subscription');
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    swal_alert('Payment Process Cancelled', 'You have closed the payment modal.', 'warning', 'subscription');
                }
            }
        });

        function initiatePayment(subscriptionId) {
            ajaxloading();
            processPayment(subscriptionId).then(paymentDetails => {
                closeajax();
                if (paymentDetails.status === 'success' && paymentDetails.sessionId.length && paymentDetails.orderId.length) {
                    try {
                        const cashfree = Cashfree({
                            // mode: "sandbox",
                            mode: "production",
                        });
                        let checkoutOptions = {
                            paymentSessionId: `${paymentDetails.sessionId}`,
                            redirectTarget: document.getElementById("checkoutView"),
                            appearance: {
                                width: "100%",
                                height: "550px",
                            },
                        };
                        // Open the modal
                        const modal = document.getElementById("paymentModal");
                        modal.style.display = "block";
                        cashfree.checkout(checkoutOptions).then((result) => {
                            if (result.error) {
                                modal.style.display = "none"; // Close the modal in case of error
                                swal_alert('Payment Process Cancelled', 'There was an error during payment.', 'error', 'subscription');
                            }
                            if (result.paymentDetails) {
                                $.post(urljs + 'subscription/check_payment_status', { orderId: paymentDetails.orderId, subscriptionId: paymentDetails.subscriptionId }, function (paymentStatus) {
                                    if (paymentStatus.status == 'success') {
                                        const formdata = new FormData();
                                        formdata.append('paymentDetails', JSON.stringify(paymentStatus));
                                        formdata.append('subscriptionId', subscriptionId);
                                        sendAjaxRequest(formdata);
                                        modal.style.display = "none"; // Close the modal after successful payment
                                    } else {
                                        swal_alert('Network Error during payment, Please Try again Later', 'payment failed', 'error', 'subscription');
                                        modal.style.display = "none"; // Close the modal if payment status check fails
                                    }
                                }, 'json');
                            } else {
                                swal_alert('Network Error during payment, Please Try again Later', 'payment failed', 'error', 'subscription');
                                modal.style.display = "none"; // Close the modal if there are no payment details
                            }
                        });
                    } catch (error) {
                        console.log(error);
                        swal_alert('Network Error, Please Try again Later', 'payment failed', 'error', 'subscription')
                    }
                } else {
                    swal_alert('Failed', 'Payment failed', 'error', 'subscription');
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                }
            }).catch(error => {
                console.log(error);
                swal_alert('Failed', 'Payment processing error', 'error', 'subscription');
            });
        }

        function sendAjaxRequest(formdata) {
            $.ajax({
                url: urljs + "subscription/save_subscription",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == 'success') {
                        swal_alert('Success', data.message, 'success', 'subscription');
                    } else {
                        swal_alert('Error', data.message, 'error', 'subscription')
                    }
                },
                error: function () {
                    closeajax();
                }
            });
        }

        function processPayment(subscriptionId) {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('subscriptionId', subscriptionId);
                $.ajax({
                    url: urljs + "subscription/initiate", // Replace with your payment initiation endpoint
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        if (data.status == 'success') {
                            resolve(data.paymentDetails);
                        } else {
                            reject(data.message);
                        }
                    },
                    error: function (error) {
                        closeajax();
                        reject('Payment initiation failed');
                    }
                });
            });
        }

    </script>

</body>

</html>