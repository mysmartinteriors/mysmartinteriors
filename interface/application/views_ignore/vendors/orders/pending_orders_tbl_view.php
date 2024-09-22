<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl. No.</th>
                <th>Order Id</th>
                <th>Delivery Address</th>
                <th>Map</th>
                <th>Products</th>
                <th>Total Amount</th>
                <th>Created</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $datas) {?>
                    <tr>
                        <td>
                            <?php echo $i ?>
                        </td>
                        <td>
                            <?php if (!empty($datas['orderId'])) {
                                echo $datas['orderId'];
                            } else {
                                echo '---';
                            } ?>
                        </td>
                        <td>
                            <?php echo !empty($datas['deliveryAddress']) ? $datas['deliveryAddress'] : '---'; ?>
                        </td>
                        <td>
                            <a target="_blank" href="https://www.google.com/maps/search/<?php echo $datas['latlong'] ?>">
                                Google Map <i class="fa fa-map"></i>
                            </a>
                        </td>
                        <td>
                            <?php
                            if (!empty($datas['order_details'])) {
                                foreach ($datas['order_details'] as $orderDetails) {
                                    echo !empty($orderDetails['product_name']) ? nl2br($orderDetails['product_name'] . " - " . $orderDetails['product_quantity'] . " " . $orderDetails['product_unit'] . " - Qty " . $orderDetails['count'] . "<br><br>") : '---';
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo "<i class='fa fa-rupee-sign'></i> " . $datas['actualAmountToPay']; ?>
                        </td>
                        <td>
                            <?php echo $datas['createdDate']; ?>
                        </td>
                        <td>
                            <?php echo ($datas['pay_status']==1)?'<span class="badge badge-success"> Paid</span>':'<span class="badge badge-danger"> Pending</span>' ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <?php if ($datas['status'] == 27) { ?>
                                    <button class="tbl-action-btn btn btn-info btn-update" data-id="<?php echo $datas['id'] ?>"
                                        data-code="<?php echo $datas['orderId'] ?>">
                                        Update
                                    </button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php $i++;
                }
            } else { ?>
                <tr>
                    <td colspan="10" class="text-center">No records found...</td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <div id="page_result" class="table-pagination-holder">
        <?php
        if (isset($item_per_page) != "") {
            echo $pagination;
        }
        ?>
    </div>
</div>