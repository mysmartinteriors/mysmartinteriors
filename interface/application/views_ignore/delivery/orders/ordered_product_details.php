<style>
    .product-col{
        align-items: flex-start;
    }
    .product-image-container{
        position: relative;
  display: block;
  background-color: #fafafa;
        flex: 0 0 75px;
        max-width: 75px;
        vertical-align: top;
        margin-right: 1.5rem;
        border: 1px solid #ccc;
        margin-bottom: 0;
    }

    .table {
  width: 100%;
  margin-bottom: 1rem;
  background-color: transparent;
}

.table-mini-cart tr td {
  border: 0;
    border-top-width: 0px;
    border-top-style: none;
    border-top-color: currentcolor;
  padding: 0.1rem 0;
  vertical-align: top;
  border-top: 1px solid #ccc;
}

.product-image img {
  display: block;
  width: 100%;
  height: auto;
}

.table-mini-cart .product-title {
  display: block;
  max-width: 200px;
  /* margin-bottom: .2rem; */
  font-size: 1rem;
  font-weight: 600;
}

.table-mini-cart .product-qty {
  display: block;
  color: #777;
  /* font-size: 1rem; */
}

.table-mini-cart .price-col {
  color: #000;
  /* padding-top: 1rem; */
  font-size: 1rem;
  font-weight: 600;
}
</style>

<div class="row">
    <div class="col-12">
        <table class="table table-mini-cart">
            <thead>
                <th>Product</th>
                <th>Quantity</th>
                <th>Product Amount</th>
            </thead>
            <tbody>
                <?php foreach ($orderDetails['order_details'] as $cartData) { ?>
                    <tr>
                        <td class="product-col d-flex">
                            <figure class="product-image-container">
                                <a class="product-image" style="display: block;">
                                    <img src="<?php echo base_url() . $cartData['product_image'] ?>"
                                        alt="product">
                                </a>
                            </figure>
                            <div>
                                <h2 class="product-title">
                                    <a>
                                        <?php echo $cartData['product_name'] ?>
                                    </a>
                                </h2>

                                <span class="product-qty">
                                    <?php echo $cartData['quantity']. ' '.$cartData['unit'] ?>
                                </span>
                                <span class="product-qty">&#8377;
                                    <?php echo $cartData['price'] ?>
                                </span>
                            </div>
                        </td>
                        <td class="price-col"><i class="fa fa-inr"></i>
                            <?php echo $cartData['count'] ?>
                        </td>
                        <td class="price-col"><i class="fa fa-inr"></i>
                            &#8377; <?php echo ($cartData['count']*$cartData['price']) ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>