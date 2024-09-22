 <footer class="footer">
            <div class="footer-middle">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-3 col-sm-4">
                        <div class="widget">
                           <h4 class="widget-title">Contact Us</h4>
                           <ul class="contact-info">
                              <li>
                                 <span class="contact-info-label">Address:</span>Machonayakanahalli, Nelamangala, <br>Bengaluru, Karnataka-562 123
                              </li>
                              <li>
                                 <span class="contact-info-label">Phone:</span><a href="tel:">+91-87624 63738</a>
                              </li>
                              <li>
                                 <span class="contact-info-label">Email:</span> <a href="mailto:nalaaorganic@gmail.com">nalaaorganic@gmail.com</a>
                              </li>
                           </ul>
                        </div>
                        <!-- End .widget -->
                     </div>
                     <!-- End .col-lg-3 -->
                     <div class="col-lg-9 col-sm-8">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="widget">
                                 <h4 class="widget-title">Quick links </h4>
                                 <div class="row">
                                    <div class="col-sm-6 col-md-3">
                                       <ul class="links">
                                          <li><a href="<?php echo base_url(); ?>about_us">About Us</a></li>
                                          <li><a href="<?php echo base_url(); ?>contact">Contact Us</a></li>
                                       </ul>
                                    </div>
                                    <!-- End .col-sm-6 -->
                                    <div class="col-sm-6 col-md-3">
                                       <ul class="links">
                                           <?php if(is_uLogged()){ ?>
                                             <li><a href="<?php echo base_url(); ?>account">My Account</a></li>                                          
                                          <?php }else{ ?>
                                             <li><a href="#" class="login-link">Login</a></li>
                                          <?php } ?>
                                          
                                          <li><a href="<?php echo base_url(); ?>account/myorders">My orders</a></li>
                                       </ul>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                       <ul class="links">
                                          <li><a href="<?php echo base_url(); ?>info/refund_policy">Cancellation & Refund Policy</a></li>
                                          <li><a href="<?php echo base_url(); ?>info/faqs">FAQ</a></li>
                                          <li><a href="<?php echo base_url(); ?>info/terms_conditions">Terms & Conditions</a></li>
                                          <li><a href="<?php echo base_url(); ?>info">Privacy Policy</a></li>
                                       </ul>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                       <ul class="contact-info">
                                          <li>
                                             <span class="contact-info-label">Shop Working Days/Hours:</span>
                                             All Days / 10:00AM - 09:30PM
                                          </li>
                                          <li><a class='download_app' target="_blank" href="https://play.google.com/store/apps/details?id=io.nalaaorganic.app"> <img src="<?php echo base_url(); ?>ui/frontend/images/download-play-store.svg" alt="Google Play App" /></a></li>

                                          <li><a class='download_app' target="_blank" href='https://apps.apple.com/in/app/nalaa-organic/id6499208933'> <img src="<?php echo base_url(); ?>ui/frontend/images/download-app-store.svg" alt="Google Play App" /></a></li>
                                       </ul>
                                    </div>
                                 </div>
                                 <!-- End .row -->
                              </div>
                              <!-- End .widget -->
                           </div>
                           <!-- End .col-md-5 -->
                        </div>
                        <!-- End .row -->
                  
                     </div>

                     <div class="col-lg-12">
                     <div class="footer-bottom justify-content-center">
                           <p class="footer-copyright text-center">Nalaa Organic &copy;  2024.  All Rights Reserved</p>
                           <!-- <img src="<?php //echo base_url(); ?>ui/frontend/images/payments.png" alt="payment methods" class="footer-payments"> -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </footer>
         <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>
