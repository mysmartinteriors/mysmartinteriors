<div class="row">
    <div class="col-12 my-2">
        <?php if($status=='SUCCESS'){ ?>
            <i class="fa fa-check-cirle"></i>
        <?php }else if($status=='PENDING'){ ?>

        <?php }else{ ?>

        <?php } ?>
        <h3>Payment Status : <?php echo $status; ?></3>
        <h3>Payment Message : <?php echo $message; ?></3>
    </div>
</div>