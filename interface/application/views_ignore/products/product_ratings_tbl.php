<?php 
$reviewCount=0;
$ratingCount=0;
$avgRating=0;
    if($ratingQ->num_rows()>0){
        if($ratingQ->row()->reviewContent!=''){
            $reviewCount=count($ratingQ->row()->reviewContent);
        }
        foreach ($ratingQ->result() as $ratings) {
            $avgRating = ($avgRating+$ratings->ratingValue);
        }
        $ratingCount=$ratingQ->num_rows();
        $avgRating = ($avgRating)/$ratingCount;
?>
<div class="add-product-review">
    <div class="prd-rated-lists">
        <div class="row">
            <div class="col-lg-12">
                <div class="prd-tot-rating">
                    <h5>
                        <span class="prdTotRating"><?php echo round($avgRating) ?> <i class="fa fa-star"></i></span>
                        Total <?php echo $ratingCount ?> Ratings and <?php echo $reviewCount ?> Reviews
                    </h5>
                </div>
            </div>
        </div>
        <?php
            foreach ($ratingQ->result() as $ratings) {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="rate-lists">
                    <h3 class="rated-review">
                        <span class="prdTotRating"><?php echo $ratings->ratingValue ?> <i class="fa fa-star"></i></span>
                        <?php echo ucfirst($ratings->reviewSummary) ?>
                    </h3>
                    <p class="usr-details">
                        <strong><?php echo $ratings->firstName.' '.$ratings->lastName ?></strong>
                        <span><?php echo humanTiming(strtotime($ratings->ratedDate)).' ago' ?></span>
                    </p>
                    <p class="user-review"><?php echo ucfirst($ratings->reviewContent) ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php }else{ ?>
<div class="collateral-box form-inline">
    <p class="text-uppercase heading-text-color font-weight-bold">No ratings yet...
    </p>
</div>

<?php } ?>

<?php 
    //if($userRating->num_rows()>0){
?>
<!--
<div class="add-product-review">
    <div class="row">
        <div class="col-lg-12">
            <h5></h5>
        </div>
    </div>
</div>
<?php //}else{?>
<div class="collateral-box">
    <h3 class="text-uppercase heading-text-color font-weight-semibold">WRITE YOUR OWN REVIEW</h3>
</div>
<div class="add-product-review">
        <div class="row">
            <div class="col-lg-6">  

                <div class="form-group">
                    <p>
                        How much you rate this product? <span class="required">*</span>
                        <input id="prdRateBlock" data-rateit-valuesrc="value" data-rateit-resetable="false" data-rateit-value="" name="ratingValue">
                        <span class="prdrateit" data-rateit-backingfld="#prdRateBlock" data-rateit-resetable="false"  data-rateit-step="1"></span>
                    </p> 
                </div> 
                <div class="form-group">
                    <label>Your Name <span class="required">*</span></label>
                    <input type="text" class="form-control form-control-sm" value="<?php echo get_uFullName();?>" disabled>
                </div>   
                <div class="form-group">
                    <label>Your Email <span class="required">*</span></label>
                    <input type="text" class="form-control form-control-sm" value="<?php echo get_uEmail();?>" disabled>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Summary of Your Review <span class="required">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="reviewSummary">
                </div>
                <div class="form-group mb-2">
                    <label>Review</label>
                    <textarea cols="4" rows="3" class="form-control form-control-sm" name="reviewContent"></textarea>
                </div>
            </div>
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn btn-primary" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Submitting Review...">Submit Review</button>
            </div>
        </div> 
</div>
<?php //} }else{?>
<div class="collateral-box form-inline">
    <p class="text-uppercase heading-text-color font-weight-bold">WISH TO WRITE A REVIEW?
        <a href="javascript:void(0);" class="login-link">Login Now!</a>
    </p>
</div>
<?php //} ?>
-->