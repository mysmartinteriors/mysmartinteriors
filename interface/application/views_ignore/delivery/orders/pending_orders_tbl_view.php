<?php if (isset($pagination_data)) { ?>
    <div class="row">
        <div class="col-12">
            <p class="text-left">Total Records <?php echo $pagination_data['total_rows'] ?></p>
        </div>
    </div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl. No.</th>
                <th>Order Id</th>
                <th>Delivery Address</th>
                <th>Customer Details</th>
                <th>Map</th>
                <th>Products</th>
                <th>Total Amount</th>
                <th>Created</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $datas) { ?>
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
                            Name : <?php echo $datas['firstName'] . ' ' . $datas['lastName']; ?>
                            <br>
                            Mobile : <a href="tel:<?php echo $datas['phone'] ?>"><?php echo $datas['phone'] ?></a>
                            <br>
                            Email : <?php echo $datas['email'] ?>
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
                            <button class="tbl-action-btn btn btn-info btn-viewProducts" data-toggle="tooltip"
                                title="View Products" data-id="<?php echo $datas['id'] ?>">
                                <i class="fa fa-eye"></i>
                            </button>
                            <?php
                            ?>
                        </td>
                        <td>
                            <?php echo "<i class='fa fa-rupee-sign'></i> " . $datas['actualAmountToPay']; ?>
                        </td>
                        <td>
                            <?php echo $datas['createdDate']; ?>
                        </td>
                        <td>
                            <?php echo ($datas['pay_status'] == 1) ? '<span class="badge badge-success"> Paid</span>' : '<span class="badge badge-danger"> Pending</span>' ?>
                        </td>
                        <td>
                            <?php echo '<span class="' . $datas['status_color_name'] . '">' . $datas['status_name'] . '</span>' ?>
                            <?php if (!empty($datas['deliveryDate'])) { ?>
                                On <i class="fa fa-calendar"></i> <?php echo $datas['deliveryDate'] ?>
                            <?php } ?>
                            <?php if (!empty($datas['comments'])) {
                                echo $datas['comments'];
                            } ?>
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
        $.post(urljs + 'delivery/orders/productDetails', { orderId }, function (response) {
            if (response.status == 'success') {
                dataModal.find('.bootbox-body').html(response.message);
            } else {
                bootbox.cloaseAll();
                swal('No Products Found for the order', '', 'warning');
            }
        }, 'json')
    })
</script>