<div class="table-responsive">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl. No.</th>
                <th>Order Id</th>
                <th>Customer</th>
                <th>Delivery Address</th>
                <th>Products</th>
                <th>Payment Details</th>
                <th>Ordered At</th>
                <th>Delivery By</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $datas) {
            ?>
            <tr>
                    <td>
                        <?php echo $i?>
                    </td>               
                    <td>
                        <?php if(!empty($datas['orderId'])){echo $datas['orderId'];}else{ echo '---';} ?>
                    </td>
                    <td>
                       <?php echo !empty($datas['firstname']) ? $datas['firstname'] : '---'; ?>
                        <?php echo !empty($datas['email']) ? '<br>'.$datas['email'] : ''; ?>
                        <?php echo !empty($datas['phone']) ? '<br>'.$datas['phone'] : ''; ?>                     
                    </td>     
                    <td>    
                        <?php echo !empty($datas['deliveryAddress']) ? $datas['deliveryAddress'] : '---'; ?>
                    </td>
                    <td><?php 
                    if(!empty($datas['order_details'])){
                        foreach ($datas['order_details'] as $orderDetails) {
                            // print_R($orderDetails);echo "<hr>";
                            echo !empty($orderDetails['product_name']) ? nl2br($orderDetails['product_name'] ." - " .$orderDetails['product_quantity'] . " " . $orderDetails['product_unit'] . " / <i class='fa fa-rupee-sign'></i> " .$orderDetails['product_price']. " - Qty -> " . $orderDetails['count']. "<br><br>"): '---'; 
                        }
                    }
                    ?></td>           
                    <td>
                        <?php 
                                echo "Sub Total => <i class='fa fa-rupee-sign'></i>". $datas['totalAmount'] . "<br>";
                                echo "Tax => <i class='fa fa-rupee-sign'></i>". $datas['taxAmount'] . "<br>";
                                echo "Delivery Charge => <i class='fa fa-rupee-sign'></i>". $datas['deliveryCharge'] . "<br>";
                                echo "Total => ". $datas['totalAmount'] + $datas['taxAmount'] + $datas['deliveryCharge'] . "<br>";
                                echo "Deducted Amount => <i class='fa fa-rupee-sign'></i>";
                                if($datas['deductedSubscriptionAmount'] != ''){
                                   echo $datas['deductedSubscriptionAmount'];
                                }else if($datas['deductedSubscriptionWalletPointsAmount'] != ''){
                                   echo $datas['deductedSubscriptionWalletPointsAmount'];
                                }else{
                                    echo "0";
                                }
                                 echo "<br>";
                                 echo "Total Amount to Pay => <i class='fa fa-rupee-sign'></i>". $datas['actualAmountToPay'] ;
                             ?>
                    </td>
                    <td>
                        <?php if(!empty($datas['createdDate'])){echo custom_date('d-M-Y h:i A',$datas['createdDate']);}else{ echo '---';} ?>
                    </td>
                    <td>
                        <?php if(!empty($datas['delivery_boy_name'])){
                           echo $datas['delivery_boy_name'] ;
                        }else{
                            echo "-----";
                        }
                        if(!empty($datas['delivery_boy_code'])){
                            echo "<br>" .$datas['delivery_boy_code'] ;
                         }
                         if(!empty($datas['delivery_boy_email'])){
                            echo "<br>" .$datas['delivery_boy_email'] ;
                         }
                         if(!empty($datas['delivery_boy_phone'])){
                            echo "<br>" .$datas['delivery_boy_phone'] ;
                         }
                        ?>
                  <?php //echo "Delivered At". !empty($datas['deliveredDate']) ? '<br>'.custom_date('d-M-Y h:i A',$datas['deliveredDate']) : ''; ?>
                </td>
                 <td>
                    <?php 
                 echo ($datas['pay_status'] == 1) ? '<span class="badge badge-success"> Paid</span><hr>' : '<span class="badge badge-danger"> Pending</span><hr>'; 
                            if($datas['paymentMethod']=='cash_on_delivery'){
                                echo 'CASH ON DELIVERY';
                            }else if($datas['paymentMethod']=='pay_online'){
                                echo 'ONLINE PAYMENT';
                            }else{
                                if($datas['pay_status']==1){
                                    echo "WALLET PAYMENT";
                                }else{
                                    echo "UNKNOWN";
                                }
                            } ?>
                 </td>
                 <td><?php echo '<span class="'.$datas['status_color_name'].'">'.$datas['status_name'].'</span>' ?>
                         <?php if(!empty($datas['comments'])){
                          echo $datas['comments'];
                         } ?>
                </td>
                <td>
                        <div class="tbl-action-holder">
                        <?php if($datas['status'] == 25){ ?>
                            <button class="tbl-action-btn btn btn-info btn-assign" data-id="<?php echo $datas['id'] ?>" data-code="<?php echo $datas['orderId'] ?>">
                            Assign Order <br> to Delivery Boy
                           </button>
                        <?php } ?>
                        <?php //if($datas['status'] == 25 || $datas['status'] == 27){ ?>
                            <!-- <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air" onclick="window.location.href='<?php echo base_url()?>admin/orders/edit/<?php echo $datas['id'] ?>'" title="Edit Order">
                             <i class="ti-pencil"></i>
                            </button> -->
                        <?php //}else{ ?>
                            <!-- <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air" onclick="window.location.href='<?php echo base_url()?>admin/orders/view/<?php echo $datas['id'] ?>'" title="View Order">
                             <i class="ti-zoom-in"></i>
                            </button> -->
                            <?php //} ?>
                            <?php //if($datas['status'] == 29){ ?>
                            <button class="tbl-action-btn btn btn-purple mt-2" onclick="window.open('<?php echo base_url()?>admin/orders/pdf/<?php echo $datas['id'] ?>')" title="Generate Invoice">
                             <!-- <i class="ti-printer"></i> -->
                             View Invoice
                            </button>
                        <?php //} ?>
                        </div>
                    </td>

            </tr>
    <?php 
    $i++;
                    }
            }
            else{
    ?>  
    <tr>
        <td colspan="10" class="text-center">No records found...</td>
    </tr>
 <?php 
            }
    ?>  
        </tbody>

    </table>
    <div id="page_result" class="table-pagination-holder">
        <?php if (isset($pagination_data['pagination_links'])) { echo $pagination_data['pagination_links']; } ?>
    </div>
</div>