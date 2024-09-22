<div class="form-group inputGroup">
    <input class="form-control referValue" data-userId="<?php echo $userId ?>" placeholder="Enter <?php echo $message ?>" data-referType="<?php echo $type ?>" type="<?php echo ($type=='email')?'email':'text' ?>" />
    <button class="my-2 submitBtn btn btn-primary">Refer Now</button>
</div>


<script>
    $(document).ready(function(){
        $(".submitBtn").on('click', function(e){
            e.preventDefault();
            const type = $('.referValue').attr('data-referType');
            const referValue = $(".referValue").val();
            $.post(urljs+'account/referNow', {type, referValue}, function(response){
                if(response.status=='success'){
                    swal('Success', response.message, 'success')
                    setTimeout(()=>{
                        location.reload();
                    }, 3000)
                }else{
                    swal('Fail', response.message, 'error')
                }
            }, 'json')
        })
    })
</script>