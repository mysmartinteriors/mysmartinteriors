<?php
$productId='';
$featureId='';
$content='';
$content_html='';
$createdDate='';
$updatedDate='';
$productURL='';
if(!empty($productQ)){
    $productId=$productQ['id'];
    $productURL=$productQ['product_url'];
}
if($featureQ['status']=='success' && !empty($featureQ['data_list'])){
    $featureId=$featureQ['data_list']['id'];
    $content=$featureQ['data_list']['content'];
    $content_html=$featureQ['data_list']['content_html'];
    $createdDate=$featureQ['data_list']['createdDate'];
    $updatedDate=$featureQ['data_list']['updatedDate'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">  
<link href="<?php echo base_url() ?>ui/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>ui/assets/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" />
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout mng-product">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Product Details</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/products">Products</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/products/edit/<?php echo $productId ?>"><?php echo ucwords($productURL) ?></a></li>
                    <li class="breadcrumb-item">Details</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">

                <!--product images-->
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" id="prd_image_form">
                            <input type="hidden" name="pId" value="<?php echo $productId ?>">
                            <div class="ibox">
                                <div class="ibox-head page-head-btns">
                                    <div class="ibox-title">Here are the product images</div>
                                    <button type="submit" class="btn btn-success btn-circle btn-fix btn-air" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating...">
                                        <span class="btn-icon"><i class="ti-check-box"></i>Save</span>
                                    </button>
                                </div>
                                <div class="ibox-body">
                                    <div class="row">
                                        <?php if($imageQ['status']=='success' && !empty($imageQ['data_list'])){
                                                $i=1;
                                                foreach ($imageQ['data_list'] as $imageRow) { ?>
                                                    <div class="col-sm-3 col-12">
                                                        <div class="ibox imgBox-holder imageExists">
                                                            <div class="ibox-body">
                                                                <div class="prdImgBox">
                                                                    <button class="btn btn-danger rmPrdImage btn-icon-only btn-circle btn-sm btn-air" type="button" data-id="<?php echo $imageRow['id'] ?>"><i class="ti-trash"></i></button>
                                                                    <button class="btn btn-success btn-sm btn-air openMediaModal" type="button" onclick="openFileManager(this);" box-id="<?php echo $i ?>">
                                                                    <span class="btn-icon"><i class="fa fa-image"></i>Select Image</span>
                                                                    </button>
                                                                    <img src="<?php echo base_url().$imageRow['filePath'] ?>" alt="image" class="mediaImgPrev" />
                                                                    <input type="hidden" name="productImage[]" class="attachPath" value="<?php echo $imageRow['filePath'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php $i++; } }
                                            $boxCount=0;
                                            if(empty($imageQ['data_list'])){
                                                $boxCount=4;
                                            }else if(count($imageQ['data_list'])==1){
                                                $boxCount=3;
                                            }else if(count($imageQ['data_list'])==2){
                                                $boxCount=2;
                                            }else if(count($imageQ['data_list'])==3){
                                                $boxCount=1;
                                            }else if(count($imageQ['data_list'])==4){
                                                $boxCount=0;
                                            }
                                            if($boxCount>0){
                                            for($j=1;$j<=$boxCount;$j++){
                                        ?>

                                        <div class="col-sm-3 col-12">
                                            <div class="ibox imgBox-holder">
                                                <div class="ibox-body">
                                                    <div class="prdImgBox">
                                                        <button class="btn btn-success btn-sm btn-air openMediaModal" type="button" onclick="openFileManager(this);" box-id="<?php echo $j ?>">
                                                          <span class="btn-icon"><i class="fa fa-image"></i>Select Image</span>
                                                        </button>
                                                        <img src="" alt="image" class="mediaImgPrev" />
                                                        <input type="hidden" name="productImage[]" class="attachPath" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } } ?>

                                    </div>
                                </div>               
                            </div>
                        </form>
                    </div>                    
                </div>
                <!--end product images-->

                <!--product details-->
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" id="prd_feature_form">
                            <input type="hidden" name="pId" value="<?php echo $productId ?>">
                            <input type="hidden" name="fId" value="<?php echo $featureId ?>">
                            <div class="ibox">
                                <div class="ibox-head page-head-btns">
                                    <div class="ibox-title">Here goes the product details</div>
                                    <button type="submit" class="btn btn-success btn-circle btn-fix btn-air" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating...">
                                        <span class="btn-icon"><i class="ti-check-box"></i>Save</span>
                                    </button>
                                </div>
                                <div class="ibox-body">
                                    <div class="row">                                 
                                        <div class="col-sm-12 col-12">
                                            <div class="form-group">
                                                <textarea class="form-control textEditor" name="details" placeholder="Write here"><?php echo $content_html ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>               
                            </div>
                        </form>
                    </div>                    
                </div>
                <!--end product details-->

                <!--product ratings-->
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <form role="form" id="prd_ratings_form">
                            <input type="hidden" name="pId" value="<?php echo $productId ?>">
                            <div class="ibox">
                                <div class="ibox-head page-head-btns">
                                    <div class="ibox-title">Here are the user ratings for this product</div>
                                </div>
                                <div class="ibox-body">
                                    <div class="row">                                 
                                        <div class="col-sm-12 col-12">
                                            <table class="table table-bordered rating_tbl">
                                                <thead>
                                                    <tr>
                                                        <th>SlNo</th>
                                                        <th>Customer</th>
                                                        <th>Ratings</th>
                                                        <th>Summary</th>
                                                        <th>Review</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if($ratingQ['status']=='success' && !empty($ratingQ['data_list'])){
                                                            $i=1;
                                                            foreach ($ratingQ['data_list'] as $ratingRow) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $i ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ratingRow['firstName'].' '.$ratingRow['lastName'] ?>
                                                            <?php if($ratingRow['email']!=''){echo '<br>'.$ratingRow['email'];} ?>
                                                        </td>
                                                        <td width="130px" >
                                                            <div class="ratting-star">
                                                                <?php echo $ratingRow['ratingValue'] ?><i class="fa fa-star"></i>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo $ratingRow['reviewSummary'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ratingRow['reviewContent'] ?>
                                                        </td>
                                                        <td><?php echo getMyDbDate('%d-%M-%Y %H:%i:%s',$ratingRow['ratedDate']) ?></td>
                                                    </tr>
                                                    <?php $i++; } } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>               
                            </div>
                        </form>
                    </div>
                </div> -->
                <!--end product ratings-->

            </div>
            <!-- END PAGE CONTENT-->
            <?php echo $footer ?>
        </div>
    </div>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/ckeditor/adapters/jquery.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/jquery-minicolors/jquery.minicolors.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/product_details.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/media_library.js"></script>

</body>
</html>
  