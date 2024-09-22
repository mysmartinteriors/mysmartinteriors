<?php
$id='';
$code='';

if(!empty($orderData)){
  // foreach ($orderData as $datas) {
    $id=$orderData['id'];
    $code=$orderData['orderId'];
  // }
}
?> 
<div id="joborder_upstatus">
  <div class="row">
    <div class="col-lg-12 left-side">
      <form class="modal-forms" id="assign_order_form">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="code" value="<?php echo $code ?>">
        <div class="row">  
          <div class="col-12 col-lg-12">
            <label class="form-control-label">Payment</label>
            <select class="custom-select" name="payment_status">
                <option value="1">Paid</option>
            </select>
          </div>

          <!-- <div class="col-12 col-lg-12">
            <label class="form-control-label">Payment Method</label>
            <select class="custom-select" name="paymentMethod">
                <option value="pay_online" <?php //echo $orderData['paymentMethod']=='pay_online'?'selected':'' ?>>Online Payment</option>
                <option value="cash_on_delivery" <?php //echo $orderData['paymentMethod']=='cash_on_delivery'?'selected':'' ?>>Cash On Delivery</option>
            </select>
          </div> -->

          <div class="col-12 col-lg-12">
            <label class="form-control-label">Delivery Status</label>
            <select class="custom-select" id="delivery" name="status">
              <option value="">--Select--</option>
                <option value="28">Delivered</option>
                <option value="26">Cancelled</option>
            </select>
          </div>

          <div class="col-12 col-lg-12 comments d-none">
            <label for="" class="form-control-label">Comments</label>
            <input class="form-control" type="text" name="comments" value = "" required>
          </div>

            <div class="col-12 text-right mt-3">
                <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Updating..."><i class="fa fa-paper-plane"></i> Update</button>
            </div>
          </div>
      </form>

    </div>
  </div>
</div>

<script>
  $("#delivery").on("click", function(){
    var delId = $('#delivery :selected').val();
    if(delId == "26"){
      $(".comments").removeClass("d-none");
    }else{
      $(".comments").addClass("d-none");
    }
  })
</script>