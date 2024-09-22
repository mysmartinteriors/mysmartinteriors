<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Vendors</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Vendor Challan History Detail</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <!-- end row -->
                <?php if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                    $rowData = $apidata['data_list'];
                    ?>
                    <div class="row">
                        <div class="col-12 my-2">
                            <div class="col-12">
                                <div class="ibox">
                                    <div class="ibox-head page-head-btns">
                                        <div class="ibox-title">Challan History Information</div>
                                    </div>
                                    <div class="ibox-body">
                                        <div class="row mb-4">
                                            <div class="col-sm-12 col-11">
                                                <table class="table">
                                                    <tr>
                                                        <td>Created</td>
                                                        <td>
                                                            <?php echo custom_date('d-M-Y h:i A', $rowData['created_at']); ?>
                                                            <?php if (!empty($rowData['created_user_firstname'])) {
                                                                echo '<br>By - ' . ucwords($rowData['created_user_firstname'] . ' ' . $rowData['created_user_lastname']);
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Updated At</td>
                                                        <td><?php echo !empty($rowData['updated_at']) ? custom_date('d-M-Y h:i A', $rowData['updated_at']) : 'N/A'; ?>
                                                        </td>
                                                        <?php if (!empty($rowData['updated_user_firstname'])) {
                                                            echo '<br>By - ' . ucwords($rowData['updated_user_firstname'] . ' ' . $rowData['updated_user_lastname']);
                                                        } ?>
                                                    </tr>
                                                </table>

                                                <table class="table table-bordered mb-0">
                                                    <thead class="thead-default">
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Name</th>
                                                            <th>Product Quantity</th>
                                                            <th>Product Unit</th>
                                                            <th>In Stock</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($rowData['products'])) { ?>
                                                            <?php $i = 0; ?>
                                                            <?php foreach ($rowData['products'] as $product) { ?>
                                                                <tr>
                                                                    <td><?php echo $i ?></td>
                                                                    <td><?php echo $product['product_name'] ?></td>
                                                                    <td><?php echo $product['product_quantity'] ?></td>
                                                                    <td><?php echo $product['product_unit'] ?></td>
                                                                    <td class="px-5">
                                                                        <div class="form-check">
                                                                            <input data-id="<?php echo $product['id'] ?>" class="form-check-input" type="radio"
                                                                                name="status<?php echo $i ?>"
                                                                                id="gridCheck<?php echo ($i + 1); ?>_yes" value="yes" <?php echo ($product['status']==61)?'checked':'checked'?>>
                                                                            <label class="form-check-label"
                                                                                for="gridCheck<?php echo ($i + 1) ?>_yes">Yes</label>
                                                                            <br />
                                                                            <input data-id="<?php echo $product['id'] ?>" class="form-check-input" type="radio"
                                                                                name="status<?php echo $i ?>"
                                                                                id="gridCheck<?php echo ($i + 1); ?>_no" value="no" <?php echo ($product['status']==62)?'checked':'' ?>>
                                                                            <label class="form-check-label"
                                                                                for="gridCheck<?php echo ($i + 1) ?>_no">No</label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="tbl-action-holder">
                                                                            <input data-id="<?php echo $product['id'] ?>" type="text"
                                                                                placeholder="Enter Remarks (Optional)"
                                                                                name="vendor_remarks[]" id="" value="<?php echo !empty($product['vendor_remarks'])?$product['vendor_remarks']:'' ?>" class="form-control">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php $i++;
                                                            } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="6">No Products Found</td>
                                                            </tr>
                                                            <!-- Handle case when $rowData['products'] is empty -->
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <?php if($rowData['status']!=60){ ?>
                                                <div class="row">
                                                    <div class="col-12 text-right">
                                                        <button class="btn btn-danger updateChallan my-2">Update Challan</button>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-12 my-2">
                            <div class="col-12">
                                <div class="ibox">
                                    <div class="ibox-head page-head-btns">
                                        <div class="ibox-title">No Detail Found</div>
                                    </div>
                                    <div class="ibox-body">
                                        <div class="row mb-4">
                                            <div class="col-sm-10 col-11">
                                                <span class="alert alert-danger">No Detail found for the above Challan
                                                    History</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- END PAGE CONTENT-->
                    <?php echo $footer ?>
                </div>
            </div>
            <?php echo $commonJs ?>
            <script>
                $(".btn-deleteData").on('click', function (e) {
                    e.preventDefault();
                    const productId = $(this).attr('data-id');
                    const productName = $(this).attr('data-name');
                    $.post(urljs + 'admin/vendors/delete_challan_history_product', { productId }, function (response) {
                        if (response.status == 'success') {
                            swal_alert('Success!', productName + ' deleted successfully', 'success', '');
                        } else {
                            swal('Error', productName + ' couldn\'t be deleted', 'error')
                        }
                    }, 'json')
                })
            </script>

<script>
    $(document).ready(function() {
        // Function to get the data from the table and construct arrays
        function getData() {
            var inStockData = [];
            var remarksData = [];

            // Iterate through each table row
            $('tbody tr').each(function(index) {
                var productId = $(this).find('input[type="radio"]').data('id');
                var inStockValue = $(this).find('input[type="radio"]:checked').val();
                var remarksValue = $(this).find('input[type="text"]').val();

                // Construct object for in_stock array
                if(productId){
                    inStockData.push({
                        'data-id': productId, // Use the product ID extracted from the data-id attribute
                        'in_stock': inStockValue === 'yes' // Convert 'yes' to true, 'no' to false
                    });
                }

                // Construct object for remarks array
                if(remarksValue){
                    remarksData.push({
                        'data-id': productId, // Use the product ID extracted from the data-id attribute
                        'value': remarksValue
                    });
                }
            });

            return {
                'in_stock': inStockData,
                'remarks': remarksData
            };
        }

        // Click event handler for the update button
        $('.updateChallan').click(function() {
            // Get the data from the table
            var dataToSend = getData();
            // Send the data to the backend using AJAX
            $.post(urljs+'vendors/dashboard/update_challan', {data: dataToSend, challanId: '<?php echo $challan_history_id ?>'}, function(response) {
                if(response.status=='success'){
                    swal_alert('Success!!', 'Updated Successfully', 'success', '');
                }else{
                    swal_alert('Error!!', 'Update Error', 'error', '');
                }
            }, 'json');
        });
    });
</script>

</body>

</html>