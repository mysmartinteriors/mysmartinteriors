<div class="dataViewModal">
    <div class="">
        <?php if(isset($orderQ)){ 
            //print_R($orderQ);echo "<hr>";   ?>
          <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Sl. No.</th>
                        <th>Product</th>
                        <!-- <th>Price</th> -->
                        <th>Quantity</th>  
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                  $i=1;
                  foreach($orderQ['order_details'] as $ordersQ){ 
                    // print_R($ordersQ);echo "<hr>";
                  ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td class="product-col">
                          <figure class="product-image-container">
                              <!-- <a href="<?php //echo base_url().'product/'.$ordersQ['productURL']?>" target="_blank" class="product-image"> -->
                                  <img src="<?php echo base_url().$ordersQ['product_image']?>" alt="product">
                              <!-- </a> -->
                          </figure>
                          <h2 class="product-title">
                              <!-- <a href="<?php //echo base_url().'product/'.$orderQ['productURL']?>" target="_blank"> -->
                              <?php echo $ordersQ['product_name'] ?><br>
                               <?php if(!empty($ordersQ['product_price']) && !empty($ordersQ['product_quantity']) && !empty($ordersQ['product_unit'])){ ?>
                                   <?php echo $ordersQ['product_quantity'] . ' ' . $ordersQ['product_unit']; ?><br>
                                   <i class="fa fa-inr"></i> <?php echo  $ordersQ['product_price'] ?></a>
                                   &nbsp<?php if(!empty($ordersQ['product_mrp'])){ ?>
                                    <del><?php echo $ordersQ['product_mrp'] ?></del>
                                   <?php } ?>
                               <?php } ?>
                            <!-- </a> -->
                          </h2>
                        </td>
                        <!-- <td><?php echo '<i class="fa fa-inr"></i> '.$ordersQ['product_price'] ?></td> -->
                        <td><?php echo $ordersQ['count'] ?></td>
                        <!-- <td><?php //echo $orderQ['tax'].'%' ?></td> -->
                        <td><?php echo '<i class="fa fa-inr"></i> '.$ordersQ['totalAmount'] ?></td>
                    </tr>
                <?php $i++; } ?>
                </tbody>
            </table>
          </div>
       <?php }else{ ?>
          <p class="text-center">You don't have any orders!<br><a href="<?php echo base_url()?>Products">Shop now</a>  </p>
       <?php } ?>

    </div><!-- End .col-md-6 -->          
</div><!-- End .row -->