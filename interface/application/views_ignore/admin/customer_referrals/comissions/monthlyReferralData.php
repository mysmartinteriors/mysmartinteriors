<table class="table table-bordered">
    <thead>
        <th>Sl.No</th>
        <th>Level</th>
        <th>Earning</th>
        <th>Subscribed By</th>
        <th>Subscription Purchased On</th>
        <th>Subscription Amount</th>
        <th>Validity</th>
        <th>Action</th>
    </thead>
    <tbody id="referralDataView">
        <?php
        $i = 1;
        foreach ($data_list as $referedCustomers) { ?>
            <tr>
                <td>
                    <?php echo $i ?>
                </td>
                <td>
                    <?php if (!empty($referedCustomers['level'])) {
                        echo "Level " . $referedCustomers['level'];
                    } ?>
                </td>
                <td>
                    <?php if (!empty($referedCustomers['amount'])) {
                        echo $referedCustomers['amount'];
                    } ?>
                </td>
                <td>
                    <?php echo $referedCustomers['ref_by_first_name'] . ' ' . $referedCustomers['ref_by_last_name'] ?>
                </td>
                <td>
                    <?php echo $referedCustomers['createdDate'] ?>
                </td>
                <td>
                    <?php echo $referedCustomers['subscriptionAmount'] ?>
                </td>
                <td class="test"><span class="badge bg-<?php echo ($referedCustomers['status'] == 1) ? 'success' : 'danger' ?>">
                        <?php echo ($referedCustomers['status'] == 1) ? 'Settled' : 'Not Settled' ?>
                    </span></td>
                <td>
                    <input type="checkbox" class="form-control referralComissionDetails" <?php echo ($referedCustomers['status']!=1)?'disabled':'' ?> data-id="<?php echo $referedCustomers['id'] ?>" />
                </td>
            </tr>
            <?php $i++;
        } 
        ?>
    </tbody>
</table>

<div class="row" id="settleButtonRow" style="display: none">
    <div class="col-12">
        <button id="settleReferralCommission" class="btn btn-success">Settle Referral Comission</button>
    </div>
</div>

<script>
  $(document).ready(function() {
    $('.referralComissionDetails').change(function() {
      if ($('.referralComissionDetails:checked').length > 0) {
        $('#settleButtonRow').show();
      } else {
        $('#settleButtonRow').hide();
      }
    });

    $('#settleReferralCommission').click(function() {
      var checkedIds = [];
      $('.referralComissionDetails:checked').each(function() {
        checkedIds.push($(this).data('id'));
      });
      // Send data to your API
      $.post(urljs+'admin/customers/settleReferralsAmount', {ids: checkedIds, customerId: '<?php echo $customerId ?>'}, function(response){
        if(response.status=='success'){
            swal(response.message, '', 'success')
            setTimeout(() => {
                location.reload();
            }, 3000);
        }else{
            swal(response.message, '', 'warning');
        }
      }, 'json');
    });
  });
</script>
</script>