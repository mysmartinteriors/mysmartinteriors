<?php
if (!empty($order_data)) {
    $name = $order_data['name'];
    $email = $order_data['email'];
    $phone = $order_data['phone'];
    $address = $order_data['address'];
    $orderId = $order_data['id'];
    $code = $order_data['code'];
    $createdDate = $order_data['createdDate'];
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
                    <li class="breadcrumb-item">View</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content orders-page">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Customer Details</div>
                                <button class="btn btn-purple btn-fix btn-air" type="button" onclick="window.open('<?php echo base_url()?>admin/orders/pdf/<?php echo $orderId ?>')"  title="View & Download PDF">
                                    <span class="btn-icon"><i class="ti-printer"></i>Print</span>
                                </button>
                            </div>
                            <div class="ibox-body customer-data">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p><strong>Name:</strong> <?php echo $name ?></p>
                                        <p><strong>Email:</strong> <?php echo $email ?></p>
                                        <p><strong>Phone:</strong> <?php echo $phone ?></p>
                                        <p><strong>Address:</strong> <?php echo $address ?></p>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <p><strong>Order ID:</strong>  <?php  echo $code?></p>
                                        <p><strong>Date:</strong> <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s",$createdDate); ?></p>
                                        <p>
                                            <strong>Status:</strong> 
                                            <?php if($status<=0){ ?>
                                                <span class="badge badge-danger badge-pill">Pending</span>
                                            <?php }else if($status==1){ ?>
                                                <span class="badge badge-info badge-pill">Payment Due</span>
                                            <?php }else if($status==2){ ?>
                                                <span class="badge badge-info badge-pill">Dispatched</span>
                                            <?php }else if($status==3){ ?>
                                                <span class="badge badge-success badge-pill">Served</span>
                                            <?php } ?>
                                        </p>
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
                                <?php if(!empty($product_details)){ ?>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Tax</th>
                                                        <th>Total</th>
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
                                                <?php 
                                                    $i++;
                                                    } 
                                                ?>
                                                <tr>
                                                    <td style="text-align:right;font-weight:800;" colspan="5">Subtotal</td>
                                                    <td style="font-weight:800;"><i class="fa fa-rupee-sign"></i> <?php echo $subtotal ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;font-weight:800;" colspan="5">Tax Amount</td>
                                                    <td style="font-weight:800;"><i class="fa fa-rupee-sign"></i> <?php echo $taxAmt ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;font-weight:800;" colspan="5">Grand Total</td>
                                                    <td style="font-weight:800;"><i class="fa fa-rupee-sign"></i> <?php echo $total_amount ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
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
  