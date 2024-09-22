<?php
if (!empty($order_data)) {
    $customerId = $order_data['customerId'];
    $name = $order_data['name'];
    $email = $order_data['email'];
    $phone = $order_data['phone'];
    $address = $order_data['address'];
    $orderId = $order_data['id'];
    $code = $order_data['code'];
    $createdDate = $order_data['createdDate'];
    $status_id = $order_data['status'];
    $status = $order_data['status_name'];
    $status_color = $order_data['status_color_name'];
    $subtotal=$order_data['subtotal'];
    $taxAmt=$order_data['tax'];
    $total_amount=$order_data['total_amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">  
    <!-- PLUGINS STYLES-->
    <link href="<?php echo base_url() ?>ui/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />  
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
                <h1 class="page-title">Orders</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/orders">Bookings</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content orders-page">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Customer Details</div>
                            </div>
                            <div class="ibox-body customer-data">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p><strong>Name:</strong> <?php echo $name ?></p>
                                        <p><strong>Email:</strong> <?php echo $email ?></p>
                                        <p><strong>Phone:</strong> <?php echo $phone ?></p>
                                        <p><strong>Address:</strong>  <?php  echo $address?></p>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <p><strong>Order ID:</strong>  <?php  echo $code?></p>
                                        <p><strong>Date:</strong> <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s",$createdDate); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Order Details</div>
                            </div>
                            <div class="ibox-body">
                              <form role="form" id="edit_ubook_form">
                                <input type="hidden" name="id" value="<?php echo $orderId ?>">
                                <input type="hidden" name="code" value="<?php echo $code ?>">
                                <input type="hidden" name="cId" value="<?php echo $customerId ?>">
                                <?php if(!empty($product_details)){ ?>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Product</th>
                                                        <th>Price<small>(<i class="fa fa-rupee-sign"></i>)</small></th>
                                                        <th>Quantity<small>(No's)</small></th>
                                                        <th>Tax<small>(%)</small></th>
                                                        <th>Total
                                                            <small>(<i class="fa fa-rupee-sign"></i>)</small>
                                                            <span data-toggle="tooltip" class="data-tooltip" data-placement="top" title="Inclusive of total price with tax amount" data-original-title=""><i class="fa fa-info-circle"></i></span> 
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
                                                    $i=1;
                                                    foreach($product_details as $product_data){  
                                                  ?>
                                                    <tr>
                                                        <td><?php echo $i ?></td>
                                                        <td>
                                                          <?php if(!empty($product_data['product_image'])){ ?>
                                                <img src="<?php echo base_url().$product_data['product_image'] ?>" class="img-sm">
                                                <?php } ?>
                                                            <p><?php echo $product_data['name'] ?>
                                                            <?php if(!empty($product_data['parent_name'])){ ?>
                                                                <small> <?php echo '<br>'.$product_data['parent_name'] ?></small>
                                                            <?php } ?>
                                                            <?php if(!empty($product_data['category_name'])){ ?>
                                                                <small> <?php echo '->'.$product_data['category_name'] ?></small>
                                                            <?php } ?>
                                                </p>
                                                        </td>
                                                        <td>
                                                            <?php echo '<i class="fa fa-rupee-sign"></i> '.$product_data['price'] ?>
                                                        </td>
                                                        <td>                                                         
                                                           <?php echo $product_data['qty']." No's"?>
                                                        </td>
                                                        <td>
                                                            <?php echo $product_data['tax'].'%' ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<i class="fa fa-rupee-sign"></i> '.$product_data['total'] ?>
                                                        </td>
                                                    </tr>

                                                    
                                                <?php $i++; } ?>
                                                </tbody>
                                            </table>
                                          </div>
                                    </div>
                                </div>
                                <div class="tbl-footer mb-3">
                                    <div class="row">
                                        
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Delivery Boy</label>
                                                <select class="form-control custom-select" name="delivery_by">
                                                    <option value="1">Pragna</option>
                                                    <option value="2">Dhurba</option>
                                                    
                                                </select>
                                                <script>$('[name=delivery_by]').val(<?php echo $delivery_by ?>);</script>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Payment Status</label>
                                                <select class="form-control custom-select" name="payment_status">
                                                    <option value="-1">Paid</option>
                                                    <option value="0">Unpaid</option>
                                                </select>
                                                <script>$('[name=payment_status]').val(<?php echo $payment_status ?>);</script>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Status</label>
                                                <select class="form-control custom-select" name="status">
                                                    <option value="25">Pending</option>
                                                    <option value="26">Cancel Order</option>
                                                    <option value="27">Dispatched</option>
                                                    <option value="28">Complete & Generate Bill</option>
                                                </select>
                                                <script>$('[name=status]').val(<?php echo $status ?>);</script>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Grand Total<small>(<i class="fa fa-rupee-sign"></i>)</small></label>
                                                <input class="form-control" name="total_amount" value="<?php echo $total_amount ?>" readonly>
                                            </div>
                                        </div>
                                         <div class="col-lg-6">
                                            <p><small><span class="text-danger">*</span>If the status is selected as cancel order, you may no longer available to edit this order again and customer will get a notification regarding the cancellation!</small></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary btn-air mr-2">Update</button>
                                                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo base_url()?>admin/orders'">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <?php }?>
                            </form>
                          </div>               
                        </div>
                    </div>                    
                </div>
            </div>
            <!-- END PAGE CONTENT-->
            <?php echo $footer ?>
        </div>
    </div>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/orders.js"></script>
</body>
</html>
  