<?php 
 if($productQ['status']=='success' && !empty($productQ['data_list'])){
        $product = $productQ['data_list'];
        if(!empty($product['product_image'])){
            $main_img=base_url().$product['product_image'];
        }else{
            $main_img=base_url().'uploads/default_product.jpg';
        }
?>

<div class="product-single-container product-single-default product-quick-view container">
    <div class="row">
        <div class="col-lg-6 col-md-6 product-single-gallery">
            <?php if($product['product_image']!=''){ ?>
            <div class="product-slider-container product-item">
                <div class="product-single-carousel owl-carousel owl-theme">
                    <div class="product-item">
                        <img class="product-single-image" src="<?php echo base_url().$product['product_image'] ?>" data-zoom-image="<?php echo base_url().$product['product_image'] ?>"/>
                    </div>
                    <?php
                    if($imageQ['status']=='success' && !empty($imageQ['data_list'])){
                        foreach($imageQ['data_list'] as $prdImage){ ?>
                            <div class="product-item">
                                <img class="product-single-image" src="<?php echo base_url().$prdImage['filePath'] ?>" data-zoom-image="<?php echo base_url().$prdImage['filePath'] ?>"/>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>

                <div class="owl-dot">
                    <img src="<?php echo base_url().$product['product_image'] ?>"/>
                </div>
                <?php
                    if($imageQ['status']=='success' && !empty($imageQ['data_list'])){
                    foreach($imageQ['data_list'] as $prdImage){
                ?>
                <div class="owl-dot">
                    <img src="<?php echo base_url().$prdImage['filePath'] ?>" alt="" />  
                </div>
                <?php } ?>
                <?php } ?>
            </div>
            <?php }else{ ?>
                <img src="<?php echo $main_img ?>" alt=""/>
            <?php } ?>
        </div><!-- End .col-lg-7 -->

        <div class="col-lg-6 col-md-6">
            <div class="product-single-details">
                <h1 class="product-title"><?php echo $product['name'] ?></h1> 
                <?php if(!empty($product['qty_details'])){ ?>
                    <select class="form-control select2_unit" name="unit" id="">
                    <?php  $json_data = json_decode($product['qty_details']);
                        foreach($json_data as $details) {
                            // print_R($details);echo "<hr>";
                            echo '<option value="'.$details->quantity.' ' .$details->unit.'" label="'.$details->quantity.' '.$details->unit.' / ' . $details->price.' Rs " selected>';
                        } ?>
                    </select>
                <?php  } ?>
                
                <div class="product-desc">
                    <p><?php echo $product['description'] ?></p>
                </div><!-- End .product-desc -->

                <div class="product-stock">
                    <?php if($product['in_stock']==1){ ?>
                    <p class="text-green"><i class="fa fa-check-square-o"></i> In Stock</p>
                    <?php }else{ ?>
                    <p class="text-danger"><i class="fa fa-window-close-o"></i> Out Of Stock</p> 
                    <?php } ?>
                </div>

                <div class="product-action">
                        <?php if($product['in_stock']==1){ ?>
                        <div class="product-single-qty">
                            <input class="horizontal-quantity form-control" id="prdqty" type="text" value="1" name="quantity" readonly>
                        </div>
                            <button type="button" class="btn-primary paction add-cart" title="Add to Cart" data-id="<?php echo $product['id'] ?>" data-metricsId="<?php echo $metricsId ?>" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                            </button>
                        <?php } ?>
                </div>
                <!-- End .product-action -->

                <!-- <div class="product-single-share">
                    <label>Share:</label>
                     <ul class="social-icons ">
                        <li><a href="#" class="twitter"><i class="icon-whatsapp"></i></a></li>
                        <li><a href="#" class="linkedin"><i class="icon-instagram"></i></a></li>
                     </ul>
                    <div class="addthis_inline_share_toolbox"></div>
                </div> -->
                <!-- End .product single-share -->
            </div><!-- End .product-single-details -->
        </div><!-- End .col-lg-5 -->
    </div><!-- End .row -->
</div><!-- End .product-single-container -->                                                                                                                   
<?php
    // }
}
?>
<script src="<?php echo base_url(); ?>ui/frontend/js/main.min.js"></script> 