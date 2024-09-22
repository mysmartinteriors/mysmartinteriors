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
                <h1 class="page-title">Products</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/products">Products</a></li>
                    <li class="breadcrumb-item">Add</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Add New Product</div>
                            </div>
                             <div class="ibox-body">
                             <form role="form" id="addedit_product_form">
                                <input type="hidden" name="productId" value="">
                                <input type="hidden" name="parentId" value="">
                                <div class="row">
                                    <div class="col-lg-4">
                                      <div class="ibox prd-image-box">
                                        <div class="ibox-head page-head-btns">
                                          <div class="ibox-title">Add Product Image</div>
                                        </div>
                                        <div class="ibox-body text-center">
                                            <button class="btn btn-success btn-sm btn-air openMediaModal" type="button" onclick="openFileManager(this);" box-id="1">
                                              <span class="btn-icon"><i class="fa fa-image"></i>Add Image</span>
                                            </button>
                                            <img src="" alt="image" class="mediaImgPrev" />
                                            <input type="hidden" name="productImage" class="attachPath" value="" >
                                        </div> 
                                      </div> 
                                    </div>
                                    <div class="col-lg-8">                                        
                                        <div class="row">
                                            <div class="col-sm-6 form-group mb-4">
                                            <label class="form-control-label">Category<span class="text-danger">*</span></label>
                                            <select class="form-control select2_cat" name="categoryId">
                                                <option value="">--Select category--</option>
                                                <?php
                                                        // Assuming the data is in $apiResponse['data_list']
                                                        $dataList = $categoryQ['data_list'];
                                                        // Function to recursively generate options
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

                                                        // Generate options for each category
                                                        $options = '';
                                                        foreach ($dataList as $category) {
                                                        $options .= generateOptions($category);
                                                        }

                                                        echo $options;
                                                        ?>
                                            </select>
                                            <script>//$('[name=categoryId]').val(<?php //echo $categoryId ?>);</script>
                                            </div> 
                                            <div class="col-sm-6 form-group mb-4">
                                                <label>Product Code<span class="text-danger">*</span></label>
                                                <input class="form-control form-control-solid" name="productCode" type="text" placeholder="Unique ID" onchange="update_prd_url();">
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 form-group mb-4">
                                                <label>Product Name<span class="text-danger">*</span></label>
                                                <input class="form-control form-control-solid" name="productName" type="text" placeholder="Enter Product Name" onchange="update_prd_url();">
                                            </div>   
                                        </div>
                                        <div class="form-group mb-4">
                                            <label>Description<span class="text-danger">*</span></label>
                                            <textarea class="form-control form-control-solid textEditor" rows="4" name="description" placeholder="Product Description goes here"></textarea>
                                        </div>
                                                    </div>
                                        <!-- start dynamic columns -->
                                        <div class="row">
        <div class="col-sm-12 mt-2">
            <?php //} ?>
            <div class="mt-3" id="custom_field_sec">
                <div class="card">
                    <div class="card-box mb-0 p-2">
                        <div class="appendRows" data-count="1">
                            <div class="row">
                                <div class="form-group col-sm-4 col-12">
                                    <label class="form-control-label">MRP</label>
                                    <input type="text" name="mrp[]" id="mrp_1" class="c_name form-control"
                                        value="">
                                </div>
                                <div class="form-group col-sm-4 col-12">
                                    <label class="form-control-label">Price<span class="text-danger">*</span></label>
                                    <input type="text" name="price[]" id="price_1" class="c_email form-control"
                                        value="">
                                </div>
                                <div class="form-group col-sm-4 col-12">
                                    <label class="form-control-label">Quantity<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="quantity[]" id="quantity_1" class="c_name form-control"
                                        value="">
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label>Unit<span class="text-danger">*</span></label>
                                    <select class="form-control select2_unit" name="unit[]" id="unit_1">
                                        <option value="">--Select Unit--</option>
                                        <option value="Kg">Kg</option>
                                        <option value="Gram">Gram</option>
                                        <option value="Litre">Litre</option>
                                        <option value="Piece">Piece</option>
                                        <option value="Bunch">Bunch</option>
                                        <option value="Pack">Pack</option>

                                        <?php
                                                // Assuming the data is in $apiResponse['data_list']
                                                // if(is_array($prd_units) && !empty($prd_units['data_list'])){
                                                //     $unit = "";
                                                // foreach($prd_units['data_list'] as $prd_units){
                                                //     if($prd_units['l_value'] == $unit){
                                                //         echo '<option value="' . $prd_units['id'] . '" label="' . $prd_units['l_value'] . '" selected>';
                                                //     }else{
                                                //         echo '<option value="' . $prd_units['id'] . '" label="' . $prd_units['l_value'] . '">';
                                                //     }
                                                // }
                                                // }
                                                ?>
                                    </select>
                                    <script>$('[name=unit]').val(<?php echo $unit ?>);</script>
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label>CGST</label>
                                    <input class="form-control form-control-solid" name="CGST[]" id="CGST_1" type="text" placeholder="In %">
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label>SGST</label>
                                    <input class="form-control form-control-solid" name="SGST[]" id="SGST_1" type="text" placeholder="In %">
                                </div>           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 text-end" style="text-align:end">
                        <span><label class="action-icons btn btn-success btn-sm btn-addCustomField"
                                onclick="addCustomField();"><i class="fa fa-plus"></i> Add Field</label></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- end dynamic columns -->
                                         <!-- <div class="row"> -->

                                        <div class="form-group mb-2">
                                            <label>Product URL</label>
                                            <div class="input-group form-control-solid mb-3">
                                                <span class="input-group-addon"><?php echo base_url()?>products/</span>
                                                <input class="form-control form-control-solid" name="productURL" type="text" placeholder="Helps to show in google search...">
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
                                                    <input class="tagsinput form-control form-control-solid" name="metaTags" type="text" placeholder="Meta Tags" value="">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label>Meta Description</label>
                                                    <textarea class="form-control form-control-solid" rows="4" name="metaDescription" placeholder="Meta Description goes here"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Stock Check<span class="text-danger">*</span></label>
                                            <div class="mb-0">
                                                <label class="radio radio-inline radio-outline-success">
                                                    <input type="radio" name="in_stock" value="1" checked="">
                                                    <span class="input-span"></span>In Stock
                                                </label>
                                                <label class="radio radio-inline radio-outline-danger">
                                                    <input type="radio" name="in_stock" value="2">
                                                    <span class="input-span"></span>Out Of Stock
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Is New Arrival<span class="text-danger">*</span></label>
                                            <div class="mb-0">
                                                <label class="radio radio-inline radio-outline-success">
                                                    <input type="radio" name="is_primary" value="1" checked="">
                                                    <span class="input-span"></span>Yes
                                                </label>
                                                <label class="radio radio-inline radio-outline-danger">
                                                    <input type="radio" name="is_primary" value="2">
                                                    <span class="input-span"></span>No
                                                </label>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Status<span class="text-danger">*</span></label>
                                            <div class="mb-0">
                                                <label class="radio radio-inline radio-outline-success">
                                                    <input type="radio" name="status" value="32" checked="">
                                                    <span class="input-span"></span>Active
                                                </label>
                                                <label class="radio radio-inline radio-outline-danger">
                                                    <input type="radio" name="status" value="33">
                                                    <span class="input-span"></span>Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-lg-6">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
                                            <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo base_url()?>admin/products'">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            </div>               
                        </div>
                    </div>                    
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

</body>
</html>
  