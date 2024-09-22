<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Categories</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Categories</li>
                    <li class="breadcrumb-item">Listing</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Manage your categories</div>
                            </div>
                            <div class="ibox-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ibox">
                                            <div class="ibox-head">
                                                <div class="ibox-title">Add New Category</div>
                                            </div>
                                            <div class="ibox-body">
                                                <form id="add_cat_form">
                                                    <div class="form-group mb-4">
                                                        <label>Category Name <span class="text-danger">*</span> </label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" name="cat_name" data-edit="">
                                                            <span class="input-group-btn">
                                                                <button type="submit" class="btn btn-outline-secondary" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Adding">Add</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="load-menus">
                                            <ul id="load_menu_here" class="sortableLists list-group">
                                            </ul>                                                          
                                            <div class="float-right mt-3">
                                                <button id="btnOutput" type="button" class="btn btn-success"><i class="fa fa-check-square"></i> Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <!-- PAGE LEVEL PLUGINS-->
<script  src="<?php echo base_url() ?>ui/assets/plugins/jQuery-Menu-Editor/bs-iconpicker/js/iconset/iconset-fontawesome-4.7.0.min.js"></script>
<script  src="<?php echo base_url() ?>ui/assets/plugins/jQuery-Menu-Editor/bs-iconpicker/js/bootstrap-iconpicker.js"></script>
<script src="<?php echo base_url() ?>ui/assets/plugins/jQuery-Menu-Editor/jquery-menu-editor.min.js"></script>

<script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/categories.js"></script>
</body>
</html>
  