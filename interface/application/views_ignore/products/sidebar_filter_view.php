<input type="hidden" data-type="search" data-name="search" class="refine_filter" id="prd-search"
    value="<?php echo $q ?>">
<div class="sidebar-wrapper" id="prd_sidebar">
    <?php
    if (!empty($catQ)) { ?>
        <div class="widget">
            <h3 class="widget-title">
                <a data-toggle="collapse" href="#categorybody" role="button" aria-expanded="true"
                    aria-controls="categorybody">Category</a>
            </h3>
            <div class="collapse show" id="categorybody">
                <div class="widget-body">
                    <ul>
                        <?php echo $catQ; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php }
    if ($fpriceQ['status'] == 'success' && !empty($fpriceQ['data_list'])) { ?>
        <div class="widget">
            <h3 class="widget-title">
                <a data-toggle="collapse" href="#pricebody" role="button" aria-expanded="true"
                    aria-controls="pricebody">Price</a>
            </h3>
            <div class="collapse show" id="pricebody">
                <div class="widget-body">
                    <div class="price-range-wrap">
                        <div class="price-slide-range"></div>
                        <div class="range-slider">
                            <input type="text" class="priceFilter" id="amount" value="" />
                            <input type="hidden" class="refine_filter priceTextBox minPrice" data-type="min_price"
                                data-name="min_price" id="minamount" value="" />
                            <input type="hidden" class="refine_filter priceTextBox maxPrice" data-type="max_price"
                                id="maxamount" data-name="max_price"
                                value="<?php echo $fpriceQ['data_list']['max_price'] ?>" />
                        </div>
                    </div>
                </div><!-- End .widget-body -->
            </div><!-- End .collapse -->
        </div><!-- End .widget -->
    <?php } ?>

    <?php if ($featuredQ['status'] == 'success' && !empty($featuredQ['data_list'])) { ?>
        <div class="widget widget-featured">
            <h3 class="widget-title">Featured Products</h3>
            <div class="widget-body">
                <div class="owl-carousel widget-featured-products">
                    <div class="featured-col">
                        <?php foreach ($featuredQ['data_list'] as $featuredRow) { ?>
                            <div class="product product-sm">
                                <figure class="product-image-container">
                                    <?php
                                    if ($featuredRow['product_image'] != "") {
                                        $img_prev = base_url() . $featuredRow['product_image'];
                                    } else {
                                        $img_prev = base_url() . 'uploads/default_product.jpg';
                                    }
                                    ?>
                                    <a href="<?php echo base_url() . 'product/' . $featuredRow['code'] ?>"
                                        class="product-image">
                                        <img src="<?php echo $img_prev ?>" alt="">
                                    </a>
                                    <!-- <?php echo string_teaser($featuredRow['name'], 40) ?></a> -->
                                </figure>

                                <!-- <div class="product-details">
                                    <h1 class="product-title">
                                        <a href="<?php echo base_url() . 'product/' . $featuredRow['code'] ?>"
                                            style="color:#0f7a4e">
                                            <?php echo $featuredRow['name'];
                                            ?>
                                        </a>
                                    </h1>

                                    <?php if (!empty($featuredRow['product_metrics'])) { ?>
                                        <div class="metricsParent">
                                        <select class="form-control select2_unit metrics" name="metricsId" id="">
                                            <?php $json_data = $featuredRow['product_metrics'];
                                            foreach ($json_data as $details) { ?>
                                                <option value="<?php echo $details['id'] ?>">
                                                    <?php 
                                                        echo $details['quantity'] . ' ' . $details['unit'] . ' / ' ; 
                                                        if (isset($details['mrp']) && $details['mrp'] > $details['price']) {
                                                            echo '<span style="text-decoration: line-through;">MRP ₹'. $details['mrp'] . '</span> / ';
                                                        }
                                                        echo 'Price ₹' .$details['price'] . '';  ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        </div>
                                    <?php } ?>

                                    <div class="product-action">
                                        <?php if ($featuredRow['in_stock'] == 1) { ?>
                                            <button type="button" class="btn-primary paction add-cart" title="Add to Cart" data-id="<?php echo $featuredRow['id'] ?>"
                                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                                <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                            </button>
                                        <?php } else { ?>
                                            <button class="paction notify-to-buy" title="Get noticed about products stock"
                                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Please wait...">
                                                <span>Notify Me</span>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div> -->

                                <div class="product-details">
                        <h1 class="product-title">
                            <a href="<?php echo base_url() . 'product/' . $featuredRow['code'] ?>" style="color:#0f7a4e"> 
                                <?php echo $featuredRow['name'];
                                ?>
                            </a>
                        </h1>


                        <?php if (!empty($featuredRow['product_metrics'])) { ?>
                            <div class="metricsParent">
                                <select class="form-control select2_unit metrics" name="metricsId" id="">
                                    <?php $json_data = $featuredRow['product_metrics'];
                                    foreach ($json_data as $details) { ?> 
                                        <option data-metricsId="<?php echo $details['id'] ?>" data-mrp="<?php echo $details['mrp'] ?>" data-price="<?php echo $details['price'] ?>" value="<?php echo $details['id'] ?>"><?php echo $details['quantity']. ' '.$details['unit'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between px-2 priceDiv">
                                <p class="priceParent" style="text-align: left">
                                    <strong>&#8377; <span class="price"></span></strong> 
                                    <span style="text-decoration: line-through; font-size: 10px;">&#8377; <span class="mrp"></span></span>
                                </p>
                                <?php if(!empty($featuredRow['badge'])){ ?>
                                <span class="badge badge-success badge-sm" style="height: 20px"><?php echo !empty($featuredRow['badge'])?$featuredRow['badge']:''; ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="product-action">
                            <?php if ($featuredRow['in_stock'] == 1) { ?>
                                <div class="product-single-qty custom-single-qty">
                                <input class="custom-horizontal-quantity form-control" type="text" value="1" name="quantity" id="featuredPrdQty" readonly>
                                </div>
                                <button type="button" class="btn-primary paction add-cart" title="Add to Cart"
                                data-id="<?php echo $featuredRow['id'] ?>"
                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                </button>
                            <?php } ?>
                        </div>
                        
                        <!-- End .product-action -->
                    </div><!-- End .product-details -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div>