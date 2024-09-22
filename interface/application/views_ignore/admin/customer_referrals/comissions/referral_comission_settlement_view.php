<div class="row">
    <div class="col-12">
        <h6>Earned Comission =  <?php echo 'Rs. '.$data_list['totalReferralComission'] ?></h6>
        <h6>Date Range =  <?php echo $data_list['daterange'] ?> </h6>
        <h6>Are you sure you want to settle this amount?</h6>
        <div class="col-12">
            <button class="btn btn-success settle" data-id="<?php echo $userId ?>">Settle Amount</button>
        </div>
    </div>
</div>

<script>
    $(".cancel").on('click', function(e){
        e.preventDefault();
        const customerId = $(this).attr('data-id');

    })

    $(".settle").on('click', function(e){
        e.preventDefault();
        const customerId = $(this).attr('data-id');
        console.log(customerId + ' is the customerId');
        $.post(urljs+'admin/customers/settleReferralsAmount', {customerId}, function(response){
            if(response.status=='success'){
                swal('Success', response.message, 'success');
                location.reload();
            }else{
                swal('Error', response.message, 'error')
            }
        }, 'json')
    })
</script>