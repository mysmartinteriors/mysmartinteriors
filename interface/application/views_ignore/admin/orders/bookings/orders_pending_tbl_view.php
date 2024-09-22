<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl. No.</th>
                <th>Ordered At</th>
                <th>Order Id</th>
                <th>Delivery Address</th>
                <th>Payment Status</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Delivery Date</th>
                <th>Delivery Status</th>
                <th>Delivered By</th>
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
                            <?php echo $i ?>
                        </td>
                        <td>
                            <?php if (!empty($datas['createdDate'])) {
                                echo custom_date('d-M-Y h:i A', $datas['createdDate']);
                            } else {
                                echo '---';
                            } ?>
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
                            }
                            
                            ?>
                        </td>
                        <td>
                            <?php echo !empty($datas['firstname']) ? $datas['firstname'] : '---'; ?>
                            <?php echo !empty($datas['email']) ? '<br>' . $datas['email'] : ''; ?>
                            <?php echo !empty($datas['phone']) ? '<br>' . $datas['phone'] : ''; ?>
                        </td>
                        <td>
                            <button class="tbl-action-btn btn btn-info btn-viewProducts" data-toggle="tooltip"
                                title="View Products" data-id="<?php echo $datas['id'] ?>">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                        <td>
                            <?php if(!empty($datas['deliveryDate'])){ ?>
                            <i class="fa fa-calendar"></i> <?php echo $datas['deliveryDate'] ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo '<span class="' . $datas['status_color_name'] . '">' . $datas['status_name'] . '</span>' ?>
                            <?php if (!empty($datas['comments'])) {
                                echo $datas['comments'];
                            } ?>
                        </td>

                        <td>
                            <?php if (!empty($datas['delivery_boy_name'])) {
                                echo $datas['delivery_boy_name'];
                            } else {
                                echo "-----";
                            }
                            if (!empty($datas['delivery_boy_code'])) {
                                echo "<br>" . $datas['delivery_boy_code'];
                            }
                            if (!empty($datas['delivery_boy_email'])) {
                                echo "<br>" . $datas['delivery_boy_email'];
                            }
                            if (!empty($datas['delivery_boy_phone'])) {
                                echo "<br>" . $datas['delivery_boy_phone'];
                            }
                            ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <button data-toggle="tooltip" title="View Invoice" class="tbl-action-btn btn btn-purple mt-2"
                                    onclick="window.open('<?php echo base_url() ?>admin/orders/pdf/<?php echo $datas['id'] ?>')"
                                    title="Generate Invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
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
        <?php if (isset($pagination_data['pagination_links'])) {
            echo $pagination_data['pagination_links'];
        } ?>
    </div>
</div>



<script>
    $(".btn-viewProducts").on('click', function (e) {
        e.preventDefault();
        const orderId = $(this).attr('data-id');
        var dataModal = bootbox.dialog({
            title: "",
            message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
            closeButton: true,
            size: 'large',
            animate: true,
            centerVertical: true,
            className: "userModalView",
        });
        $.post(urljs + 'admin/orders/productDetails', { orderId }, function (response) {
            if (response.status == 'success') {
                dataModal.find('.bootbox-body').html(response.message);
            } else {
                bootbox.cloaseAll();
                swal('No Products Found for the order', '', 'warning');
            }
        }, 'json')
    })
</script>