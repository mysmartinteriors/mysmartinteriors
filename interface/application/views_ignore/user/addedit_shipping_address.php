<?php 
$addressId='';
$customerId=get_userId();
$name='';
$phone='';
$apartment='';
$address='';
$city='Bengaluru';
$state='Karnataka';
$country='India';
$postalCode='';
$primaryAddress = '';
if($addressQ['status']=='success' && !empty($addressQ['data_list'])){
      $addrData=$addressQ['data_list'];
      $addressId=$addrData['id'];
      $customerId=$addrData['customerId'];
      $name=$addrData['name'];
      $phone=$addrData['phone'];
      $apartment=$addrData['apartment'];
      $address=$addrData['address'];
      $city=$addrData['city'];
      $state=$addrData['state'];
      $country=$addrData['country'];
      $postalCode=$addrData['postalCode'];
      $primaryAddress = $addrData['pri_address'];
  }
?>

<div class="modal-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
        <form id="addedit_ship_addr" role="form" method="post">
            <input type="hidden" name="customerId" value="<?php echo $customerId ?>">
            <input type="hidden" name="addressId" value="<?php echo $addressId ?>">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group required-field">
                    <label>Full name </label>
                    <input type="text" class="form-control form-input form-wide" name="name" value="<?php echo $name ?>">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group required-field">
                    <label>Phone number </label>
                    <input type="text" class="form-control form-input form-wide" name="phone" value="<?php echo $phone ?>">
                </div>
              </div>
            </div>

            <div class="row">
            <div class="col-lg-6">
                    <div class="form-group required-field">
                        <label class="col-form-label">House/Apartment Number</label>
                        <input type="text" class="form-control form-input form-wide"  name="apartment" value="<?php echo $apartment ?>">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group required-field">
                        <label class="col-form-label">Area/Lane address</label>
                        <input type="text" class="form-control form-input form-wide"  name="address" value="<?php echo $address ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group required-field">
                      <label class="col-form-label">Postal code</label>
                      <input type="text" class="form-control form-input form-wide" name="postalCode" value="<?php echo $postalCode ?>">
                  </div>
              </div>
                <div class="col-lg-6">
                    <div class="form-group required-field">
                        <label class="col-form-label">City</label>
                        <input type="text" class="form-control form-input form-wide" name="city" value="<?php echo $city ?>" >
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                  <div class="form-group required-field">
                      <label class="col-form-label">State</label>
                      <input type="text" class="form-control form-input form-wide"  name="state" value="<?php echo $state ?>" >
                  </div>
              </div>
              <div class="col-lg-6">
                  <div class="form-group required-field">
                      <label class="col-form-label">Country</label>
                      <input type="text" class="form-control form-input form-wide"  name="country" value="<?php echo $country ?>" >
                  </div>
              </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-steps-action d-flex" style="justify-content: space-between">
                        <!-- <button type="submit" class="btn btn-primary float-right" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Saving...">Save</button> -->
                        <label for="isPrimary">
                            <input type="checkbox" name="is_primary" id="isPrimary" <?php echo ($primaryAddress==1)?'checked':'' ?>>
                            Make Primary Address
                        </label>
                        <button type="submit" class="btn btn-primary float-right" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Saving...">Save</button>
                    </div><!-- End .checkout-steps-action -->
                </div><!-- End .col-lg-8 -->
            </div><!-- End .row -->
        </form>
      </div>
    </div>
  </div>
</div>