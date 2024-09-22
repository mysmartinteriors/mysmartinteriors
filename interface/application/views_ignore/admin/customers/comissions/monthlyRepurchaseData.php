<table class="table table-bordered">
    <thead>
        <th>Sl.No</th>
        <th>Levela</th>
        <th>Earning</th>
        <th>Purchased By</th>
        <th>Order Purchased On</th>
        <th>Order Purchased Amount</th>
        <th>Validity</th>
        <th>Action</th>
    </thead>
    <tbody id="referralDataView">
        <?php
        $i = 1;
        foreach ($data_list as $repurchasedCustomer) { ?>
            <tr>
                <td>
                    <?php echo $i ?>
                </td>
                <td>
                    <?php if (!empty($repurchasedCustomer['level'])) {
                        echo "Level " . $repurchasedCustomer['level'];
                    } ?>
                </td>
                <td>
                    <?php if (!empty($repurchasedCustomer['amount'])) {
                        echo $repurchasedCustomer['amount'];
                    } ?>
                </td>
                <td>
                    <?php echo $repurchasedCustomer['ref_by_first_name'] . ' ' . $repurchasedCustomer['ref_by_last_name'] ?>
                </td>
                <td>
                    <?php echo $repurchasedCustomer['createdDate'] ?>
                </td>
                <td>
                    <?php echo $repurchasedCustomer['orderAmount'] ?>
                </td>
                <td class="test"><span class="badge bg-<?php echo ($repurchasedCustomer['status'] == 1) ? 'success' : 'danger' ?>">
                        <?php echo ($repurchasedCustomer['status'] == 1) ? 'Expired' : 'Settled' ?>
                    </span></td>
                <td>
                    <input type="checkbox" <?php echo ($repurchasedCustomer['status']==2)?'disabled':'' ?> class="form-control referralComissionDetails" data-id="<?php echo $repurchasedCustomer['id'] ?>" />
                </td>
            </tr>
            <?php $i++;
        } 
        ?>
    </tbody>
</table>

<div class="row" id="settleButtonRow" style="display: none">
    <div class="col-12">
        <button id="settleReferralCommission" class="btn btn-success">Settle Repurchase Comission</button>
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
      $.post(urljs+'admin/customers/settleRepurchaseAmount', {ids: checkedIds, customerId: '<?php echo $customerId ?>'}, function(response) {
        if(response.status=='success'){
            swal(response.message, 'success')
            setTimeout(() => {
                location.reload();
            }, 3000);
        }else{
            swal('Failed', response.message, 'warning');
        }
      }, 'json');
    });

  });
</script>
</script>