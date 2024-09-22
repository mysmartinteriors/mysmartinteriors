<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo $title ?></title>
      <meta name="keywords" content="" />
      <meta name="description" content="">
      <meta name="author" content="">      
      <!-- Plugins CSS File -->
      <?php echo $commonCss ?>
   </head>
   <body>
      <div class="page-wrapper">
         <!-- start header-->
         <?php echo $header_main ?>
         <!-- End .header -->

         <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url()?>"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?php echo base_url()?>account">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Addresses</li>
                    </ol>
                </div><!-- End .container -->
            </nav>
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-lg-3">
                        <div class="widget widget-dashboard">
                           <ul class="list">
                                <li><a href="<?php echo base_url()?>account/dashboard">Dashboard</a></li>
                                <li><a href="<?php echo base_url()?>account/myprofile">Edit Profile </a></li>
                                <li><a href="<?php echo base_url()?>account/myorders">My Orders</a></li>
                                <li class="active"><a href="<?php echo base_url()?>account/myaddresses">Address List</a></li>
                                <li><a href="<?php echo base_url()?>account/wallets">Wallet</a></li>
                                <li><a href="<?php echo base_url()?>account/referals">Referral Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/repurchase">Repurchase Bonus</a></li>
                                <li><a href="<?php echo base_url()?>account/logout">Logout</a></li>
                            </ul>
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                    <div class="col-lg-9 order-lg-last dashboard-content">
                        <div class='d-flex justify-content-between align-items-center address_edit_sect'>
                            <h2 class='text-center'>Manage your shipping address</h2>
                            <button type="button" class="btn btn-primary btn-shipaddr btn-sm pull-right mb-1" data-id=""><i class="fa fa-plus"></i> Add New</button>
                        </div>
                        <div id="my_addr_tbl">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl</th>
                                            <th>Address</th>
                                            <th>Is Primary?</th>
                                            <th>Action</th>       
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if($billAddrQ['status']=='success' && !empty($billAddrQ['data_list'])){
                                                $i=1;
                                                foreach ($billAddrQ['data_list'] as $row) { 
                                            ?>
                                        <tr>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo ucfirst($row['name']).', '.$row['apartment'].', '.$row['address'].', '.$row['city'].', '.$row['state'].', '.$row['country'].'-'.$row['postalCode'].'<br>Ph:'.$row['phone'] ?></td>
                                            <td>
                                                <?php 
                                                if($row['pri_address']==1){echo '<span class="badge badge-success">Yes</span>';}
                                                else{echo '<span class="badge badge-danger">No</span>';}
                                                ?>                                                    
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-actions-sm edit-action btn-edit btn-shipaddr" data-id="<?php echo $row['id'] ?>"><i class="fa fa-pencil"></i></button>
                                                <?php if($row['status']==64 && $row['pri_address']!=1){ ?>
                                                    <button type="button" class="btn btn-actions-sm del-action btn-delAddr" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                                <?php if($row['pri_address']==2){?>
                                                    <button type="button" class="btn btn-actions-sm stat-action btn-priAddr" data-id="<?php echo $row['id'] ?>"><i class="fa fa-check"></i></button>
                                                <?php  } ?>
                                            </td>
                                        </tr>
                                        <?php $i++; } }else{ ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Seems you do not have any shipping address...</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- End .col-lg-9 -->        
                </div>

        </main><!-- End .main -->
         <!-- End .main -->
         <?php echo $footer ?>
         <!-- End .footer -->
      </div>
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
      
      <?php echo $commonJs ?>

   </body>
</html>