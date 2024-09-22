<?php
$productId = '';
$parentId = '';
$categoryId = '';
$productCode = '';
$productName = '';
$prdDescription = '';
$prdFeatures = '';
$productImage = '';
$prdMoreImage = '';
$productPrice = '';
$in_stock = '';
$productURL = '';
$metaTags = '';
$metaDescription = '';
$ratingValue = 0;
$ratingCount = 0;
$prdTotRating = 0;
$metrics = array();

if (!empty($productQ)) {
    $parentId = $productQ['parentId'];
    $categoryId = $productQ['categoryId'];
    $parentName = $productQ['parentName'];
    $categoryName = $productQ['categoryName'];
    $p_code = $productQ['p_code'];
    $s_code = $productQ['s_code'];

    $productId = $productQ['id'];
    $productCode = $productQ['code'];
    $productName = $productQ['name'];
    $prdDescription = $productQ['description'];
    $productImage = $productQ['product_image'];
    //   $productPrice=$productQ['price'];
    $in_stock = $productQ['in_stock'];
    $productURL = $productQ['product_url'];
    $metaTags = $productQ['metaTags'];
    $metaDescription = $productQ['metaDescription'];
    $ratingCount = 0;
    //   $ratingCount=$productQ['ratings'];
    $ratingValue = 0;
    $prdDetails = 0;
    $metrics = $productQ['product_metrics'];
    //   $ratingValue=$productQ['ratingValue'];
    //   $prdDetails=$productQ['prdDetails'];

    //   $color_code=$productQ['color_code'];
    //   $color_name=$productQ['color_name'];
    //   $model_no=$productQ['model_no'];

    // $prdTotRating=$this->admin->cal_rating_per($ratingCount,$ratingValue);

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo $title ?>
    </title>
    <meta name="keywords" content="<?php echo $metaTags ?>" />
    <meta name="description" content="<?php echo $metaDescription ?>">
    <meta name="author" content="">
    <!-- Plugins CSS File -->
    <?php echo $commonCss ?>
</head>

<body>
    <div class="page-wrapper">
        <!-- start header-->
        <?php echo $header_main ?>
        <!-- End .header -->

        <main class="main">

            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>Products">Products</a></li>
                        <?php if ($parentId != 0 && $parentName != '') { ?>
                            <li class="breadcrumb-item"><a
                                    href="<?php echo base_url() ?>Products?cat_type=<?php echo $p_code ?>">
                                    <?php echo $parentName ?>
                                </a></li>
                        <?php } ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo $productName ?>
                        </li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="product-single-container product-single-default">
                            <div class="row">
                                <div class="col-lg-7 col-md-6">
                                    <div class="product-single-gallery">
                                        <div class="product-slider-container product-item">
                                            <?php
                                            if ($productImage != "") {
                                                ?>
                                                <div class="product-single-carousel owl-carousel owl-theme">
                                                    <div class="product-item">
                                                        <img class="product-single-image"
                                                            src="<?php echo base_url() . $productImage ?>"
                                                            data-zoom-image="<?php echo base_url() . $productImage ?>" />
                                                    </div>
                                                    <?php
                                                    if ($imageQ['status'] == 'success' && !empty($imageQ['data_list'])) {
                                                        foreach ($imageQ['data_list'] as $prdImage) {
                                                            ?>
                                                            <div class="product-item">
                                                                <img class="product-single-image"
                                                                    src="<?php echo base_url() . $prdImage['filePath'] ?>"
                                                                    data-zoom-image="<?php echo base_url() . $prdImage['filePath'] ?>"
                                                                    alt="<?php echo $productName ?>" />
                                                            </div>
                                                        <?php }
                                                    } ?>
                                                </div>
                                                <!-- End .product-single-carousel -->
                                                <span class="prod-full-screen">
                                                    <i class="fa fa-search-plus"></i>
                                                </span>
                                            <?php
                                            } else {
                                                ?>
                                                <figure class=>
                                                    <img src="<?php echo base_url() ?>uploads/default_product.jpg" alt="" />
                                                </figure>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                                            <?php
                                            if ($productImage != "") {
                                                ?>
                                                <div class="col-3 owl-dot">
                                                    <img src="<?php echo base_url() . $productImage ?>" />
                                                </div>
                                                <?php
                                                if ($imageQ['status'] == 'success' && !empty($imageQ['data_list'])) {
                                                    foreach ($imageQ['data_list'] as $prdImage) {
                                                        ?>
                                                        <div class="owl-dot">
                                                            <img src="<?php echo base_url() . $prdImage['filePath'] ?>"
                                                                alt="<?php echo $productName ?>" />
                                                        </div>
                                                    <?php }
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-6">
                                    <div class="product-single-details">
                                        <h1 class="product-title">
                                            <?php echo $productName ?>
                                        </h1>
                                        <div class="product-desc">
                                            <p>
                                                <?php echo $prdDescription ?>
                                            </p>
                                        </div>
                                        <div class="product-stock">
                                            <?php if ($in_stock == 1) { ?>
                                                <p class="text-green"><i class="fa fa-check-square-o"></i> In Stock</p>
                                            <?php } else { ?>
                                                <p class="text-danger"><i class="fa fa-window-close-o"></i> Out Of Stock</p>
                                            <?php } ?>
                                        </div>
                                        <div class="">
                                            <?php if (!empty($metrics)) { ?>
                                                <select class="form-control select2_unit" name="metricsId" class="metricsData">
                                                    <?php 
                                                    foreach ($metrics as $details) { ?>
                                                        <option value="<?php echo $details['id'] ?>">
                                                        <?php 
                                                                echo $details['quantity'] . ' ' . $details['unit'] . ' / ' ; 
                                                                if (isset($details['mrp']) && $details['mrp'] > $details['price']) {
                                                                    echo '<span style="text-decoration: line-through;">MRP ₹'. $details['mrp'] . '</span> / ';
                                                                }
                                                                echo 'Price ₹' .$details['price'] . ''; 
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                        <div class="product-action">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex">
                                                    <div class="product-single-qty">
                                                        <input class="horizontal-quantity prdQtyInput form-control"
                                                            type="text" name="quantity" readonly>
                                                    </div>
                                                    <?php if ($in_stock == 1) { ?>
                                                        <button type="button" class="btn-primary paction add-cart"
                                                            title="Add to Cart" data-id="<?php echo $productId ?>"
                                                            data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                                            <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                                        </button>

                                                    <?php } else { ?>
                                                        <button class="paction notify-to-buy"
                                                            title="Get noticed about products stock"
                                                            data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Please wait...">
                                                            <span>Notify Me</span>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End .product-action -->

                                        <?php if ($availableQ['status'] == 'success' && !empty($availableQ['data_list'])) { ?>
                                            <div class="configurable-item">
                                                <p>Available Options:</p>
                                                <ul class="configurable-list">
                                                    <?php
                                                    foreach ($availableQ['data_list'] as $avail_list) {
                                                        if ($avail_list['productId'] != $productId) {
                                                            if ($avail_list['tag_name'] != '') {
                                                                $titleName = $avail_list['tag_name'];
                                                            } else {
                                                                $titleName = $avail_list['color_name'];
                                                            }
                                                            if ($avail_list['productImage'] != '') {
                                                                $avail_list_img = base_url() . $avail_list['productImage'];
                                                            } else {
                                                                $avail_list_img = base_url() . 'uploads/default_product.jpg';
                                                            }
                                                            ?>
                                                            <a
                                                                onclick="window.location.href='<?php echo base_url() . 'product/' . $avail_list['productURL'] ?>'">
                                                                <li data-toggle="tooltip" data-placement="top"
                                                                    title="<?php echo $titleName ?>"
                                                                    style="background: <?php echo $avail_list['color_code'] ?>">
                                                                </li>
                                                            </a>
                                                        <?php }
                                                    } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>

                                        <!-- <div class="product-single-share">
                                            <label>Share:</label>
                                            <ul class="social-icons ">
                                                <li><a href="#" class="facebook"><i class="icon-facebook"></i></a></li>
                                                <li><a href="#" class="twitter"><i class="icon-twitter"></i></a></li>
                                                <li><a href="#" class="linkedin"><i class="icon-instagram"></i></a></li>
                                            </ul>
                                            <div class="addthis_inline_share_toolbox"></div>
                                        </div> -->
                                        <!-- End .product single-share -->
                                        <?php if ($metaTags != '') { ?>
                                            <div class="product-single-tags">
                                                <p><label>Tags: </label>&nbsp;
                                                    <?php echo $metaTags ?>
                                                </p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- End .product-single-details -->
                                </div>
                                <!-- End .col-lg-5 -->
                            </div>
                            <!-- End .row -->
                        </div>
                    </div><!-- End .col-lg-9 -->

                    <div class="sidebar-overlay"></div>
                    <!-- <div class="sidebar-toggle"><i class="icon-sliders"></i></div> -->
                    <aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
                        <div class="sidebar-wrapper">
                            <?php
                            if (isset($offerQ) && $offerQ['status'] == 'success' && !empty($offerQ['data_list'])) {
                                foreach ($offerQ['data_list'] as $offerRow) {
                                    ?>
                                    <div class="widget widget-banner">
                                        <div class="banner banner-image">
                                            <a href="<?php echo base_url() . 'product/' . $offerRow['productURL'] ?>">
                                                <img src="<?php echo base_url() . $offerRow['productImage'] ?>" alt="">
                                            </a>
                                        </div><!-- End .banner -->
                                    </div><!-- End .widget -->
                                <?php }
                            } ?>
                        </div>
                    </aside><!-- End .col-md-3 -->
                </div><!-- End .row -->
                <?php
                //if($suggestQ['status']=='succes' && !empty($suggestQ['data_list'])){  ?>
                <?php //}  ?>

                <div class="mb-lg-4"></div><!-- margin -->
            </div><!-- End .container -->


        </main><!-- End .main -->
        <!-- End .main -->
        <?php echo $footer ?>
        <!-- End .footer -->
    </div>
    <!-- End .page-wrapper -->

    <?php echo $mobile_menu ?>
    <!-- End .mobile-menu-container -->

    <?php echo $commonJs ?>

</body>

</html>