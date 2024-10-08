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
                <h1 class="page-title">Customers</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/customers">Customers</a></li>
                    <li class="breadcrumb-item">Add</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Add New Customer</div>
                            </div>
                             <div class="ibox-body">
                             <form role="form" id="addedit_customer_form">
                                <input type="hidden" name="customerId" value="">
                                <div class="row">
                                    <div class="col-lg-6">
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">First Name<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="firstName" type="text" placeholder="">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Last Name<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="lastName" type="text" placeholder="">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Email Address<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="email" type="text" placeholder="">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Phone<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="phone" type="text" placeholder="">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Password<span class="text-danger">*</span></label>
                                          <div class="col-sm-7 Togglepwd">
                                            <input class="form-control" name="password" type="password" placeholder="">
                                            <i class="fa fa-eye-slash pwd-input"></i>
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label">Confirm Password<span class="text-danger">*</span></label>
                                          <div class="col-sm-7 Togglepwd">
                                            <input class="form-control" name="cpassword" type="password" placeholder="">
                                            <i class="fa fa-eye-slash pwd-input"></i>
                                          </div>
                                      </div>
                                    </div>

                                    <div class="col-lg-6">                                      
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label col-form-label">Area/Lane address<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <textarea class="form-control" name="address"></textarea>
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">City<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="city" type="text" placeholder="">
                                          </div>
                                      </div>                                      
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">State<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <input class="form-control" name="state" type="text" placeholder="">
                                          </div>
                                      </div>                                   
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Country<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="country" type="text" placeholder="">
                                          </div>
                                      </div>                                   
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Postal Code<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                          <input class="form-control" name="postalCode" type="text" placeholder="">
                                          </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                          <label class="col-sm-4 col-form-label" class="col-sm-4">Status<span class="text-danger">*</span></label>
                                          <div class="col-sm-7">
                                            <select class="custom-select" name="status">
                                              <option value="1" selected>Active</option>
                                              <option value="0">In Active</option>
                                            </select>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-12">
                                      <div class="text-center mt-4">
                                          <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                                          <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo base_url()?>admin/customers'">Cancel</button>
                                      </div>
                                    </div>
                                </div>
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
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/customers.js"></script>
</body>
</html>
  