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
                                            <div class="col-sm-10 col-11">
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
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($rowData['products'])) { ?>
                                                            <?php $i = 1; ?>
                                                            <?php foreach ($rowData['products'] as $product) { ?>
                                                                <tr>
                                                                    <td><?php echo $i ?></td>
                                                                    <td><?php echo $product['product_name'] ?></td>
                                                                    <td><?php echo $product['product_quantity'] ?></td>
                                                                    <td><?php echo $product['product_unit'] ?></td>
                                                                    <td>
                                                                        <span class="badge <?php echo $product['product_status_color_name'] ?>">
                                                                            <?php echo ($product['product_status_name']) ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="tbl-action-holder">
                                                                            <?php if($rowData['status']!=60){ ?>
                                                                            <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-deleteData" data-name = "<?php echo $product['product_name'] ?>"
                                                                                data-id="<?php echo $product['id']; ?>" title="Remove">
                                                                                <i class="ti-trash"></i>
                                                                            </button>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                    </tr>
                                                                    <?php $i++;
                                                            } ?>
                                                            <?php } else { ?>

                                                            <?php } ?>
                                                    </tbody>
                                                </table>
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
                $(".btn-deleteData").on('click', function(e){
                    e.preventDefault();
                    const productId = $(this).attr('data-id');
                    const productName = $(this).attr('data-name');
                    $.post(urljs+'admin/vendors/delete_challan_history_product', {productId}, function(response){
                        if(response.status=='success'){
                            swal_alert('Success!',productName+' deleted successfully','success','');
                        }else{
                            swal('Error', productName+' couldn\'t be deleted', 'error')
                        }
                    }, 'json')
                })
            </script>
            <!-- <script src="<?php //echo base_url(); ?>ui/assets/js/my-scripts/vendors_challan_history.js"></script> -->
</body>

</html>