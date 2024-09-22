      <div class="mobile-menu-overlay"></div>
      <!-- End .mobil-menu-overlay -->
      <div class="mobile-menu-container">
         <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-cancel"></i></span>
            <nav class="mobile-nav">
               <ul class="mobile-menu">
                  <li class="active"><a href="<?php echo base_url()?>">Home</a></li>
                  <!-- <li><a href="about.html">About </a></li> -->
                  <li>
                     <a href="javascript:;">Categories</a>
                     <!-- <ul>
                        <?php 
                        $apidata = $this->curl->execute('categories', 'GET');
                        if($apidata['status']=='success' && !empty($apidata['data_list'])){
                        foreach ($apidata['data_list'] as $category){ ?>
                           <li>
                              <a class="sf-with-ul" href="<?php echo base_url()?>Products?cat_type=<?php echo strtolower($category['text']); ?>">
                                    <?php echo $category['text']; ?>
                              </a>
                              <?php if (isset($category['children']) && !empty($category['children'])){ ?>
                                    <ul>
                                       <?php foreach ($category['children'] as $subCategory){ ?>
                                          <li>
                                                <a href="<?php echo base_url()?>Products?cat_type=<?php echo strtolower($category['text']); ?>&scat_type=<?php echo strtolower($subCategory['text']); ?>">
                                                   <?php echo $subCategory['text']; ?>
                                                </a>
                                          </li>
                                       <?php }; ?>
                                    </ul>
                              <?php } ?>
                           </li>
                        <?php } ?>
                        <?php } ?>
                  </ul> -->

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
                      <li><a href="<?php echo base_url()?>Blog">Blogs</a></li>
                        <li><a href="<?php echo base_url()?>about_us">About Us</a></li>
                        <li><a href="<?php echo base_url()?>contact">Contact Us</a></li>
                        <li><a href="<?php echo base_url()?>subscription">Subscription Plans</a></li>
                        <li><a href="<?php echo base_url()?>rewards">Rewards </a></li>
                        <li><a href="<?php echo base_url()?>account/referals">Refer And Earn <i class="fa fa-whatsapp"></i></a></li>
               </ul>
            </nav>
         </div>
         <!-- End .mobile-menu-wrapper -->
      </div>