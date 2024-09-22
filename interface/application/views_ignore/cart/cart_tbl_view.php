<style>
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
    .counter-button {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100px; /* Adjust width as needed */
      height: 40px; /* Adjust height as needed */
      padding: 5px 10px;
      font-size: 18px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background:#6d974f;
      color:#ffffff;
    }

    .decrement, .increment {
      cursor: pointer;
    }
</style>

<?php

if (!empty($cartQ) && !empty($orderTotal)) { ?>
    <div class="row">
        <div class="col-12">
            <?php //print_r($orderTotal); echo "<hr>"; ?>
        </div>
        <div class="col-lg-8">
            <div class="cart-table-container">
                <form id="cart_update_form">
                    <table class="table table-cart ">
                        <thead>
                            <tr>
                                <th class="product-col">Product</th>
                                <th class="qty-col">Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subTotal = 0;
                            foreach ($cartQ as $cartData) {
                                $subTotal += $cartData['quantity'] * $cartData['price'];
                                ?>
                                <tr class="product-row">
                                    <td class="product-col prod_items_col">
                                        <figure class="product-image-container prod_item_img">
                                            <a href="<?php echo base_url() . 'product/' . $cartData['code'] ?>" 
                                                target="_blank" class="product-image">
                                                <img src="<?php echo base_url() . $cartData['product_image'] ?>" alt="product">
                                            </a>
                                        </figure>
                                        <h1 class="product-title prod_item_text">
                                            <a href="<?php echo base_url() . 'product/' . $cartData['code'] ?>"
                                                target="_blank">
                                                <?php 
                                                $name = $cartData['name']; 
                                                $name .= ($cartData['comission_applicable']=='yes')?' <span class="text-success">*</span>':'';
                                                echo $name;
                                                ?>
                                                <br>
                                                <?php if (!empty($cartData['price']) && !empty($cartData['qty']) && !empty($cartData['unit'])) { ?>
                                                    <?php echo $cartData['qty'] . ' ' . $cartData['unit']; ?><br>
                                                    <i class="fa fa-inr"></i>
                                                    <?php echo $cartData['price'] ?>
                                                    <br>
                                                    <?php if (!empty($cartData['mrp'])) { ?>
                                                    <del>
                                                        &#8377; <?php echo $cartData['mrp'] ?>
                                                    </del>
                                                <?php } ?>
                                                &nbsp;
                                                    <?php if($cartData['mrp'] && $cartData['mrp']>$cartData['price']){ ?>
                                                    <span class="badge bg-success text-white"><?php
                                                        $mrp = $cartData['mrp'];
                                                        $price = $cartData['price'];
                                                        $discount = $mrp-$price;
                                                        $discountPercentage = (100-(($price*100)/$mrp));
                                                        echo round($discountPercentage, 0). '% Off';
                                                    ?></span>
                                                    <?php } ?>
                                                </a>
                                            <?php } ?>
                                        </h1>
                                    </td>
                                    <td>
                                    <div class="counter-button">
                                        <div data-cartId="<?php echo $cartData['cartId'] ?>" data-productId="<?php echo $cartData['productId'] ?>" class="decrement">-</div>
                                        <div class="count" data-count="<?php echo $cartData['quantity'] ?>"><?php echo $cartData['quantity'] ?></div>
                                        <div data-cartId="<?php echo $cartData['cartId'] ?>" data-productId="<?php echo $cartData['productId'] ?>" class="increment">+</div>
                                    </div>
                                        <!-- <input class="vertical-quantity form-control prdQty" type="text" data-cartId="<?php //echo $cartData['cartId'] ?>" data-productId="<?php //echo $cartData['productId'] ?>" value="<?php //echo $cartData['quantity'] ?>" name="quantity[]">
                                        <input class="" type="hidden" value="<?php //echo $cartData['cartId'] ?>" name="cartId[]">
                                        <input class="" type="hidden" value="<?php //echo $cartData['productId'] ?>" name="productId[]"> -->
                                    </td>
                                    <td>
                                        <i class="fa fa-inr"></i> <span class="prdTotal">
                                            <?php echo $cartData['quantity'] * $cartData['price'] ?>
                                        </span>
                                    </td>
                                    <td class="product-action-row">
                                        <a href="javascript:void(0);" title="Remove product" class="btn-remove delCartPrd"
                                            data-id="<?php echo $cartData['cartId'] ?>"><span class="sr-only">Remove</span></a>
                                    </td>
                                </tr>
                            <?php } ?> 
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="clearfix">
                                    <div class="float-left">
                                        <button type="button"
                                            onclick="window.location.href='<?php echo base_url() ?>Products'"
                                            class="btn btn-outline-secondary">Continue Shopping</button>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div><!-- End .col-lg-8 -->
        

        <div class="col-lg-4">
            <form id="cart_to_checkout" method="post" role="form" action="<?php echo base_url() ?>checkout">
                <div class="cart-summary">
                    <h3>Order summary</h3>

                    <?php if($orderTotal['productTotalWithoutTaxAndDeliveryAndDiscounts']<375){ ?>
                        <p class="text-white" style="width: 100%;"><div class=" bg-warning p-2 text-white text-center rounded"  style="font-size: 13px;">Please Add Products worth &#8377; <?php echo (375 - $orderTotal['productTotalWithoutTaxAndDeliveryAndDiscounts']) ?> to get free delivery</div></p>
                    <?php } ?>

                    <table class="table table-totals">
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td class="ordersubTotal"><i class="fa fa-inr"></i>
                                    <?php echo $orderTotal['productTotalWithoutTaxAndDeliveryAndDiscounts'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Tax</td>
                                <?php $tax = $orderTotal['productTax']; ?> 
                                <td class="orderTax"><i class="fa fa-inr"></i>
                                    <?php echo $tax ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Delivery Charge</td>
                                <?php $tax = $orderTotal['deliveryCharge']; ?> 
                                <td class="orderTax"><i class="fa fa-inr"></i>
                                    <?php echo $tax ?>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td>Wallet Bonus Discount</td>
                                <td class="orderTax"><i class="fa fa-inr"></i>
                                    <?php //echo $orderTotal['walletPointsDiscounts'] ?>
                                </td>
                            </tr> -->
                        </tbody>
                        <tfoot> 
                            <tr>
                                <td>Order Total</td>
                                <td class="orderTotal"><i class="fa fa-inr"></i>
                                    <?php echo $orderTotal['productTotalWithTaxAndDelivery'] ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div style="color: purple; padding: 15px;background: #f7e5f7;border-radius: 15px;text-align: center;margin-bottom:15px;">
                    Repurchase Commission available only for the Products marked with *
                    </br>
                    Checkout now to avail amazing offers and discounts.
                    </div>

                    <?php
                    if ($orderTotal < 375) {
                        $total = 375; ?>
                        <p><b><u>Note:</u> Please add items worth <b><i class="fa fa-inr"></i>
                                    <?php echo $total - ($subTotal + $tax) ?>
                                </b> more for <b>Free Delivery</b></b></p>
                    <?php } ?>
                    <div class="checkout-methods">
                        <button type="submit" class="btn btn-block btn-sm btn-primary">Checkout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php } else { ?>
    <div class="row">
        <div class="col-12">
            <div class="cart-table-container">
                <div class="card empty-cart-box">
                    <div class="card-body">
                        <img src="<?php echo base_url() ?>ui/frontend/images/empty-cart.png">
                        <h2> Your shopping cart is empty </h2>
                        <div class="text-center">
                            <button onclick="window.location.href='<?php echo base_url() ?>Products'"
                                class="btn btn-outline-secondary">Shop Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>