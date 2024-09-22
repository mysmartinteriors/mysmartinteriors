<div class="container">
    <form id="share_invoice">

        <div class="div_res"></div>
        <h3 class="via_message mt-2" style="border-bottom:1px solid black">Refer and Earn Via Whatsapp or Email </h3>
        <div class="via_method">
            <p>
                <input type="hidden" name="token" id="" class="invoice_id" value="<?php echo $customer_data['token'] ?>">
                <input type="hidden" name="customers_id" id="" class="customers_id"
                    value="<?php echo $customer_data['id'] ?>">
            <div class="form-check d-flex ">
                <div class="row">
                    <div class="col-4">
                        <input class="form-check-input share_invoice " type="radio" name="via_method" id="via_method1"
                            value="whatsapp">
                        <label class="form-check-label invoice_share_icons" for="via_method1"> <i class="ri-whatsapp-fill align-bottom  fs-4 text-success ml-2"></i><span
                                class="text-mute  invoice_share_icons"> Whatsapp</span> </label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="whatsapp_number" id="" required class="whatsapp form-control ms-2"
                            placeholder="Enter whatsapp Number" style="display:none">
                    </div>
                </div>

            </div>
            </p>
            <p>
            <div class="form-check d-flex ">
                <div class="row">
                    <div class="col-4">
                        <input class="form-check-input share_invoice" type="radio" name="via_method" id="via_method2"
                            value="email">
                        <label class="form-check-label invoice_share_icons" for="via_method2"><i class="ri-mail-fill align-
        fs-4 text-danger ml-2"></i><span class="text-mute  invoice_share_icons">Email</span></label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="email" id="" class="email form-control ms-2" required placeholder="Enter Email Add" style="display:none">
                    </div>
                </div>
            </div>
            </p>
        </div>
        <div class="submit_form mb-2 " style="text-align: right;">
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
</div>
</form>