<?php
$id='';
$status=0;
$customerId='';
$code='';
$submitDate='';
foreach ($mainQ->result() as $datas) {
  $id=$datas->id;
  $customerId=$datas->customerId;
  $code=$datas->code;
  $status=$datas->status;
  $submitDate=$datas->submitDate;
}
?>
<div id="joborder_upstatus">
  <div class="row">
    <div class="col-lg-4 left-side">
      <?php if($status<=0){ ?>
      <div class="overlay">
        <span>Since the ticket is closed, you are not allowed to update the comment or status!!!</span>
      </div>
      <?php }else{ ?>
      <form class="modal-forms" id="ticket_reply_form">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <div class="row">
          <div class="col-12 col-lg-12">
            <label class="form-control-label mt-0"><b>Reply Subject</b></label>
              <input type="text" name="subject" class="form-control">
          </div>
          <div class="col-12 col-lg-12 comments-section">
            <label class="form-control-label"><b>Your message</b></label>
              <textarea name="message" class="textEditor form-control" id="message"></textarea>
          </div>

          <?php if($status<=0){ ?>
          <div class="col-12 text-right mt-3">
              <button type="button" class="btn btn-primary btn-sm"  disabled="" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Updating...">Update</button>
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
    <div class="col-lg-8 right-side">
      <h3>Support enquiry status history</h3>
      <div class="tickets_view"> 
        <div class="p_status_scroller">               
          <div class="messaging">
              <div class="inbox_msg">
                  <div class="mesgs">
                    <div id="ticketsChatTbl">
                      <div class="msg_history">
                        <?php
                          $loginId=get_userId();
                          if($tblQ->num_rows()>0){
                            foreach ($tblQ->result() as $comments) { 
                            if($comments->from_type==0){
                              $from_name='Admin';
                            }else{
                              $from_name=ucfirst($comments->firstName);
                            }                           
                        ?>
                        <div class="incoming_msg">
                            <div class="received_msg">
                                <div class="msg_details">
                                  <div class="comment_details">
                                    <p class="comment_user">
                                      <?php echo $from_name ?>
                                      <?php echo ' - '.ucfirst($comments->subject); ?>
                                    </p>
                                    <p class="comment_time">
                                      <?php
                                        $chatTime=date('h:i:s A | d M Y', strtotime($this->admin->getCustomDate("%M-%d %H:%i:%s",strtotime($comments->reply_date))));
                                      echo '@ '.$chatTime;
                                    ?>
                                    </p>
                                  </div>
                                  <div class="comment_text">
                                    <?php echo ucfirst($comments->message); ?> 
                                  </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            }
                          }else{
                        ?>
                        <p>No conversations found...</p>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>