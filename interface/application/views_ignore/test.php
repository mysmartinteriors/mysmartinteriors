<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashfree Checkout Integration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>

<body>
    <div class="row">
        <p>Click below to open the checkout page in current tab</p>
        <button id="renderBtn">Pay Now</button>
    </div>
    <script>
        const cashfree = Cashfree({
            mode: "sandbox",
            // mode: "production",
        });
        document.getElementById("renderBtn").addEventListener("click", () => {
            let checkoutOptions = {
                paymentSessionId: "<?php echo $sessionId ?>",
                redirectTarget: "_self",
                returnUrl: "<?php echo base_url().'checkout/response?orderId='.$orderId ?>"
            };
            cashfree.checkout(checkoutOptions);
        });

    </script>
</body>

</html>