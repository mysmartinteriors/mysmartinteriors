<?php if(get_userId()){ ?>
<div class="row p-3">
    <div class="col-12">
        <h2 class="text-center text-primary" style="text-decoration: underline;">Please Choose Refer Method</h2>
    </div>
    <div class="col-12 d-flex justify-content-center">
            <a href="" style="text-decoration: none;" class="d-flex flex-column align-items-center referBtn" data-type="whatsapp" data-message="whatsapp number">
                <i class="fa fa-whatsapp fa-2x text-success"></i>
                <p>Whatsapp</p>
            </a>
            <a href="" style="text-decoration: none;" class="mx-5 d-flex flex-column align-items-center referBtn" data-type="email" data-message="email">
                <i class="fa fa-envelope fa-2x text-primary"></i>
                <p>Email</p>
            </a>
            <a href="" style="text-decoration: none;" class="d-flex flex-column align-items-center referBtn" data-type="sms" data-message="mobile number">
                <i class="fa fa-comment fa-2x text-success"></i>
                <p>SMS</p>
            </a>
    </div>
    <div class="col-12">
        <div id="inputElement"></div>
    </div>
</div>
<?php }else{ ?>
    <div class="row p-3">
        <div class="col-12">
            <h5 class="text-center text-danger">You're not logged in. Please <a class="text-primary" href="<?php echo base_url() ?>account">Click Here</a> to login</h5>
        </div>
    </div>
<?php } ?>

<script>
    $(document).ready(function(){
        referInput();
    })
    const referInput = ()=>{
        $(".referBtn").on('click', function(e){
            e.preventDefault();
            const message = $(this).attr('data-message');
            const type = $(this).attr('data-type')
            $.post(urljs+'account/get_refer_input', {message, type}, function(response){
                if(response.status=='success'){
                    $("#inputElement").html(response.message)
                }else{
                    swal('Error', response.message, 'error')
                }
            }, 'json')
            submitRefer();
        })
    }

    const submitRefer = () => {
        $(".submitBtn").on('cllick', function(){
            console.log("Submit Clicked");
            
        })
    }
</script>