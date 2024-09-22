<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <link href="<?php echo base_url()?>ui/assets/plugins/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
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
                <h1 class="page-title">General Settings</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/settings">Settings</a></li>
                    <li class="breadcrumb-item">General</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <?php $megamenu =  $settings_data['megamenu']; ?>
                    <div class="col-lg-6">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Megemenu Settings</div>
                            </div>
                            <div class="ibox-body">
                              <form role="form" id="megamenu_form">
                                <div class="form-group mb-4 row">                                        
                                  <label class="col-sm-5 col-form-label">Activate megamenu</label>
                                  <div class="col-sm-7">
                                    <label class="ui-switch switch-icon mr-2 mb-0">
                                        <input type="checkbox" name="status" <?php if($megamenu['status']['value']){ echo "checked"; } ?>>
                                        <span></span>
                                    </label>
                                  </div>
                                </div>
                                <div class="form-group mb-4 row">
                                    <label class="col-sm-5 col-form-label">Columns per row</label>
                                    <div class="col-sm-7">
                                        <select class="form-control mm_columns" name="columns">
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                      </select>
                                      <script>$('.mm_columns').val(<?php echo $megamenu['columns']['value'] ?>);</script>
                                    </div>
                                </div>
                                <div class="form-group mb-4 row">
                                    <label class="col-sm-5 col-form-label">Categories per column</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="items" value="<?php echo $megamenu['items']['value'] ?>" min="1" max="10">
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-12 text-center mt-3">
                                      <button type="submit" class="btn btn-primary btn-air mr-2" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Updating...">Update</button>
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
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/general_settings.js"></script>
</body>
</html>
  