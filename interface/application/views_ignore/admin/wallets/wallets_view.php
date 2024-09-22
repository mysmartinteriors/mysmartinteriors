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
                <h1 class="page-title">Wallets</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Wallets</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <!-- end row -->
                <div class="row">
                    <div class="col-12">                       
                        <div class="ibox">                            
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Here are the list of wallet balance of customers</div>
                                <!-- <div class="btn btn-success btn-labeled btn-labeled-left btn-icon btn-addData" data-id="">
                                    <span class="btn-label"><i class="la la-plus"></i></span>Add New
                                </div> -->
                            </div>
                                <div class="ibox-body adv_filter tblbox">
                                        <div class="row mb-4">
                                            <div class="col-sm-10 col-11">
                                                <div class="input-group  pull-left" id="adv-search">
                                                    <input type="text" class="form-control refine_filter clearAbleFilt" id="product" data-type="search" placeholder="Search for any records..." />
                                                    <input type="hidden" name="page" id="pagenumber" data-type="page" data-id="page"  class="refine_filter" />
                                                    <div class="input-group-btn">
                                                        <div class="btn-group" role="group">
                                                            <div class="dropdown dropdown-lg">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                                                                <div class="dropdown-menu">
                                                                    <form class="form-horizontal" role="form">
                                                                        <div class="row">
                                                                            <div class="col-6 form-group">
                                                                                <label for="contain">Search by customer</label>
                                                                                <input class="form-control refine_filter clearAbleFilt" data-type="support_enquiries-name" id="user" placeholder="Enter customer email...">
                                                                            </div>
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Filter by status</label>
                                                                                <select class="form-control refine_filter clearAbleFilt" data-type="support_enquiries-status" id="status">
                                                                                    <option data-type="support_enquiries-status" value="">--All datas--</option>
                                                                                    <option data-type="support_enquiries-status" value="1">Active</option>
                                                                                    <option data-type="support_enquiries-status" value="0">Inactive</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Sort by</label>
                                                                                <select class="form-control refine_filter" data-type="support_enquiries-sortby" id="sortBy">
                                                                                    <option data-type="support_enquiries-sortby" value="">Newest First</option>
                                                                                    <option data-type="support_enquiries-sortby" value="ASC">Oldest First</option>
                                                                                    <option data-type="support_enquiries-sortby" value="DESC">Last Updated</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 form-group">
                                                                                <label for="filter">Records per page</label>
                                                                                <select class="form-control refine_filter float-right" data-type="perpage" id="perpage">
                                                                                    <option data-type="perpage" value="10">10</option>
                                                                                    <option data-type="perpage" value="25">25</option>
                                                                                    <option data-type="perpage" value="50">50</option>
                                                                                    <option data-type="perpage" value="100">100</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear-btn-holder text-right mb-2">
                                                                            <a class="" id="clearFilter"><i class="ti-close"></i> Clear Search</a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-primary filter"><span class="fa fa-search" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-1">
                                                <a class="fullscreen-link float-right"><i class="ti-fullscreen"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="walletsTbl"></div>
                                            </div>
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                        </div> <!-- end row -->
            </div>
            <!-- END PAGE CONTENT-->
            <?php echo $footer ?>
        </div>
    </div>
    <?php echo $commonJs ?>
    <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/plugins/ckeditor/adapters/jquery.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/wallets.js"></script>
</body>
</html>
  