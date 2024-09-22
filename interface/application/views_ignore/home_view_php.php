<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>
      <?php echo $title ?>
   </title>
   <meta name="keywords" content="" />
   <meta name="description" content="">
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
         <?php if (is_uLogged()) {
            $userId = get_userId(); ?>

            <!-- Button trigger modal -->
            <?php if ($subscriptionQ['status'] == 'success' && !empty($subscriptionQ['data_list'])) { ?>


               <!-- Modal -->
               <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h2 class="modal-title text-success" style="font-weight:bold" id="exampleModalLongTitle">
                              Subscription Plans</h2>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                              <?php foreach ($subscriptionQ['data_list'] as $subscription) { ?>
                                 <div class="col-md-4">
                                    <div class="card" data-id=" <?php echo $subscription['id'] ?>"
                                       style="margin:10px;border-radius:25px;background-color:<?php echo $subscription['name'] ?>;cursor:pointer">
                                       <div>
                                          <h2 class="text-center p-2" style="border-bottom:2px solid black">
                                             <?php echo ucfirst($subscription['name']);
                                             ?>
                                          </h2>
                                          <h2 class="text-center p-2">
                                             <?php
                                             echo $subscription['basic_amount']; ?>
                                          </h2>
                                       </div>
                                       <h4 class="text-center p-3" style="min-height:0px !impportant">
                                          Pay
                                          <?php echo $subscription['basic_amount'] ?>, Get
                                          <?php echo $subscription['wallet_points'] ?> Extra.
                                       </h4>
                                    </div>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            <?php } ?>


            <!-- Modal -->
            <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog"
               aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h2 class="modal-title text-success" style="font-weight:bold" id="exampleModalLongTitle">My Balance
                        </h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <?php $customerData = $this->curl->execute("customers/$userId", "GET");
                        $subscriptionAmount = 0;
                        $subscriptionPoints = 0;
                        if ($customerData['status'] == 'success' && !empty($customerData['data_list'])) {
                           $customerdata = $customerData['data_list'];
                           $subscriptionAmount = $customerdata['subscriptionAmount'];
                           $subscriptionPoints = $customerdata['subscriptionPoints'];
                        } ?>
                        <p class="px-3"> Total Account Balance :
                           <?php echo $subscriptionAmount ?>
                        </p>
                        <p class="px-3">
                           Total Wallet Balance :
                           <?php echo $subscriptionPoints ?>
                        </p>
                     </div>

                  </div>
               </div>
            </div>

            <div class="collapse multi-collapse" id="multiCollapseExample1">
               <div class="card card-body">
                  Total Account Balance : 5000
                  <br>
                  Total Wallet Balance : 7500
               </div>
            </div>

         <?php } ?>

         <?php if ($slidersQ['status'] == 'success' && !empty($slidersQ['data_list'])) { ?>

            <div class="home-slider-container">
               <div class="home-slider owl-carousel owl-theme owl-theme-light">
                  <?php
                  $s = 1;
                  foreach ($slidersQ['data_list'] as $sliders) {
                     ?>
                     <div class="home-slide">
                        <div class="slide-bg owl-lazy"
                           data-src="<?php echo base_url(); ?>uploads/sliders/<?php echo $sliders['slide_image'] ?>"></div>
                        <div class="container">
                           <div class="home-slide-content">
                              <h3 class="toggleCaption text-white">
                                 <?php echo $sliders['sub_text'] ?>
                              </h3>
                              <h1 class="toggleHeading text-white">
                                 <?php echo $sliders['main_text'] ?>
                              </h1>
                              <?php if (!empty($sliders['shop_url'])) { ?>
                                 <a href="<?php echo base_url() . 'Products?' . $sliders['shop_url'] ?>"
                                    class="btn btn-primary">Shop Now</a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>
            </div>

         <?php } ?>

         <div class="featured-products-section container  home-page-carousels">
            <h2 class="title text-center mb-3">Featured Products</h2>
            <?php
            if ($featuredQ['status'] == 'success' && !empty($featuredQ['data_list'])) { ?>
               <div class="owl-carousel owl-theme featured-products">
                  <?php
                  foreach ($featuredQ['data_list'] as $featuredRow) {
                     ?>
                     <div class="product">
                        <figure class="product-image-container">
                           <?php
                           if ($featuredRow['product_image'] != "") {
                              $img_prev = base_url() . $featuredRow['product_image'];
                           } else {
                              $img_prev = base_url() . 'uploads/default_product.jpg';
                           } ?>
                           <a href="<?php echo base_url() . 'product/' . $featuredRow['code'] ?>" class="product-image">
                              <img src="<?php echo $img_prev ?>" alt="<?php echo $featuredRow['name'] ?>">
                           </a>
                           <a href="javascript:void(0);" class="btn-quickview hide_quickview_mob"
                              data-id="<?php echo $featuredRow['id'] ?>">Quickview</a>
                        </figure>
                        <div class="product-details featured_details">
                           <h1 class="product-title product_title_updated">
                              <a href="<?php echo base_url() . 'product/' . $featuredRow['code'] ?>" style="color:#0f7a4e">
                                 <?php echo $featuredRow['name']; ?>
                              </a>
                           </h1>

                           <?php if ($featuredRow['in_stock'] == 1 && $featuredRow['product_metrics']) { ?>
                              <div class="">
                                 <select class="form-control select2_unit homeMetrics" name="metricsId">
                                    <?php $json_data = $featuredRow['product_metrics'];
                                    foreach ($json_data as $details) { ?>
                                       <option data-mrp="<?php echo $details['mrp'] ?>" data-price="<?php echo $details['price'] ?>"
                                          value="<?php echo $details['id'] ?>">
                                          <?php echo $details['quantity'] . ' ' . $details['unit'] ?>
                                       </option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <div class="d-flex justify-content-between px-2 priceDiv">
                                 <p class="priceParent" style="text-align: left">
                                    <strong>&#8377; <span class="price"></span></strong>
                                    <span style="text-decoration: line-through; font-size: 10px;">&#8377; <span
                                          class="mrp"></span></span>
                                 </p>
                                 <?php if(!empty($featuredRow['badge'])){ ?>
                                <span class="badge badge-success badge-sm" style="height: 20px"><?php echo !empty($featuredRow['badge'])?$featuredRow['badge']:''; ?></span>
                                <?php } ?>
                              </div>

                              <div class="product-action">
                                 <?php if ($featuredRow['in_stock'] == 1) { ?>
                                    <div class="product-single-qty">
                                       <input class="horizontal-quantity form-control" id="featuredPrdQty" type="text" value="1"
                                          name="quantity" readonly>
                                    </div>
                                    <button type="button" class="btn-primary paction add-cart" title="Add to Cart"
                                       data-id="<?php echo $featuredRow['id'] ?>"
                                       data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                       <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                    </button>
                                 <?php } ?>
                              </div>

                              <div id="incre-decre d-none">
                              </div>
                           <?php } ?>
                           <!-- End .product-action -->
                        </div>
                        <!-- End .product-details -->
                     </div>
                  <?php } ?>
                  <!-- End .product -->
               </div><!-- End .featured-products -->
            <?php } else { ?>
               <article class="entry text-center mb-0">
                  <div class="entry-body">
                     <div class="entry-content">
                        <p>We are in the process to provide featured products to you, please hit back after sometime...</p>
                        <a href="<?php echo base_url() ?>Products" class=""><span class='btn-label'>Browse More <i
                                 class="icon-angle-right"></i></span></a>
                     </div><!-- End .entry-content -->
                  </div><!-- End .entry-body -->
               </article>
            <?php } ?>
         </div><!-- End .container -->

         <?php if ($advertiseQ['status'] == 'success' && !empty($advertiseQ['data_list'])) { ?>
            <div class="promo-section">
               <div class="promo-slider owl-carousel owl-theme owl-theme-light">
                  <?php
                  $s = 1;
                  foreach ($advertiseQ['data_list'] as $advertise) {
                     ?>
                     <img src="<?php echo base_url(); ?>uploads/sliders/<?php echo $advertise['slide_image'] ?>" alt="">
                  <?php } ?>
               </div>
            </div>
         <?php } ?>

         <!-- End .promo-section -->
         <div class="mb-5"></div>

         <!-- margin -->

         <div class="featured-products-section container  home-page-carousels new_arrivals_new">
            <h2 class="title text-center mb-3">New Arrivals</h2>
            <?php if ($arrivalsQ['status'] == 'success' && !empty($arrivalsQ['data_list'])) { ?>
               <div class="owl-carousel owl-theme featured-products">
                  <?php foreach ($arrivalsQ['data_list'] as $arrivedRow) {
                     if($arrivedRow['in_stock'] == 1){
                     ?>
                     <div class="product">
                        <figure class="product-image-container">
                           <?php
                           if ($arrivedRow['product_image'] != "") {
                              $img_prev = base_url() . $arrivedRow['product_image'];
                           } else {
                              $img_prev = base_url() . 'uploads/default_product.jpg';
                           }
                           ?>
                           <a href="<?php echo base_url() . 'product/' . $arrivedRow['code'] ?>" class="product-image">
                              <img src="<?php echo $img_prev ?>" alt="<?php echo $arrivedRow['name'] ?>">
                           </a>
                           <a href="javascript:void(0);" class="btn-quickview hide_quickview_mob"
                              data-id="<?php echo $arrivedRow['id'] ?>" data-metricsId="">Quickview</a>
                        </figure>
                        <div class="product-details featured_details">
                           <h1 class="product-title product_title_updated">
                              <a href="<?php echo base_url() . 'product/' . $arrivedRow['code'] ?>" style="color:#0f7a4e">
                                 <?php echo $arrivedRow['name']; ?>
                              </a>
                           </h1>

                           <?php if (!empty($arrivedRow['product_metrics']) && $arrivedRow['in_stock'] == 1) { ?>
                              <div class="metricsParent">
                                 <select class="form-control select2_unit homeMetrics" name="metricsId">
                                    <?php $json_data = $arrivedRow['product_metrics'];
                                    foreach ($json_data as $details) { ?>
                                       <option data-mrp="<?php echo $details['mrp'] ?>" data-price="<?php echo $details['price'] ?>"
                                          value="<?php echo $details['id'] ?>">
                                          <?php echo $details['quantity'] . ' ' . $details['unit'] ?>
                                       </option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <div class="d-flex justify-content-between px-2 priceDiv">
                                 <p class="priceParent" style="text-align: left">
                                    <strong>&#8377; <span class="price"></span></strong>
                                    <span style="text-decoration: line-through; font-size: 10px;">&#8377; <span
                                          class="mrp"></span></span>
                                 </p>

                                 <?php if(!empty($arrivedRow['badge'])){ ?>
                                    <span class="badge badge-success badge-sm" style="height: 20px"><?php echo !empty($arrivedRow['badge'])?$arrivedRow['badge']:''; ?></span>
                                <?php } ?>

                              </div>
                           <?php } ?>

                           <!-- End .price-box -->
                           <div class="product-action">
                              <?php if ($arrivedRow['in_stock'] == 1) { ?>
                                 <div class="product-single-qty">
                                    <input class="horizontal-quantity form-control" id="featuredPrdQty" type="text" value="1"
                                       name="quantity" readonly>
                                 </div>
                                 <button type="button" class="btn-primary paction add-cart" title="Add to Cart"
                                    data-id="<?php echo $arrivedRow['id'] ?>"
                                    data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin'></i></span> Adding to Cart...">
                                    <span><i class="fa fa-shopping-bag"></i> Add to Cart</span>
                                 </button>
                              <?php } ?>
                           </div>
                           <!-- End .product-action -->
                        </div>
                        <!-- End .product-details -->
                     </div>
                  <?php } ?>
                  <?php } ?>
                  <!-- End .product -->
               </div><!-- End .featured-products -->
            <?php } else { ?>
               <article class="entry text-center mb-0">
                  <div class="entry-body">
                     <div class="entry-content">
                        <p>We are in the process to provide featured products to you, please hit back after sometime...</p>
                        <a href="<?php echo base_url() ?>Products" class=""><span class='btn-label'>Browse More <i
                                 class="icon-angle-right"></i></span></a>
                     </div><!-- End .entry-content -->
                  </div><!-- End .entry-body -->
               </article>
            <?php } ?>
         </div><!-- End .container -->

         <!-- End .carousel-section -->
         <div class="mb-5"></div>


         <!-- margin -->
         <div class="info-section">
            <div class="container">
               <div class="row">
                  <div class="col-md-4">
                     <div class="feature-box feature-box-simple text-center">
                        <div class="d-flex justify-content-center p-2">
                           <img style="height: 80px;" src="<?php echo base_url() ?>ui/frontend/images/delivery.png"
                              class="img-fluid">
                        </div>
                        <!-- <i class="fa fa-thumbs-up fa-5x"></i> -->
                        <div class="feature-box-content">
                           <h3>Free delivery order Above 375 /- RS</h3>
                           <p>Excellent Products and Excellent Service</p>
                        </div>
                        <!-- End .feature-box-content -->
                     </div>
                     <!-- End .feature-box -->
                  </div>
                  <!-- End .col-md-4 -->
                  <div class="col-md-4">
                     <div class="feature-box feature-box-simple text-center">
                        <div class="d-flex justify-content-center p-2">
                           <img style="height: 80px;" src="<?php echo base_url() ?>ui/frontend/images/onlinepayment.png"
                              class="img-fluid">
                        </div>

                        <!-- <i class="fa fa-lock fa-5x"></i> -->
                        <div class="feature-box-content">
                           <h3>Online Payment / Cash On Delivery</h3>
                           <p>100% Safe & Fast Payment Protection</p>
                        </div>
                        <!-- End .feature-box-content -->
                     </div>
                     <!-- End .feature-box -->
                  </div>
                  <!-- End .col-md-4 -->
                  <div class="col-md-4">
                     <div class="feature-box feature-box-simple text-center last-child">
                        <div class="d-flex justify-content-center p-2">
                           <img style="height: 80px;" src="<?php echo base_url() ?>ui/frontend/images/support.png"
                              class="img-fluid">
                        </div>

                        <!-- <i class="fa fa-undo fa-5x"></i> -->
                        <div class="feature-box-content">
                           <h3>Online support</h3>
                           <p>Easy to Communicate</p>
                        </div>
                        <!-- End .feature-box-content -->
                     </div>
                     <!-- End .feature-box -->
                  </div>
                  <!-- End .col-md-4 -->
               </div>
               <!-- End .row -->
            </div>
            <!-- End .container -->
         </div>
         <!-- End .info-section -->
         <!-- <section class="section_testi_fb ">
            <div class="container">
               <div class="row">
                  <div class="col-md-12 col-center m-auto">
                     <?php if ($testimonialQ['status'] == 'success' && !empty($testimonialQ['data_list'])) { ?>
                        <div class="testimonials-section">
                           <div class="container">
                              <h2 class="h2 title text-center mb-5">HAPPY CLIENTS</h2>
                              <div class="testimonials-carousel owl-carousel owl-theme">
                                 <?php
                                 $s = 1;
                                 foreach ($testimonialQ['data_list'] as $testimonials) { ?>
                                    <div class="testimonial">
                                       <div class="testimonial-owner">
                                          <figure>
                                             <?php if (!empty($testimonials['image'])) { ?>
                                                <img
                                                   src="<?php echo base_url(); ?>uploads/testimonials/<?php echo $testimonials['image'] ?>"
                                                   alt="">
                                             <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>ui/images/default_avathar.jpg" alt="">
                                             <?php } ?>

                                          </figure>
                                          <div>
                                             <h4 class="testimonial-title">
                                                <?php echo $testimonials['name'] ?>
                                             </h4>
                                          </div>
                                       </div>
                                       <blockquote>
                                          <p>
                                             <?php echo $testimonials['description'] ?>
                                          </p>
                                       </blockquote>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               </div> 
            </div>
         </section> -->
      </main>

      <!-- End .main -->
      <?php echo $footer ?>
      <!-- End .footer -->
   </div>
   <!-- End .page-wrapper -->
   <?php echo $mobile_menu ?>
   <!-- End .mobile-menu-container -->

   <?php echo $commonJs ?>


   <script>
      function showPopup() {
         const message = `<?php echo $popup_message ?>`
         const redirectUrl = `<?php echo $loginUrl ?>`
         if (message) {
            swal({
               title: '',
               text: message,
               icon: 'warning',
               // buttons: ['OK', 'Remind me later'],
               showCancelButton: true,
               confirmButtonText: 'OK',
               cancelButtonText: 'Remind Me Later'
            }, function (result) {
               if (result) {
                  window.location.href = redirectUrl; // Redirect to the specified URL
               } else {
                  // console.log('User chose to be reminded later.');
                  // const remindLaterTime = Date.now() + 5 * 60 * 1000; // 5 minutes from now
                  // // const remindLaterTime = Date.now() + 30 * 1000; // 30 seconds from now
                  // localStorage.setItem('remindLaterTime', remindLaterTime);
               }
            });
         }
      }
      // Check if it's time to show the popup
      // function checkReminder() {
      //    const remindLaterTime = localStorage.getItem('remindLaterTime');
      //    if (remindLaterTime && Date.now() > remindLaterTime) {
      //       showPopup();
      //       localStorage.removeItem('remindLaterTime'); // Clear the reminder
      //    }
      // }

      $(document).ready(function () {
         // setInterval(()=>{
         //    checkReminder();
         // }, 1000)

            showPopup();

         $(".owl-carousel").owlCarousel({
            lazyLoad: true,
            loop: false,
            margin: 0,
            items: 1,
            nav: true,
            dots: true
         })


         $(".homeMetrics").each(function () {
            var selectElement = $(this);
            var defaultPrice = parseInt(selectElement.find('option:first').data('price'));
            var defaultMrp = parseInt(selectElement.find('option:first').data('mrp'));

            var defaultMetricsId = parseInt(selectElement.val());
            var addToCartBtnElement = selectElement.parent().next().next().find('.add-cart')
            addToCartBtnElement.attr('data-metricsId', defaultMetricsId)

            var priceElement = selectElement.parent().next().find('.price');
            var mrpElement = selectElement.parent().next().find('.mrp');
            var discountElement = selectElement.parent().parent().prev();
            if (defaultPrice > 0 && defaultMrp > 0) {
               let defaultDiscount = (((parseInt(defaultMrp) - parseInt(defaultPrice)) * 100) / parseInt(defaultMrp)).toFixed(0)
               console.log(discountElement)
               let priceOff = `<span class="badge bg-success text-white" style="position: absolute">${defaultDiscount}% off</span>`;
               discountElement.prepend(priceOff)
            }
            priceElement.text(defaultPrice);
            mrpElement.text(defaultMrp);

            selectElement.change(function () {
               var selectedOption = selectElement.find('option:selected');
               var price = selectedOption.data('price');
               var mrp = selectedOption.data('mrp');
               priceElement.text(price);
               mrpElement.text(mrp);
               addToCartBtnElement.attr('data-metricsId', metricsId);

            });
         })

      })
   </script>

</body>

</html>