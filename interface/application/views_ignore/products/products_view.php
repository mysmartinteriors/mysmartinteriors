<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo $title ?></title>
      <meta name="keywords" content="" />
      <meta name="description" content="">
      <meta name="author" content="">      
      <!-- Plugins CSS File -->
      <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>ui/frontend/css/plugins.css"> -->
      <?php echo $commonCss ?>
   </head>
   <body>
    <script>0</script>
      <div class="page-wrapper" id="prd_list_page">
         <!-- start header-->
         <?php echo $header_main ?>
         <!-- End .header -->
        <main class="main">
            <nav aria-label="breadcrumb" class="top-nav breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>home"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>Products">Products</a></li>
                        <?php if($q!=''){ ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $q; ?></li>
                        <?php } ?>
                    </ol>
                </div><!-- End .container -->
            </nav>

            
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="boxed-slider owl-carousel owl-carousel-lazy owl-theme owl-theme-light mb-2">
                            <div class="category-slide">
                                <div class="slide-bg owl-lazy"  data-src="<?php echo base_url() ?>uploads/banners/showroom_banner1.jpg"></div>
                                <div class="banner boxed-slide-content offset-1">
                                </div>
                            </div>
        
                            <div class="category-slide">
                                <div class="slide-bg owl-lazy"  data-src="<?php echo base_url(); ?>uploads/banners/showroom_banner2.jpg"></div>
                                <div class="banner boxed-slide-content offset-1">
                                </div>
                            </div>
                        </div>

                        <nav class="toolbox top-prdfilter-holder">
                            
                            <div class="toolbox-left">
                                <label class="prd_counts"></label>
                                
                            </div>

                            <div class="toolbox-item toolbox-show">
                              <div class="toolbox-item toolbox-sort">
                                    <div class="select-custom">
                                        <label>Show</label>
                                        <select name="perpage" class="form-control refine_filter prdorderby" data-type="perpage">
                                            <option data-type="perpage" data-name="perpage" value="" selected="selected">10</option>
                                            <option data-type="perpage" data-name="perpage" value="20">20</option>
                                            <option data-type="perpage" data-name="perpage" value="30">30</option>
                                        </select>
                                    </div>

                                </div><!-- End .toolbox-item -->
                            </div>
                        </nav>
                        
                        
                        <div id="productsListTbl">
                            <div class="row row-sm">
                                <div class="col-lg-12">
                                    <div id="ajax_loadprd"></div>
                                </div>
                            </div>
                        </div>

                        <nav class="toolbox toolbox-pagination">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    
                                    <!-- <div class="prd_pagination">
                            
                                    </div> -->
                                </div>
                            </div>
                        </nav>
                    </div><!-- End .col-lg-9 -->

                    <aside class="sidebar-shop col-lg-3 order-lg-first">
                        <?php echo $sidebar_filter ?>
                    </aside>
                </div><!-- End .row -->
            </div><!-- End .container -->

            <div class="mb-2"></div><!-- margin -->
        </main><!-- End .main -->


         <!-- End .main -->
         <?php echo $footer ?>
         <!-- End .footer -->
      </div>
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
      
      <?php echo $commonJs ?>

      <!-- <script src="<?php echo base_url(); ?>ui/frontend/js/jquery-ui.js"></script>  -->
      <script src="<?php echo base_url(); ?>ui/frontend/scripts/filter_products.js"></script> 

   </body>
</html>