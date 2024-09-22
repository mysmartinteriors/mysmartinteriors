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
                <h1 class="page-title">Vendors</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Vendor Challan History</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <!-- end row -->
                <div class="row">
                    <div class="col-12 my-2">
                    <div class="col-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Vendor Information</div>
                            </div>
                            <div class="ibox-body">
                                <div class="row mb-4 justify-content-center">
                                    <div class="col-sm-12 col-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Name</td>
                                                <td><?php echo $datas['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Code</td>
                                                <td><?php echo $datas['code'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><?php echo $datas['email'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td><?php echo $datas['phone'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td><?php echo $datas['address'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Pincode</td>
                                                <td><?php echo $datas['pincode'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Challan History</div>
                                <div class="btn btn-success btn-labeled btn-labeled-left btn-icon btn-addData"
                                    data-id="">
                                    <span class="btn-label"><i class="la la-plus"></i></span>Add New
                                </div>
                            </div>
                            <div class="ibox-body adv_filter tblbox">
                                <div class="row mb-4">
                                    <div class="col-sm-10 col-11">
                                        <div class="input-group  pull-left" id="adv-search">
                                            <input type="text" class="form-control refine_filter clearAbleFilt"
                                                id="product" data-type="search"
                                                placeholder="Search for any records..." />
                                            <input type="hidden" name="page" id="pagenumber" data-type="page"
                                                data-id="page" class="refine_filter" />
                                            <div class="input-group-btn">
                                                <div class="btn-group" role="group">
                                                    <div class="dropdown dropdown-lg">
                                                        <button type="button" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false"><span
                                                                class="caret"></span></button>
                                                        <div class="dropdown-menu">
                                                            <form class="form-horizontal" role="form">
                                                                <div class="row">
                                                                    <div class="col-6 form-group">
                                                                        <label for="filter">Records per page</label>
                                                                        <select
                                                                            class="form-control refine_filter float-right"
                                                                            data-type="perpage" id="perpage">
                                                                            <option data-type="perpage" value="10">10
                                                                            </option>
                                                                            <option data-type="perpage" value="25">25
                                                                            </option>
                                                                            <option data-type="perpage" value="50">50
                                                                            </option>
                                                                            <option data-type="perpage" value="100">100
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6 form-group">
                                                                        <label for="filter">Order by</label>
                                                                        <select class="form-control refine_filter" data-type="orderby" id="orderby">
                                                                            <option data-type="orderby" value="DESC">Newest First</option>
                                                                            <option data-type="orderby" value="ASC">Oldest First</option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- <div class="col-6 form-group">
                                                                        <label for="filterByStatus"></label>
                                                                        <select name="" id="" class="form-control refine_filter float_right" data-type="vendors_challan_history-status" id="vendors_challan_history-status">
                                                                            <option data-type="vendors_challan_history-status" >--Select--</option>
                                                                            <option data-type="vendors_challan_history-status" value="60">Completed</option>
                                                                            <option data-type="vendors_challan_history-status" value="59">Pending</option>
                                                                        </select>
                                                                    </div> -->
                                                                </div>
                                                                <div class="clear-btn-holder text-right mb-2">
                                                                    <a class="" id="clearFilter"><i
                                                                            class="ti-close"></i> Clear Search</a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-primary filter"><span
                                                            class="fa fa-search" aria-hidden="true"></span></button>
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
                                        <div id="challanTable"></div>
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
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/vendors_challan_history.js"></script>
</body>

</html>