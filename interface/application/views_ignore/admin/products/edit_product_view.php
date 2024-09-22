<?php
if ($productQ['status'] == 'success' && !empty($productQ['data_list'])) {
    $product = $productQ['data_list'];
    $productId = $product['id'];
    $categoryId = $product['categoryId'];
    $parentId = $product['parentId'];
    $productCode = $product['code'];
    $productName = $product['name'];
    $productURL = $product['product_url'];
    $description = $product['description'];
    $details = $product['product_metrics'];
    $productImage = $product['product_image'];
    $metaTags = $product['metaTags'];
    $metaDescription = $product['metaDescription'];
    $productBadge = $product['badge'];
    $tag_id = $product['tag_id'];
    $tag_name = $product['tag_name'];
    $is_primary = $product['is_primary'];
    $in_stock = $product['in_stock'];
    $status = $product['status'];
    $comission_applicable = $product['comission_applicable'];
} else {
    echo "<div class='alert bg-danger'>Invalid Request. Please Go back and try again.</div>";
    exit();
}
$pLists = array();
if ($metrics['status'] == 'success' && !empty($metrics['data_list'])) {
    $pLists = $metrics['data_list'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>
        <?php echo $title ?>
    </title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <!-- PLUGINS STYLES-->
    <link href="<?php echo base_url() ?>ui/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"
        rel="stylesheet" />
    <link href="<?php echo base_url() ?>ui/assets/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" />
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Products</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/products">Products</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Add New Product</div>
                                <button type="button" class="btn btn-info"
                                    onclick="window.location.href='<?php echo base_url() ?>admin/products/details/<?php echo $productId ?>'"><i
                                        class="fa fa-paper-plane"></i> Manage Details</button>
                            </div>
                            <div class="ibox-body">
                                <form role="form" id="addedit_product_form">
                                    <input type="hidden" name="productId" value="<?php echo $productId ?>">
                                    <input type="hidden" name="parentId" value="<?php echo $parentId ?>">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="ibox prd-image-box">
                                                <div class="ibox-head page-head-btns">
                                                    <div class="ibox-title">Add Product Image</div>
                                                </div>
                                                <div class="ibox-body text-center">
                                                    <button class="btn btn-success btn-sm btn-air openMediaModal"
                                                        type="button" onclick="openFileManager(this);" box-id="1">
                                                        <span class="btn-icon"><i class="fa fa-image"></i>Add
                                                            Image</span>
                                                    </button>
                                                    <?php if ($productImage == "") { ?>
                                                        <img src="" alt="image" class="mediaImgPrev" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url() . $productImage; ?>" alt="image"
                                                            class="mediaImgPrev imgExist" />
                                                    <?php } ?>
                                                    <input type="hidden" name="productImage" class="attachPath"
                                                        value="<?php echo $productImage; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-6 form-group mb-4">
                                                    <label class="form-control-label">Category<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control select2_cat" name="categoryId">
                                                        <option value="">--Select category--</option>
                                                        <?php
                                                        $dataList = $categoryQ['data_list'];
                                                        function generateOptions($category)
                                                        {
                                                            $options = '';

                                                            if (isset($category['children'])) {
                                                                $options .= '<optgroup value="' . $category['id'] . '" label="' . $category['text'] . '">';
                                                                foreach ($category['children'] as $subCategory) {
                                                                    $options .= generateOptions($subCategory);
                                                                }
                                                                $options .= '</optgroup>';
                                                            } else {
                                                                $options .= '<option value="' . $category['id'] . '" data-parent="0">' . $category['text'] . '</option>';
                                                            }

                                                            return $options;
                                                        }
                                                        $options = '';
                                                        foreach ($dataList as $category) {
                                                            $options .= generateOptions($category);
                                                        }

                                                        echo $options;
                                                        ?>
                                                    </select>
                                                    <script>$('[name=categoryId]').val(<?php echo $categoryId ?>);</script>
                                                </div>
                                                <div class="col-sm-6 form-group mb-4">
                                                    <label>Product Code<span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-solid" name="productCode"
                                                        type="text" placeholder="Unique ID"
                                                        value="<?php echo $productCode ?>"
                                                        data-edit="<?php echo $productCode ?>"
                                                        onchange="update_prd_url();">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 col-12 form-group mb-4">
                                                    <label>Product Name<span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-solid" name="productName"
                                                        type="text" placeholder="Enter Product Name"
                                                        onchange="update_prd_url();" value="<?php echo $productName ?>">
                                                </div>
                                                <div class="col-sm-6 col-12 form-group mb-4">
                                                    <label>Product Badge</label>
                                                    <!-- <input class="form-control form-control-solid" name="productName"
                                                        type="text" placeholder="Product Badge"
                                                        onchange="update_prd_url();" value="<?php //echo $productName ?>"> -->
                                                    <select name="badge" id="" class="form-control">
                                                        <option value="">--Select badge--</option>
                                                        <option value="Organically Grown" <?php echo $productBadge=='Organically Grown'?'selected':'' ?>>Organically Grown</option>
                                                        <option value="Fresh" <?php echo $productBadge=='Fresh'?'selected':'' ?>>Fresh</option>
                                                        <option value="Imported" <?php echo $productBadge=='Imported'?'selected':'' ?>>Imported</option>
                                                        <option value="Local" <?php echo $productBadge=='Local'?'selected':'' ?>>Local</option>
                                                        <option value="Seasonal" <?php echo $productBadge=='Seasonal'?'selected':'' ?>>Seasonal</option>
                                                        <option value="Farm" <?php echo $productBadge=='Farm'?'selected':'' ?>>Farm</option>
                                                        <option value="Nutritious" <?php echo $productBadge=='Nutritious'?'selected':'' ?>>Nutritious</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Description<span class="text-danger">*</span></label>
                                                <textarea class="form-control form-control-solid textEditor" rows="4"
                                                    name="description"><?php echo $description ?></textarea>
                                            </div>

                                        </div>
                                        <!-- start dynamic columns -->
                                        <div class="row">
                                            <div class="col-sm-12 mt-3">
                                                <div class="mt-3" id="custom_field_sec">
                                                    <div class="card">
                                                        <div class="card-box mb-0 p-2">
                                                            <?php
                                                            if ($details != '') {
                                                                if (count($pLists) > 0) {
                                                                    $i = 1;
                                                                    foreach ($pLists as $details) { ?>
                                                                        <div class="appendRows" data-count="<?php echo $i ?>">
                                                                            <div class="row">
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label class="form-control-label">MRP
                                                                                        <?PHP echo $i ?>
                                                                                    </label>
                                                                                    <input type="text" name="mrp[]" id="mrp_1"
                                                                                        class="c_name form-control"
                                                                                        value="<?php echo $details['mrp'] ?>">
                                                                                </div>
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label class="form-control-label">Price<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="price[]" id="price_1"
                                                                                        class="c_email form-control"
                                                                                        value="<?php echo $details['price'] ?>">
                                                                                </div>
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label class="form-control-label">Quantity<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="quantity[]"
                                                                                        id="quantity_1" class="c_name form-control"
                                                                                        value="<?php echo $details['quantity'] ?>">
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>Unit <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select class="form-control select2_unit"
                                                                                        name="unit[]" id="unit_<?php echo $i; ?>">
                                                                                        <option>--Select Unit --</option>
                                                                                        <option value="Kg" <?php echo ($details['unit'] == 'Kg') ? 'selected' : '' ?>>Kg</option>
                                                                                        <option value="Gram" <?php echo ($details['unit'] == 'Gram') ? 'selected' : '' ?>>Gram</option>
                                                                                        <option value="Litre" <?php echo ($details['unit'] == 'Litre') ? 'selected' : '' ?>>Litre</option>
                                                                                        <option value="Piece" <?php echo ($details['unit'] == 'Piece') ? 'selected' : '' ?>>Piece</option>
                                                                                        <option value="Bunch" <?php echo ($details['unit'] == 'Bunch') ? 'selected' : '' ?>>Bunch</option>
                                                                                        <option value="Pack" <?php echo ($details['unit'] == 'Pack') ? 'selected' : '' ?>>Pack</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>CGST</label>
                                                                                    <input class="form-control form-control-solid"
                                                                                        name="CGST[]" id="CGST_1" type="text"
                                                                                        value="<?php echo $details['CGST'] ?>">
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>SGST</label>
                                                                                    <input class="form-control form-control-solid"
                                                                                        name="SGST[]" id="SGST_1" type="text"
                                                                                        value="<?php echo $details['SGST'] ?>">
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="row justify-content-end">
                                                                                        <?php if($details['status']=='Inactive'){ ?>
                                                                                        <?php }else{ ?>
                                                                                        <?php } ?>
                                                                                        <button class="btn <?php echo $details['status']=='Active'?'bg-warning':'bg-danger' ?> deactivateMRP" data-id="<?php echo $details['id'] ?>" title="Deactivate Metric"><?php echo $details['status']=='Active'?'Activated':'DISABLED' ?></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php $i++;
                                                                    }
                                                                } else { ?>
                                                                    <div class="card">
                                                                        <div class="card-box mb-0 p-2">
                                                                            <div class="appendRows" data-count="1">
                                                                                <div class="row">
                                                                                    <div class="form-group col-sm-4 col-12">
                                                                                        <label
                                                                                            class="form-control-label">MRP</label>
                                                                                        <input type="text" name="mrp[]"
                                                                                            id="mrp_1"
                                                                                            class="c_name form-control"
                                                                                            value="">
                                                                                    </div>
                                                                                    <div class="form-group col-sm-4 col-12">
                                                                                        <label
                                                                                            class="form-control-label">Price<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" name="price[]"
                                                                                            id="price_1"
                                                                                            class="c_email form-control"
                                                                                            value="">
                                                                                    </div>
                                                                                    <div class="form-group col-sm-4 col-12">
                                                                                        <label
                                                                                            class="form-control-label">Quantity<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" name="quantity[]"
                                                                                            id="quantity_1"
                                                                                            class="c_name form-control"
                                                                                            value="">
                                                                                    </div>
                                                                                    <div class="col-sm-4 form-group mb-4">
                                                                                        <label>Unit<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select
                                                                                            class="form-control select2_unit"
                                                                                            name="unit[]" id="unit_1" required>
                                                                                            <option value="">--Select Unit--
                                                                                            </option>
                                                                                            <option>--Select Unit --</option>
                                                                                            <option value="Kg">Kg</option>
                                                                                            <option value="Gram">Gram</option>
                                                                                            <option value="Litre">Litre</option>
                                                                                            <option value="Piece">Piece</option>
                                                                                            <option value="Bunch">Bunch</option>
                                                                                            <option value="Pack">Pack</option>

                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-sm-4 form-group mb-4">
                                                                                        <label>CGST</label>
                                                                                        <input
                                                                                            class="form-control form-control-solid"
                                                                                            name="CGST[]" id="CGST_1"
                                                                                            type="text" placeholder="In %">
                                                                                    </div>
                                                                                    <div class="col-sm-4 form-group mb-4">
                                                                                        <label>SGST</label>
                                                                                        <input
                                                                                            class="form-control form-control-solid"
                                                                                            name="SGST[]" id="SGST_1"
                                                                                            type="text" placeholder="In %">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="card">
                                                                    <div class="card-box mb-0 p-2">
                                                                        <div class="appendRows" data-count="1">
                                                                            <div class="row">
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label
                                                                                        class="form-control-label">MRP</label>
                                                                                    <input type="text" name="mrp[]"
                                                                                        id="mrp_1"
                                                                                        class="c_name form-control"
                                                                                        value="">
                                                                                </div>
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label
                                                                                        class="form-control-label">Price<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="price[]"
                                                                                        id="price_1"
                                                                                        class="c_email form-control"
                                                                                        value="">
                                                                                </div>
                                                                                <div class="form-group col-sm-4 col-12">
                                                                                    <label
                                                                                        class="form-control-label">Quantity<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="quantity[]"
                                                                                        id="quantity_1"
                                                                                        class="c_name form-control"
                                                                                        value="">
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>Unit<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select
                                                                                        class="form-control select2_unit"
                                                                                        name="unit[]" id="unit_1" required>
                                                                                        <option value="">--Select Unit--
                                                                                        </option>
                                                                                        <option>--Select Unit --</option>
                                                                                        <option value="Kg">Kg</option>
                                                                                        <option value="Gram">Gram</option>
                                                                                        <option value="Litre">Litre</option>
                                                                                        <option value="Piece">Piece</option>
                                                                                        <option value="Bunch">Bunch</option>
                                                                                        <option value="Pack">Pack</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>CGST</label>
                                                                                    <input
                                                                                        class="form-control form-control-solid"
                                                                                        name="CGST[]" id="CGST_1"
                                                                                        type="text" placeholder="In %">
                                                                                </div>
                                                                                <div class="col-sm-4 form-group mb-4">
                                                                                    <label>SGST</label>
                                                                                    <input
                                                                                        class="form-control form-control-solid"
                                                                                        name="SGST[]" id="SGST_1"
                                                                                        type="text" placeholder="In %">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-12 text-end" style="text-align:end">
                                                        <span><label
                                                                class="action-icons btn btn-success btn-sm btn-addCustomField"
                                                                onclick="addCustomField();"><i class="fa fa-plus"></i>
                                                                Add Field</label></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label>Product URL<span class="text-danger">*</span></label>
                                        <div class="input-group form-control-solid mb-3">
                                            <span class="input-group-addon">
                                                <?php echo base_url() ?>products/
                                            </span>
                                            <input class="form-control form-control-solid" name="productURL" type="text"
                                                placeholder="Helps to show in google search..."
                                                value="<?php echo $productURL ?>">
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox">
                                    <div class="ibox-head page-head-btns">
                                        <div class="ibox-title">SEO Details</div>
                                    </div>
                                    <div class="ibox-body">
                                        <div class="form-group mb-4">
                                            <label>Meta Tags</label>
                                            <input class="tagsinput form-control form-control-solid" name="metaTags"
                                                type="text" placeholder="Meta Tags" value="<?php echo $metaTags ?>">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label>Meta Description</label>
                                            <textarea class="form-control form-control-solid" rows="4"
                                                name="metaDescription"
                                                placeholder="Meta Description goes here"><?php echo $metaDescription ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Stock Check<span class="text-danger">*</span></label>
                                    <?php
                                    $stock1_select = $stock2_select = '';
                                    if ($in_stock == 1) {
                                        $stock1_select = 'checked';
                                    } else if ($in_stock == 0) {
                                        $stock2_select = 'checked';
                                    }
                                    ?>
                                    <div class="mb-0">
                                        <label class="radio radio-inline radio-outline-success">
                                            <input type="radio" name="in_stock" value="1" <?php echo $stock1_select ?>>
                                            <span class="input-span"></span>In Stock
                                        </label>
                                        <label class="radio radio-inline radio-outline-danger">
                                            <input type="radio" name="in_stock" value="2" <?php echo $stock2_select ?>>
                                            <span class="input-span"></span>Out Of Stock
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <div class="mb-0">
                                        <label class="radio radio-inline radio-outline-success">
                                            <input type="radio" name="status" value="32" <?php echo ($status == 32) ? 'checked' : '' ?>>
                                            <span class="input-span"></span>Active
                                        </label>
                                        <label class="radio radio-inline radio-outline-danger">
                                            <input type="radio" name="status" value="33" <?php echo ($status == 33) ? 'checked' : '' ?>>
                                            <span class="input-span"></span>Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Comission Applicable<span class="text-danger">*</span></label>
                                    <div class="mb-0">
                                        <label class="radio radio-inline radio-outline-success">
                                            <input type="radio" name="comission_applicable" value="yes" <?php echo ($comission_applicable == 'yes') ? 'checked' : '' ?>>
                                            <span class="input-span"></span>Active
                                        </label>
                                        <label class="radio radio-inline radio-outline-danger">
                                            <input type="radio" name="comission_applicable" value="no" <?php echo ($comission_applicable == 'no') ? 'checked' : '' ?>>
                                            <span class="input-span"></span>Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.location.href='<?php echo base_url() ?>admin/products'">Cancel</button>
                                </div>
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
                <!-- end card-->
            </div>
            <!-- end col -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
    <?php echo $footer ?>
    </div>
    </div>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/ckeditor/adapters/jquery.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/jquery-minicolors/jquery.minicolors.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/products.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/media_library.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/excel_progress.js"></script>
    <script>
        $(".deactivateMRP").on('click', function(e){
            e.preventDefault();
            const id = $(this).attr('data-id');
            $.post(urljs+'admin/products/update_metrics_status/'+id, {}, function(response){
                if(response.status=='success'){
                    swal_alert('Success!!', response.message, "success", '')
                }else{
                    swal_alert('Failed!!', response.message, "error", '')
                }
            }, 'json')
        })
    </script>
</body>

</html>