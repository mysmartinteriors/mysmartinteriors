<!-- START HEADER-->
        <header class="header">
            <div class="page-brand">
                <a href="<?php echo base_url()?>delivery/dashboard">
                    <img src="<?php echo base_url()?>ui/frontend/images/logo.png">
                </a>
            </div>
            <div class="flexbox flex-1">
                <!-- START TOP-LEFT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler" href="javascript:;">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                    </li>
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            <?php 
                                $userId = User::get_deliveryId();
                                $apidata = $this->curl->execute("delivery/$userId", "GET");
                                // print_R($apidata);exit();
                                if($apidata['status']=='success' && !empty($apidata['data_list'])){
                                    $dpPicUrl=base_url().'uploads/delivery/'.$apidata['data_list']['profile_picture'];
                                }else{
                                    $dpPicUrl=base_url().'uploads/site/default_avathar.jpg';
                                }
                            ?>
                            <span>Hello, <?php echo ucfirst(User::get_deliveryName()); ?></span>
                            <img src="<?php echo $dpPicUrl ?>" alt="" />
                        </a>
                        <div class="dropdown-menu dropdown-arrow dropdown-menu-right admin-dropdown-menu">
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-header">
                                <div class="admin-avatar">
                                    <img src="<?php echo $dpPicUrl ?>" alt="" />
                                </div>
                                <div>
                                    <h5 class="font-strong text-white">User: <?php echo ucfirst(User::get_deliveryName()); ?></h5>
                                    <a href="<?php echo base_url() ?>delivery/logout">Logout <i class="fa fa-sign-in-alt"></i></a>
                                </div>
                            </div>
                           
                        </div>
                    </li>
                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>
        <!-- END HEADER-->

         <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" id="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
	   <script type="text/javascript">
	   var csrf_test_name = '<?php echo $this->security->get_csrf_hash(); ?>';
	   </script>