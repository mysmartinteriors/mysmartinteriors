<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
<link rel='stylesheet' id='maxmegamenu-css'  href='<?php echo base_url(); ?>ui/assets/plugins/megamenu/megamenu.css' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo base_url(); ?>ui/assets/plugins/megamenu/megamenu-helper.css' type='text/css' media='all' />

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
                <h1 class="page-title">Menu Settings</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Categories</li>
                    <li class="breadcrumb-item">Menu Settings</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Manage your category menu structure</div>
                                <div class="pull-right">
                                    <span>
                                        <label class="ui-switch switch-icon mr-2 mb-0">
                                            <input type="checkbox" name="mega_menu_status" id="mega_menu_status" value="<?php echo $status ?>" <?php if($status){ echo "checked"; } ?>>
                                            <span></span>
                                        </label>
                                    </span>                                   
                                    <button type="submit" id="save_mega_menu" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Saving..." class="btn btn-sm btn-success"><i class="fa fa-check-square"></i> Save</button>
                                </div>
                            </div>
                            <div class="ibox-body nav-menus-php">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="cboxContent">
                                            <div class="mm_content mega_menu">
                                                <div id="megamenu-grid" class="ui-sortable">     
                                                    
                                                </div>
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
<script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/category_settings.js"></script>
<script src="<?php echo base_url(); ?>ui/assets/plugins/megamenu/megamenu.js"></script>
</body>
</html>
  