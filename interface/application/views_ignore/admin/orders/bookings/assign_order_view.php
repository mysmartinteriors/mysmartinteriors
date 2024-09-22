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
          <!-- <div class="col-12 col-lg-12">
            <label for="" class="form-control-label">Comments</label>
            <input class="form-control" type="text" name="comments" value = "<?php echo $comments ?>">
          </div>         -->
          <div class="col-12 col-lg-12">
            <label class="form-control-label">Select Delivery Boy <span class="text-danger">*</span></label>
            <select class="custom-select" name="delivery_boy" id="delivery">
              <?php if(isset($deliveryBoys)){
                if(!empty($deliveryBoys) && is_array($deliveryBoys)){ 
                  foreach ($deliveryBoys as $deliveryBoy) {                  
                  ?>
                  <option value="<?php echo $deliveryBoy['id'] ?>"><?php echo $deliveryBoy['name'] . ' - ' . $deliveryBoy['phone']  ?>
                <?php if(!empty($deliveryBoy['availability'])){
                  echo " - " . ucwords($deliveryBoy['availability']);
                } ?>
                </option>

                <?php } }
              } ?>
            </select>
          </div>
          <div id="error"></div>

            <div class="col-12 text-right mt-3">
                <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Updating..."><i class="fa fa-paper-plane"></i> Update</button>
            </div>
          </div>
      </form>

    </div>
  </div>
</div>