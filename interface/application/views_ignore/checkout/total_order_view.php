<?php
$subTotal = 0;
if (!empty($cartQ)) {
    foreach ($cartQ as $cartData) {
        $subTotal += $cartData['quantity'] * $cartData['price'];
    }
}

?>

<h3>Order summary</h3>
<table class="table table-totals">
    <tbody>
        <tr>
            <td>Subtotal</td>
            <td class="ordersubTotal"><i class="fa fa-inr"></i>
                <?php echo $subTotal ?>
            </td>
        </tr>

        <tr>
            <td>Tax</td>
            <?php $tax = $orderTotal - $subTotal; ?>
            <td class="orderTax"><i class="fa fa-inr"></i>
                <?php echo $tax ?>
            </td>
        </tr>

        <tr>
            <td>Delivery Charges</td>
            <?php
            $deliverycharge = '';
            if ($orderTotal >= 375) {
                $deliverycharge = 0;
            } else if (isset($deliveryCharge)) {
                $deliverycharge = $deliveryCharge;
            }
            // if($orderTotal >= 375){ 
            ?>
            <td class="delivery_charge"><i class="fa fa-inr"></i>
                <?php echo $deliverycharge ?>
            </td>
            <?php //}else{  ?>
            <!-- <td class="delivery_charge"><i class="fa fa-inr"></i> <?php //echo "0"  ?></td> -->
            <?php //}  ?>
            <!-- <td class="delivery_charge"></td> -->
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>Order Total</td>
            <?php if ($orderTotal >= 375) {
                // $deliverycharge = 0;
                $totalOrderCost = $subTotal + $tax;
            } else {
                $totalOrderCost = $subTotal + $tax + $deliveryCharge;
            } ?>
            <td class="orderTotal"><i class="fa fa-inr"></i>
                <?php echo $totalOrderCost ?>
            </td>

        </tr>
        <?php
        if ($orderTotal < 375) {
            $total = 375; ?>
            <p><b><u>Note:</u> Please add items worth <b><i class="fa fa-inr"></i>
                        <?php echo $total - ($subTotal + $tax) ?>
                    </b> more for <b>Free Delivery</b></b></p>
        <?php } ?>
    </tfoot>
</table>
</div>