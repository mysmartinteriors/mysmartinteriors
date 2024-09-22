<style>
    .bootstrap-touchspin-up{
        background: none;
        color: #8798a1;
        border-color: #dae2e6;
    }
    .bootstrap-touchspin-down{
        background: none;
        color: #8798a1;
        border-color: #dae2e6;
    }
</style>

<div class="row row-sm">
    <?php
    if (!empty($data_list)) {
        foreach ($data_list as $productRow) {
            if (isset($productRow['ratingCount']) && isset($productRow['ratingValue'])) {
                $prdTotRating = $this->admin->cal_rating_per($productRow['ratingCount'], $productRow['ratingValue']);
            } ?>
            <div class="col-6 col-md-4">
                <div class="product">
                    <figure class="product-image-container" style="position: relative">
                        <?php
                        if ($productRow['product_image'] != "") {
                            $img_prev = base_url() . $productRow['product_image'];
                        } else {
                            $img_prev = base_url() . 'uploads/default_product.jpg';
                        } ?>
                        <a href="<?php echo base_url() . 'product/' . $productRow['code'] ?>" class="product-image">
                            <img src="<?php echo $img_prev ?>" alt="product">
                        </a>
                        <a href="javascript:void(0);" class="btn-quickview"
                            data-id="<?php echo $productRow['id'] ?>">Quickview</a>
                    </figure>
                    <div class="product-details" data-stock="<?php echo $productRow['in_stock'] ?>">
                        <h1 class="product-title">
                            <a href="<?php echo base_url() . 'product/' . $productRow['code'] ?>" style="color:#0f7a4e"> 
                                <?php echo $productRow['name'];
                                ?>
                            </a>
                        </h1>

                        <?php if (!empty($productRow['product_metrics'])) { ?>
                            <div class="metricsParent">
                                <select class="form-control select2_unit metrics" name="metricsId" id="">
                                    <?php $json_data = $productRow['product_metrics'];
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
                                <?php if(!empty($productRow['badge'])){ ?>
                                <span class="badge badge-success badge-sm" style="height: 20px"><?php echo !empty($productRow['badge'])?$productRow['badge']:''; ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="product-action">
                                <div class="product-single-qty custom-single-qty">
                                <input  <?php echo $productRow['in_stock']==1?'':'disabled' ?>  class="custom-horizontal-quantity form-control" type="text" value="1" name="quantity" id="featuredPrdQty" readonly>
                                </div>
                                <button <?php echo $productRow['in_stock']==1?'':'disabled' ?> type="button" class="btn-primary paction add-cart" title="Add to Cart"
                                data-id="<?php echo $productRow['id'] ?>"
                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                </button>
                            <?php //} ?>
                                
                        </div>
                        
                        <!-- End .product-action -->
                    </div><!-- End .product-details -->
                </div><!-- End .product -->
            </div><!-- End .col-md-4 -->
        <?php } ?>
        <div class="col-12 mb-2 text-right">
            <div id="result" class="table-pagination-holder">
                <?php if (isset($pagination_data['pagination_links'])) {
                    echo $pagination_data['pagination_links'];
                } ?>
            </div>
        </div>
    <?php } else {
        ?>
        <div class="col-md-12">
            <div class="prd-notify">
                <div class="alert alert-warning fade show alert-outline has-icon"><i class="la la-warning alert-icon"></i>
                    <strong>Sorry! </strong>No product(s) available... Try refine your search or select other categories
                </div>
            </div>
        </div>
    </div>

    <?php
    }
    ?>


<script>
    $(document).ready(function(){
        $(".custom-horizontal-quantity").TouchSpin({
            min: 1
        })
        $(".metrics").each(function(){
            var selectElement = $(this);
            var defaultPrice = parseInt(selectElement.find('option:first').data('price'));
            var defaultMrp = parseInt(selectElement.find('option:first').data('mrp'));
            var defaultMetricsId = parseInt(selectElement.val());
            var addToCartBtnElement = selectElement.parent().next().next().find('.add-cart')
            addToCartBtnElement.attr('data-metricsId', defaultMetricsId)
            var priceElement = selectElement.parent().next().find('.price');
            var mrpElement = selectElement.parent().next().find('.mrp');
            var discountElement = selectElement.parent().parent().prev();
            var outOfStockElement = selectElement.parent().parent().prev();
            var isOutOfStockParent = selectElement.parent().parent();
            var isOutOfStock = isOutOfStockParent.attr('data-stock')
            // console.log(isOutOfStock)
            if(defaultPrice>0 && defaultMrp>0 && defaultMetricsId>0){
                let defaultDiscount = (((parseInt(defaultMrp) - parseInt(defaultPrice))*100)/parseInt(defaultMrp)).toFixed(0)
                let priceOff = `<span class="badge bg-success text-white" style="position: absolute">${defaultDiscount}% off</span>`;
                discountElement.prepend(priceOff)
            }
            if(isOutOfStock==2){
                let priceOff = `<span class="badge bg-danger text-white rightSpan" style="position: absolute; right: 0">Out Of Stock</span>`;
                discountElement.prepend(priceOff)
            }
            priceElement.text(defaultPrice);
            mrpElement.text(defaultMrp);

            selectElement.change(function() {
                var selectedOption = selectElement.find('option:selected');
                var price = selectedOption.data('price');
                var mrp = selectedOption.data('mrp');
                var metricsId = selectedOption.val();
                priceElement.text(price);
                mrpElement.text(mrp);
                addToCartBtnElement.attr('data-metricsId', metricsId);
            });
        })
    })
</script>