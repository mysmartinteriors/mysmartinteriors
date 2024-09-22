<?php
$id='';
$status=0;
$customerId='';
$code='';
$submitDate='';
if(!empty($support_enquiry)){
  foreach ($support_enquiry as $datas) {
    $id=$datas['id'];
    $customerId=$datas['customerId'];
    $code=$datas['code'];
    $status=$datas['status'];
    $submitDate=$datas['submitDate'];
  }
}
?>
<div id="joborder_upstatus">
  <div class="row">
    <div class="col-lg-12 left-side">
      <?php if($status==35){ ?>
      <div class="overlay"> 
        <span>Since the ticket is closed, you are not allowed to update the comment or status!!!</span>
      </div>
      <?php }else{ ?>
      <form class="modal-forms" id="ticket_reply_form">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="cid" value="<?php echo $customerId ?>">
        <div class="row">          
          <div class="col-12 col-lg-12">
            <label class="form-control-label"><b>Status</b></label>
            <select class="custom-select" name="status">
              <option value="34">Pending</option>
              <option value="35">Resolved</option>
            </select>
          </div>


          <?php if($status<=0){ ?>
          <div class="col-12 text-right mt-3">
              <button type="button" class="btn btn-primary btn-sm" disabled data-loading-text="<i class='fa fa-spinner fa-spin'></i> Updating...">Update</button>
          </div>
          <?php }else{ ?>
            <div class="col-12 text-right mt-3">
                <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Updating..."><i class="fa fa-paper-plane"></i> Update</button>
            </div>
          <?php } ?>
          </div>
      </form>
      <?php } ?>
    </div>
  </div>
</div>