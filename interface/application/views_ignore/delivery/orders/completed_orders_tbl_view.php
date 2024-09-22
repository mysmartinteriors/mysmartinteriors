<div class="table-responsive">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl. No.</th>
                <th>Order Id</th>
                <th>Delivery Address</th>
                <th>Products</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $datas) {
                    // print_R($datas);echo "<hr>";
            ?>
            <tr>
                    <td>
                        <?php echo $i?>
                    </td>               
                    <td>
                        <?php if(!empty($datas['orderId'])){echo $datas['orderId'];}else{ echo '---';} ?>
                    </td>     
                    <td>    
                        <?php echo !empty($datas['deliveryAddress']) ? $datas['deliveryAddress'] : '---'; ?>
                    </td>
                    <td><?php 
                    if(!empty($datas['order_details'])){
                        foreach ($datas['order_details'] as $orderDetails) {
                            // print_R($orderDetails);echo "<hr>";
                            echo !empty($orderDetails['product_name']) ? nl2br($orderDetails['product_name'] ." - " .$orderDetails['product_quantity']. " " . $orderDetails['product_unit'] . " - Qty " . $orderDetails['count']. "<br><br>"): '---'; 
                        }
                    }
                    ?></td>           
                    <td>
                        <?php 
                                 echo "<i class='fa fa-rupee-sign'></i> ". $datas['actualAmountToPay'] ;
                                 ?>
                    </td>
                 <td><?php echo '<span class="'.$datas['status_color_name'].'">'.$datas['status_name'].'</span>' ?>
                 <?php if(!empty($datas['comments'])){
                            echo $datas['comments'];
                         } ?>
                </td>
                    
                   <!-- <td>
                        <div class="tbl-action-holder">
                        <?php if($datas['status'] == 27){ ?>
                            <button class="tbl-action-btn btn btn-info btn-update" data-id="<?php echo $datas['id'] ?>" data-code="<?php echo $datas['orderId'] ?>">
                            Update
                           </button>
                        <?php } ?>
                        </div>
                    </td> -->

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
        <?php
        if(isset($item_per_page)!=""){
            echo $pagination;
            }
        ?>
    </div>
</div>