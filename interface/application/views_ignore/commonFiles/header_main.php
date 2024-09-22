<style>
   @-webkit-keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.4;}
}
.myblink{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 2s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	/* -webkit-animation-direction: alternate; */
}
</style>
<header class="header">
            <div class="header-top">
               <div class="container">
                  <div class="header-left header-dropdowns">
                     <ul class="social-icons ">
                        <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61557720083912" class="facebook"><i class="icon-facebook"></i></a></li>
                        <li><a target="_blank" href="https://x.com/nalaaorganic" class="twitter"><i class="icon-twitter"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/nalaaorganic" class="linkedin"><i class="icon-instagram"></i></a></li>
                     </ul>
                  </div>
                  <!-- End .header-left -->
                  <?php if(is_uLogged()){ ?>
                  <div class="header-right">
                     <p class="welcome-msg">Welcome <?php echo get_userName() ?>! </p>
                     <div class="header-dropdown dropdown-expanded">
                        <a href="#"><i class="icon-user" style='font-size:25px'></i></a>
                        <div class="header-menu" style="background-color:#6d974f;">
                           <ul>
                              <li><a href="<?php echo base_url()?>account/dashboard">MY ACCOUNT </a></li>
                              <li><a href="<?php echo base_url()?>cart">MY CART</a></li>
                              <li><a href="<?php echo base_url()?>account/logout">LOGOUT</a></li>
                              <li><a href="<?php echo base_url()?>contact">SUPPORT</a></li>
                           </ul>
                        </div>
                        <!-- End .header-menu -->
                     </div>
                     <!-- End .header-dropown -->
                  </div>
                  <?php } else { ?>
                     <div class="header-right">
                     <p class="welcome-msg">Welcome to Nalaa Organic! </p>
                     <div class="header-dropdown dropdown-expanded">
                        <a href="#"><i class="icon-user" style='font-size:25px;'></i></a>
                        <div class="header-menu" style="background-color:#6d974f">
                           <ul>
                              <li class="head-ulog-link"><a href="javascript:void(0);" class="login-link">LOGIN & SIGNUP </a></li>
                              <li><a href="<?php echo base_url()?>cart">CART</a></li>
                              <li><a href="<?php echo base_url()?>contact">SUPPORT</a></li>
                           </ul>
                        </div>
                        <!-- End .header-menu -->
                     </div>
                     <!-- End .header-dropown -->
                  </div>
                  <?php  } ?>
                  <!-- End .header-right -->
               </div>
               <!-- End .container -->
            </div>
            <!-- End .header-top -->
            <div class="header-middle primary_header">
               <div class="container">
                  <div class="header-left">
                     <a href="<?php echo base_url() ?>" class="logo">
                     <img src="<?php echo base_url(); ?>ui/frontend/images/logo.png" alt=" Logo">
                     </a>
                  </div>
                  <!-- End .header-left -->
                  <div class="header-center">
                     <div class="header-search">
                        <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                        <form id="search_mini_form" class="search_form search_form_hide" action="<?php echo base_url() ?>Products" method="get">
                           <div class="header-search-wrapper">
                              <input  class="form-control text" type="text" autocomplete="off" name="search"  value="<?php echo $this->input->get('q'); ?>" placeholder="Search our store...">
                              <button class="btn" type="submit"><i class="icon-magnifier"></i></button>
                           </div>
                        </form>
                        <!-- End .header-search-wrapper -->
                     </div>
                     <!-- End .header-search -->
                  </div>
                  <!-- End .headeer-center -->
                  <div class="header-right">
                     <button class="mobile-menu-toggler" type="button">
                     <i class="icon-menu"></i>
                     </button>
                     <div class="header-contact">
                        <span>Call us now</span>
                        <a href="tel:#"><strong>+91-8762 463738</strong></a>
                     </div>
                     <!-- End .header-contact -->
                     <div class="cart-holder">
                        <a  href="<?php echo base_url()?>cart" class="cart-link">
                        <span class="cart-count cartQuantity">0</span>
                        </a>
                     </div>
                  </div>
                  <!-- End .header-right -->
               </div>
               <!-- End .container -->
            </div>


            <!-- =====search-field for small devices====== -->
               <section class='small_screen_search'> 
                  <div class="container container_search">
                  <!-- End .header-left -->
                 <div class=" header-center-xs">
                     <div class="header-search">
                        <!-- <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a> -->
                        <form id="search_mini_form" class="search_form" action="<?php echo base_url() ?>Products" method="get">
                           <div class="header-search-wrapper header-search-wrapper-xs">
                              <input  class="form-control text" type="text" autocomplete="off" name="search"  value="<?php echo $this->input->get('q'); ?>" placeholder="Search our store...">
                              <button class="btn" type="submit"><i class="icon-magnifier"></i></button>
                           </div>
                        </form>
                        <!-- End .header-search-wrapper -->
                     </div>
                     <!-- End .header-search -->
                  </div>
            <!-- ====search-field for small devices====== -->
            </div>
            </section>


            <!-- End .header-middle -->
            <div class="header-bottom sticky-header header_full_width">
               <div class="container">
                  <nav class="main-nav">
                     <ul class="menu sf-arrows sf-js-enabled">
                        <li><a href="<?php echo base_url()?>">Home</a></li>
                        <li class="">
                           <a href="javascript:;" class="sf-with-ul">Categories</a>
                           <ul>
                           <?php 
                                 $apidata = $this->curl->execute('categories', 'GET');
                                 if($apidata['status']=='success' && !empty($apidata['data_list'])){
                                 foreach ($apidata['data_list'] as $category){ ?>
                                    <li>
                                       <a class="sf-with-ul" href="<?php echo base_url()?>Products?cat_type=<?php echo strtolower($category['code']); ?>">
                                             <?php echo $category['text']; ?>
                                       </a>
                                       <?php if (isset($category['children']) && !empty($category['children'])){ ?>
                                             <ul>
                                                <?php foreach ($category['children'] as $subCategory){ ?>
                                                   <li>
                                                         <a href="<?php echo base_url()?>Products?cat_type=<?php echo strtolower($category['code']); ?>&scat_type=<?php echo strtolower($subCategory['code']); ?>">
                                                            <?php echo $subCategory['text']; ?>
                                                         </a>
                                                   </li>
                                                <?php }; ?>
                                             </ul>
                                       <?php } ?>
                                    </li>
                                 <?php } ?>
                                 <?php } ?>
                           </ul>
                        </li>                        
                        <li><a href="<?php echo base_url()?>Products?max_price=290">Products</a></li>
                        <li><a href="<?php echo base_url()?>Blog">Blog</a></li>
                        <li><a href="<?php echo base_url()?>about_us">About Us</a></li>
                        <li><a href="<?php echo base_url()?>contact">Contact Us</a></li>
                        <li><a href="<?php echo base_url()?>subscription">Subscription Plans</a></li>
                        <li><a href="<?php echo base_url()?>rewards">Rewards</a></li>
                        <!-- <li><a href="<?php //echo base_url()?>account/referals">Refer And Earn</a></li> -->
                        <li>
                        <?php $userId=get_userId(); ?>
                        <?php
                           $whatsappmsg = "Hey, I wanted to share this amazing referral for Nalaaorganic for fresh fruits and vegetables at your doorsteps and get amazing discounts on every order.\n\n*Use my mobile number as referral code*\n\nAndroid \n https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en\n\nIOS \nhttps://apps.apple.com/in/app/nalaa-organic/id6499208933\n\nWEB\nhttps://nalaaorganic.com/account/login\n\nPlease reply 'OK' to enable the link.\n\nThank You";
                              $whatsappmsg = rawurlencode($whatsappmsg);
                        ?>
                           <a target="_blank" href="<?php echo 'https://wa.me/?text='.$whatsappmsg ?>" class="myblink text-danger" href="javascript:;" customer-id="<?php echo $userId ?>"><i class="fa fa-whatsapp"></i>&nbsp; Refer And Earn</a>
                        </li>
                     </ul>
                  </nav>
                  <button class="mobile-menu-toggler" type="button">
                        <i class="icon-menu"></i>
                  </button>
               </div>
            </div>
            <div >
               <div class="text-center" style="background-color: #6d974f;">
                  <p class="blink_me">Delivery available only on Tuesday, Wednesday, Friday  and Saturday | Free delivery for the orders above ₹ 375</p>
               </div>
            </div>
            <!-- End .header-bottom -->
         </header>