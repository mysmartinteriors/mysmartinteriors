<?php
if (!empty($customerQ)) {
  // foreach ($customerQ->result() as $customer) {
  $customerId = $customerQ['id'];
  $firstName = $customerQ['firstName'];
  $lastName = $customerQ['lastName'];
  $email = $customerQ['email'];
  $password = $customerQ['password'];
  $phone = $customerQ['phone'];
  $address = $customerQ['address'];
  $city = $customerQ['city'];
  $state = $customerQ['state'];
  $country = $customerQ['country'];
  $postalCode = $customerQ['postalCode'];
  $status = $customerQ['status'];
  $subscriptionAmount = $customerQ['subscriptionAmount'];
  $subscriptionPoints = $customerQ['subscriptionPoints'];
  $referredBy = $customerQ['refered_by'];
  // }
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
          <li class="breadcrumb-item">Edit</li>
        </ol>
      </div>
      <!-- START PAGE CONTENT-->
      <div class="page-content">
        <div class="row">
          <div class="col-lg-12">
            <div class="ibox">
              <div class="ibox-head page-head-btns">
                <div class="ibox-title">Edit Customer</div>
              </div>
              <div class="ibox-body">
                <form role="form" id="addedit_customer_form">
                  <input type="hidden" name="customerId" value="<?php echo $customerId ?>">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">First Name<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="firstName" type="text" value="<?php echo $firstName ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Last Name<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="lastName" type="text" value="<?php echo $lastName ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Email Address<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="email" type="text" value="<?php echo $email ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Phone<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="phone" type="text" value="<?php echo $phone ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Password<span class="text-danger">*</span></label>
                        <div class="col-sm-7 Togglepwd">
                          <input class="form-control" name="password" id="cpassword" type="password"
                            value="<?php echo $password ?>">
                          <i class="fa fa-eye-slash pwd-input"></i>
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Confirm Password<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7 Togglepwd">
                          <input class="form-control" name="cpassword" type="password" value="<?php echo $password ?>">
                          <i class="fa fa-eye-slash pwd-input"></i>
                        </div>
                      </div>

                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label col-form-label">Area/Lane address<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <textarea class="form-control" name="address"><?php echo $address ?></textarea>
                        </div>
                      </div>

                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">City<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="city" type="text" value="<?php echo $city ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">State<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="state" type="text" value="<?php echo $state ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Country<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="country" type="text" value="<?php echo $country ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Postal Code<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="postalCode" type="text" value="<?php echo $postalCode ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Status<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <select class="custom-select" name="status">
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                          </select>
                          <script>$('[name=status]').val(<?php echo $status ?>)</script>
                        </div>
                      </div>
                      <?php if(empty($referredBy)){ ?>
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Referred By<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <?php 
                            $customers = $this->curl->execute('customers/list', 'GET');
                            // print_R($customers);
                            if($customers['status']=='success' && !empty($customers['data_list'])){
                          ?>
                            <select class="custom-select custom-select1" name="refered_by">
                              <option value="">--Select Referrer--</option>
                              <?php foreach($customers['data_list'] as $customer){ ?>
                                <option value="<?php echo $customer['id'] ?>"><?php echo $customer['phone']; ?></option>
                              <?php } ?>
                            </select>
                          <?php } ?>
                          <script>$('[name=status]').val(<?php echo $status ?>)</script>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="col-lg-12">
                      <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                        <button type="button" class="btn btn-secondary"
                          onclick="window.location.href='<?php echo base_url() ?>admin/customers'">Cancel</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

            </div>
          </div>

          <!-- Payment Details -->
          <div class="col-lg-12">
            <div class="ibox">
              <div class="ibox-head page-head-btns">
                <div class="ibox-title">Edit Subscription Payment Details</div>
              </div>
              <div class="ibox-body">
                <form role="form" id="addedit_customer_payment_form" submitdisabledcontrols="true">
                  <div class="row">

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Subscription Amount<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <?php 
                              $subscriptions = $this->curl->execute('subscription', 'GET');
                              // print_R($subscriptions);
                              if($subscriptions['status']=='success' && !empty($subscriptions['data_list'])){ ?>
                                <select name="subscriptionAmount" id="" class="form-control">
                                  <?php foreach($subscriptions['data_list'] as $subs){ ?>
                                    <option value="<?php echo $subs['id'] ?>"><?php echo $subs['name'] . ' | Amount '.$subs['basic_amount'].' | Wallet Points '.$subs['wallet_points'] ?></option>
                                  <?php } ?>
                                </select>
                              <?php }else{ ?>
                                <input class="form-control" name="subscriptionAmount" type="text" />
                              <?php } ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label" class="col-sm-4">Subscription Points<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="subscriptionPoints" type="text" />
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment ID<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="pCfPaymentId" type="text" placeholder="eg: 2825934280">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Details for ? <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="reference_table" type="text"
                            value="user_subscription">
                        </div>
                      </div>
                    </div>

                    <!-- <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Reference ID<span class="text-danger">*</span></label>
                        <div class="col-sm-7"> -->
                          <input hidden class="form-control" name="reference_id" value="<?php echo $customerId ?>" type="text"
                            >
                        <!-- </div>
                      </div>
                    </div> -->

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Response Status<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="pResponseStatus" type="text" value="success">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Order ID<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" placeholder="eg: order_6984096i5hN4HjLHJFHARiTHpsYYUUcVD"
                            name="pOrderId" type="text">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Entity<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="pEntity" type="text" value="payment">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Is Captured?<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="pIsCaptured" type="text" value="NO">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Order Amount<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="pOrderAmount" type="text" value=""
                            placeholder="Order Amount">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Group<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentGroup" type="text"
                            placeholder="eg: UPI/Card/Debit Card/Wallet">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Amount<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" placeholder="Paid Amount" name="paymentAmount" type="text">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Currency<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentCurrency" type="text" value="INR">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Status<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentStatus" type="text" value="SUCCESS">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Completed Time<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentTime" type="text" value="<?php echo cur_datetime() ?>"
                            >
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment Message<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentMessage" type="text" value="Payment Completed"
                            >
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group row mb-4">
                        <label class="col-sm-4 col-form-label">Payment JSON<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input class="form-control" name="paymentMethod" type="text" value=""
                            placeholder='eg: {"upi":{"channel":"link","upi_id":"9844021428@ybl"}}'>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-air mr-2">Add Missing Subscription Details</button>
                        <button type="button" class="btn btn-secondary"
                          onclick="window.location.href='<?php echo base_url() ?>admin/customers'">Cancel</button>
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
  <script>
    $(document).ready(function(){
      $(".custom-select1").select2();
    })
  </script>
</body>

</html> 