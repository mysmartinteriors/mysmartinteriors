<div class="container">
<form id="refer_earn">

<div class="div_res"></div>
    <div class="form-group">
        <label for="">Refer Via SMS </label>
        <input type="text" name="mobile" class="form-control" id="">
    </div>
    <div class="form-group">
        <label for="">Refer Via Whatsapp <i class="fa fa-whatsa"></i></label>
        <input type="text" name="whatsapp" class="form-control" id="">
    </div>
    <div class="form-group">
        <label for="">Refer Via Email <i class="fa fa-whatsa"></i></label>
        <input type="email" name="email" class="form-control" id="">
    </div>
    <input type="hidden" name="token" id=""  class="invoice_id" value="<?php echo $customer_data['token']?>">
    <input type="hidden" name="customers_id" id=""  class="customers_id" value="<?php echo $customer_data['id']?>">
    <!-- <div class="via_method"> -->
    <!-- <div class="form-check d-flex ">
        <div class="row">
            <div class="col-4">
                <input class="form-check-input share_invoice " type="radio" name="via_method" id="via_method1" value="whatsapp">
                <label class="form-check-label invoice_share_icons" for="via_method1"> <i class="ri-whatsapp-fill align-bottom  fs-4 text-success me-1"></i><span class="text-mute  invoice_share_icons">Whatsapp</span> </label>
            </div>
            <div class="col-8">
                <input type="text" name="whatsapp_number" id="" class="whatsapp form-control ms-2" placeholder="Enter Your whatsapp Number" style="display:none">
            </div>
        </div>        
    </div>
    <div class="form-check d-flex ">
        <div class="row">
            <div class="col-4">
                <input class="form-check-input share_invoice " type="radio" name="via_method" id="via_method2" value="email">
                <label class="form-check-label invoice_share_icons" for="via_method2"><i class="ri-mail-fill align-fs-4 text-danger me-1"></i><span class="text-mute  invoice_share_icons">Email</span></label>
            </div>
            <div class="col-8">
                <input type="text" name="email" id="" class="email form-control ms-2" placeholder="Enter Your Email ID" style="display:none">
            </div>
        </div>
    </div> -->

<div class="submit_form mb-2" style="text-align: center;">
<button type="submit" class="btn btn-primary">Send</button>
</div>
<!-- </div> -->
</form>

