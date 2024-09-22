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
        .ui-widget-header {
            color: #333 !important;
        }

        .payment_options-card {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding: 15px;
        }

        .payment_option {
            border-bottom: 1px solid #d3d3d3;
        }

        .order_box {
            padding: 15px 0;
            border-bottom: 1px solid #d3d3d3;
        }

        .order_box:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .order_box:last-child h2 {
            margin-bottom: 0;
        }
    </style>
</head>


<body>
    <div class="page-wrapper">
        <?php echo $header_main ?>
        <input type="hidden" name="latlong" value="" class="latlong">
        <input type="hidden" name="selectedAddressId" value="" class="selectedAddressId">
        <input type="hidden" id="deliveryDate" name="deliveryDate">

        <main class="main">
            <div class="mb-1"></div>
            <?php if (!empty($orderTotal)) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="checkout-forms" id="<?php echo $tblId ?>"></div>
                                </div>
                                <div class="col-md-8 mb-2 p-2 payment_options-card">
                                    <h3>Choose Delivery Address</h3>
                                    <select name="address" id="selectaddress" class="form-control">
                                        <?php
                                        $active = '';
                                        $selectedValue = '';
                                        if ($addressQ['status'] == 'success' && !empty($addressQ['data_list'])) {
                                            foreach ($addressQ['data_list'] as $addressData) {
                                                if ($addressData['pri_address'] == 1) {
                                                    $active = 'active selectedAddress';
                                                    $selectedValue = $addressData['id'];
                                                } else {
                                                    $active = '';
                                                }
                                                ?>
                                                <option value="<?php echo $addressData['id'] ?>" data-latitude="<?php echo $addressData['latitude'] ?>" data-longitude="<?php echo $addressData['longitude'] ?>" <?php echo (!empty($selectedValue) && $selectedValue == $addressData['id']) ? 'selected' : '' ?>>
                                                    <?php echo $addressData['phone'] . ', ' . $addressData['name'] . ', ' . $addressData['apartment'] . ', ' . $addressData['address'] . ', ' . $addressData['city'] . ', ' . $addressData['state'] . ' - ' . $addressData['postalCode'] . ', ' . $addressData['country'] ?>
                                                </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <h3>Please place your address in the map</h3>
                                    <div id="map" style="height: 200px;"></div>
                                    <div class="col-md-12 px-2">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2 p-2 payment_options-card">
                                    <h3>Choose Delivery Date</h3>
                                    <div id="customdatepicker"></div>
                                </div>

                                <?php if ($orderTotal['availableWalletAmountDeduction'] > 0) { ?>
                                    <div class="col-12 mb-1 payment_options-card useWalletAmountDiv">
                                        <!-- <h3>User Wallet Purchase Amount</h3> -->
                                        <?php if ($userData['status'] == 'success' && !empty($userData['data_list']) && ($userData['data_list']['subscriptionAmount'] > 0 || $userData['data_list']['subscriptionPoints'] > 0)) { ?>
                                            <div class="d-flex align-items-center p-2 justify-content-between">
                                                <div class="">
                                                    <input value="yes" class="form-check-input mr-2" type="checkbox"
                                                        data-amountAfterWalletDeduction="<?php echo $orderTotal['productAmountAfterWalletAmountDeductionWithoutWalletPoints'] ?>"
                                                        data-amountWithoutWalletDiscount="<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>"
                                                        name="wallet_deduction" id="walletDeduction">
                                                    <label class="form-check-label ml-3">
                                                        <span>Use Wallet Amount &#8377;</span><span
                                                            id="deductableAmount"><?php echo $orderTotal['availableDeductWalletAmountWithoutWalletPointsDeduction'] ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-12 mb-1 payment_options-card">
                                        <h3 class="text-danger">No Wallet Amount In Your Account. Please <a
                                                href="<?php echo base_url() ?>subscription">click here</a> to add amount</h3>
                                    </div>
                                <?php } ?>
                                    <div class="col-12 mb-2 p-2 payment_options-card">
                                        <div id="checkoutView"></div>
                                    </div>
                                <div class="col-md-12 mb-2 p-2 payment_options-card" style="padding: 24px !important;">
                                    <h3>Payment Options</h3>
                                    <form id="checkout_addrform" class='m-0'>
                                        <input type="hidden" name="deduct_wallet_amount" id="deduct_wallet_amount"
                                            value="no">
                                        <input type="hidden" name="deduct_wallet_bonus" id="deduct_wallet_bonus" value="no">
                                        <div class="row">
                                            <div class="col-12" id="paymentOptionsVisibility">
                                                <?php if (!empty($orderTotal)) { ?>
                                                    <input type="hidden" name="actualAmountToPay"
                                                        value="<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>"
                                                        id="actualAmountToPay">
                                                    <div
                                                        class="payment_option d-flex align-items-center p-2 justify-content-between">
                                                        <div class="">
                                                            <input class="form-check-input mr-2" type="radio" value="pay_online"
                                                                id="pay_online" name="payment_method">
                                                            <label class="form-check-label ml-3" for="pay_online">
                                                                Pay Online
                                                            </label>
                                                        </div>
                                                        <div class="tex-right">
                                                            <button type="submit" disabled
                                                                class="btn btn-primary my-2 payNowBtn submitBtn"
                                                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Please wait...">Pay
                                                                Now &#8377; <span
                                                                    class="pay_now_text"><?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?></span></button>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="payment_option d-flex justify-content-between align-items-center p-2">
                                                        <div>
                                                            <input class="form-check-input" type="radio"
                                                                value="cash_on_delivery" id="cash_on_delivery"
                                                                name="payment_method">
                                                            <label class="form-check-label ml-3" for="cash_on_delivery">
                                                                Cash On Delivery
                                                            </label>
                                                        </div>
                                                        <input type="hidden" id="orderAmountTotal" name="orderAmount"
                                                            value="<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>">
                                                        <div class="tex-right">
                                                            <button type="submit" class="btn btn-primary my-2 submitBtn"
                                                                disabled
                                                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Please wait...">Order
                                                                Now &#8377; <span
                                                                    class="pay_now_text"><?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?></span></button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-12" id="paymentOptionsVisibilityOne" style="display: none;">
                                                <div
                                                    class="payment_option d-flex align-items-center p-2 justify-content-between">
                                                    <div class="">
                                                        <input class="form-check-input mr-2" type="radio"
                                                            value="pay_with_wallet" id="pay_with_wallet"
                                                            name="payment_method">
                                                        <label class="form-check-label ml-3" for="pay_with_wallet">
                                                            Continue
                                                        </label>
                                                    </div>
                                                    <div class="tex-right">
                                                        <button type="submit" class="btn btn-primary my-2"
                                                            data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Please wait...">Continue</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div><!-- End .col-lg-8 -->

                        <div class="col-lg-4">
                            <div class="order-summary">
                                <?php $subTotal = 0;
                                if (!empty($cartQ)) { ?>
                                    <h4>
                                        <a data-toggle="collapse" href="#order-cart-section" class="collapsed" role="button"
                                            aria-expanded="false" aria-controls="order-cart-section">
                                            <?php echo count($cartQ); ?> product(s) in Cart
                                        </a>
                                    </h4>

                                    <div class="collapse" id="order-cart-section">
                                        <table class="table table-mini-cart">
                                            <tbody>
                                                <?php foreach ($cartQ as $cartData) { ?>
                                                    <tr>
                                                        <td class="product-col">
                                                            <figure class="product-image-container">
                                                                <a href="<?php echo base_url() . 'product/' . $cartData['code'] ?>"
                                                                    class="product-image">
                                                                    <img src="<?php echo base_url() . $cartData['product_image'] ?>"
                                                                        alt="product">
                                                                </a>
                                                            </figure>
                                                            <div>
                                                                <h2 class="product-title">
                                                                    <a
                                                                        href="<?php echo base_url() . 'product/' . $cartData['code'] ?>">
                                                                        <?php echo $cartData['name'] ?>
                                                                    </a>
                                                                </h2>

                                                                <span class="product-qty">Qty:
                                                                    <?php echo $cartData['quantity'] ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="price-col"><i class="fa fa-inr"></i>
                                                            <?php echo ($cartData['quantity'] * $cartData['price']) ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- <hr style="marginTop: 20px"> -->
                                    <div class="order_box">
                                        <div class="row">

                                            <div class="col-6">
                                                <h4>Order Subtotal</h4>
                                            </div>
                                            <div class="col-6 text-right d-flex justify-content-end align-items-center">&#8377;
                                                <h4> <?php echo $orderTotal['productTotalWithoutTaxAndDeliveryAndDiscounts'] ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='order_box'>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4>Tax</h4>
                                            </div>
                                            <div class="col-6 text-right d-flex justify-content-end align-items-center">&#8377;
                                                <h4><?php echo $orderTotal['productTax']; ?></h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='order_box'>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4>Delivery Charge</h4>
                                            </div>
                                            <div class="col-6 text-right d-flex justify-content-end align-items-center">&#8377;
                                                <h4><?php echo $orderTotal['deliveryCharge']; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($orderTotal['walletPointsDiscounts'] > 0) { ?>
                                        <div class="order_box wallet_discount_row">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h4 for="wallet_discount_amount">Wallet Bonus Discount &#8377;
                                                        <?php echo $orderTotal['walletPointsDiscounts'] ?>
                                                    </h4>
                                                </div>
                                                <div class="col-6 text-right d-flex justify-content-end align-items-center">
                                                    <h4><input type="checkbox" class="wallet_bonus_discount"
                                                            name="wallet_discount_amount" id="wallet_discount_amount"></h4>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class='order_box'>
                                        <div class="row">
                                            <div class="col-6">
                                                <h2>Order Total</h2>
                                            </div>
                                            <div class="col-6 text-right d-flex justify-content-end align-items-center">&#8377;
                                                <h2 id="orderTotal"><?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <h4>
                                        <a href="javascript:void(0);" class="">Products not selected to checkout</a>
                                    </h4>
                                <?php } ?>
                            </div><!-- End .order-summary -->
                            <?php //} ?>

                        </div><!-- End .col-lg-4 -->
                        <?php //} ?>
                    </div><!-- End .row -->

                </div><!-- End .container -->
            <?php } else { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="alert bg-danger text-white">No Products In the Cart. <a
                                    href="<?php echo base_url() ?>Products">Go Back</a> To Add Products. </h5>
                            <!-- <alert class="bg-danger"></alert> -->
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="mb-5"></div>
        </main><!-- End .main -->
        <!-- End .main -->
        <?php echo $footer ?>
        <!-- End .footer -->
    </div>
    <?php echo $mobile_menu ?>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/frontend/js/cart.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXL19yva1nEHaZmHLlzPh8_0CajExS_ks"></script>


    <script>
        // function initMap() {
        //     var initialCoords = { lat: 12.9716, lng: 77.5946 };
        //     const lat = $("#selectaddress").attr('data-latitude');
        //     const lng = $("#selectaddress").attr('data-longitude');
        //     initialCoords = {lat, lng};
        //     var map = new google.maps.Map(document.getElementById('map'), {
        //         center: initialCoords,
        //         zoom: 12
        //     });

        //     var marker = new google.maps.Marker({
        //         position: initialCoords,
        //         map: map,
        //         draggable: true
        //     });

        //     if (navigator.geolocation) {
        //         navigator.geolocation.getCurrentPosition(function (position) {
        //             var userCoords = {
        //                 lat: position.coords.latitude,
        //                 lng: position.coords.longitude
        //             };
        //             map.setCenter(userCoords);
        //             marker.setPosition(userCoords);
        //         }, function () {
        //             console.log('Error: The Geolocation service failed.');
        //         });
        //     } else {
        //         console.log('Error: Your browser doesn\'t support geolocation.');
        //     }

        //     google.maps.event.addListener(marker, 'dragend', function (event) {
        //         var newCoords = {
        //             lat: event.latLng.lat(),
        //             lng: event.latLng.lng()
        //         };
        //     });
        // }


        function initMap() {
            var selectAddress = $("#selectaddress");
            var lat = parseFloat(selectAddress.find('option:selected').attr('data-latitude'));
            var lng = parseFloat(selectAddress.find('option:selected').attr('data-longitude'));
            var selectedAddressId = selectAddress.find('option:selected').val();
            console.log("SELECTED Address ID is "+selectedAddressId);
            
            // Check if the latitude and longitude are valid numbers
            if (!isNaN(lat) && !isNaN(lng)) {
                var initialCoords = { lat: lat, lng: lng };
                
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: initialCoords,
                    zoom: 12
                });

                var marker = new google.maps.Marker({
                    position: initialCoords,
                    map: map,
                    draggable: true
                });
                map.setCenter(initialCoords);
                marker.setPosition(initialCoords);
                $(".latlong").val(`${lat},${lng}`);
                $(".selectedAddressId").val(selectedAddressId);
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    var newLat = event.latLng.lat();
                    var newLng = event.latLng.lng();
                    $(".latlong").val(`${newLat},${newLng}`);
                });
            } else {
                $("#map").html(`<h4 class="mt-5 text-center text-danger">No Addess Set or is Invalid Address, Please try updating your address if the error persists. <br> <a href="${urljs}account/myaddresses">Click Here</a> to update address. </h4>`);
                // console.log('Error: Selected address does not have valid latitude and longitude.');
            }
        }

        $(document).ready(function () {
            initMap();
            save_checkout_addr();

            // Reinitialize the map when a new address is selected
            $('#selectaddress').on('change', function() {
                initMap();
            });
            $("#customdatepicker").datepicker({
                beforeShowDay: function (date) {
                    var today = new Date();
                    var curTime = today.getHours();
                    today.setHours(0, 0, 0, 0);
                    var day = date.getDay();
                    var isSelectable = (day === 2 || day === 3 || day === 5 || day === 6) && date > today;

                    // Calculate tomorrow's date
                    var tomorrow = new Date(today);
                    tomorrow.setDate(today.getDate() + 1);

                    // Check if it's after 6 PM today and the date is tomorrow
                    if (curTime >= 19 && date.toDateString() === tomorrow.toDateString()) {
                        console.log("Tomorrow's date is disabled because it's after 6 PM today.");
                        return [false];
                    }

                    return [isSelectable];
                },
                onSelect: function (dateText, inst) {
                    var selectedDate = $(this).datepicker('getDate'); // Get the selected date
                    var formattedDate = formatDate(selectedDate); // Format the selected date
                    if (formattedDate) {
                        $("#deliveryDate").val(formattedDate);
                    }
                }
            });

            $('input:radio[name="payment_method"]').change(function () {
                if ($(this).is(':checked')) {
                    $('button[type="submit"]').prop('disabled', true);
                    $(this).closest('.payment_option').find('button[type="submit"]').prop('disabled', false);
                }
            });

            // wallet Deduction Option
            $("#walletDeduction").on('click', function (e) {
                const amountAfterWalletDeduction = $(this).attr('data-amountAfterWalletDeduction');
                const amountWithoutWalletDeduction = $(this).attr('data-amountWithoutWalletDiscount');
                if ($(this).prop('checked')) {
                    $(".wallet_discount_row").css('display', 'none')
                    if (amountAfterWalletDeduction > 0) {
                        $('input[type="radio"]').prop('checked', false);
                        $("#pay_online").attr('checked', true)
                        $(".pay_now_text").text(amountAfterWalletDeduction);
                        $("#actualAmountToPay").val(amountAfterWalletDeduction);
                        $("#deduct_wallet_amount").val('yes');
                    } else {
                        $('input[type="radio"]').prop('checked', true);
                        $("#pay_with_wallet").attr('checked', true)
                        $("#deduct_wallet_amount").val('yes');
                        $("#paymentOptionsVisibility").css('display', 'none')
                        $("#paymentOptionsVisibilityOne").css('display', 'block')
                    }
                } else {
                    $('.wallet_discount_row').css('display', 'block');
                    $(".submitBtn").prop('disabled', true);
                    $('input[type="radio"]').prop('checked', true);
                    $(".pay_now_text").text(amountWithoutWalletDeduction);
                    $("#actualAmountToPay").val(amountWithoutWalletDeduction);
                    $("#deduct_wallet_amount").val('no');
                    $("#paymentOptionsVisibility").css('display', 'block')
                    $("#paymentOptionsVisibilityOne").css('display', 'none')
                }
            })
        });

        function formatDate(date) {
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month starts from 0, so add 1, and pad single digit months with leading zero
            var day = date.getDate().toString().padStart(2, '0'); // Pad single digit days with leading zero
            return year + '-' + month + '-' + day;
        }


        $(".wallet_bonus_discount").on('change', function (e) {
            e.preventDefault();
            if ($(this).is(':checked')) {
                $(".useWalletAmountDiv").css('display', 'none');
                $("#deduct_wallet_bonus").val('yes');
                $("#orderTotal").text(`<?php echo $orderTotal['totalWithTaxAndDeliveryAndDiscount'] ?>`);
                $(".pay_now_text").text(`<?php echo $orderTotal['totalWithTaxAndDeliveryAndDiscount'] ?>`);
                $("#deductableAmount").text(`<?php echo $orderTotal['totalWithTaxAndDeliveryAndDiscount'] ?>`);
                $("#actualAmountToPay").val(`<?php echo $orderTotal['totalWithTaxAndDeliveryAndDiscount'] ?>`);
                $("#orderAmountTotal").val(`<?php echo $orderTotal['totalWithTaxAndDeliveryAndDiscount'] ?>`);
            } else {
                $("#deduct_wallet_bonus").val('no');
                $(".useWalletAmountDiv").css('display', 'block');
                $("#orderTotal").text(`<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>`);
                $(".pay_now_text").text(`<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>`);
                $("#actualAmountToPay").val(`<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>`);
                $("#deductableAmount").text(`<?php echo $orderTotal['availableDeductWalletAmountWithoutWalletPointsDeduction'] ?>`);
                $("#orderAmountTotal").val(`<?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>`);
            }
        })
    </script>

</body>

</html>