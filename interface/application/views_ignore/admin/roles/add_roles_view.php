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
                <h1 class="page-title">Create Role</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Roles</li>
                    <li class="breadcrumb-item">Create</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Create new user role and assign permissions</div>
                            </div>
                            <div class="ibox-body">  							
								<div class="p-20 form-holders">
                                     <form role="form" data-parsley-validate novalidate id="addedit_rolesform"> 
									 <input name="id" class="form-control" value=""  type="hidden" >
										<div class="row">												 
											<div class="col-sm-6">
												<div class="form-group row rolename-holder">
													<label for="name" class="col-sm-3 form-control-label">Role Name<span class="text-danger">*</span></label>
													<div class="col-sm-9">
														 <input name="name" class="form-control" data-edit="" type="text" >
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row rolename-holder">
													<label for="name" class="col-sm-3 form-control-label">Description<span class="text-danger">*</span></label>
													<div class="col-sm-9">
														 <input name="description" class="form-control" data-edit="" type="text" >
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<?php
												$where = array('status' =>1);
												// $modules=$this->adminmodel->get_table_data('admin_modules',$where,'*',true,'moduleId','asc');
												if(!empty($modules)){
													$i=1;
													foreach ($modules as $m){?>
												<div class="form-group row">
													<label for="<?php echo $m['moduleName'];?>" class="col-sm-4 form-control-label"><?php echo $i.". ".$m['moduleName'];?></label>
													<div class="col-sm-8">
														<div class="role-checks">
															<label class="checkbox checkbox-outline-success checkbox-inline">
					                                            <input type="checkbox" class="selectall" data-id="<?php echo $m['id'];?>">
					                                            <span class="input-span"></span>Select all
				                                        	</label>
															<label class="checkbox checkbox-outline-success checkbox-inline">
					                                            <input type="checkbox" name="view_<?php echo $m['id'];?>" class="canChkThis">
					                                            <span class="input-span"></span>View
				                                        	</label>
															<label class="checkbox checkbox-outline-success checkbox-inline">
					                                            <input type="checkbox" name="add_<?php echo $m['id'];?>" class="canChkThis">
					                                            <span class="input-span"></span>Add
				                                        	</label>
															<label class="checkbox checkbox-outline-success checkbox-inline">
					                                            <input type="checkbox" name="edit_<?php echo $m['id'];?>" class="canChkThis">
					                                            <span class="input-span"></span>Edit
				                                        	</label>
															<label class="checkbox checkbox-outline-success checkbox-inline">
					                                            <input type="checkbox" name="delete_<?php echo $m['id'];?>" class="canChkThis">
					                                            <span class="input-span"></span>Delete
				                                        	</label>

															
														</div>
													</div>
												</div>
												<?php 
													$i++;
													}
												}
												?>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-8 col-12 text-center mt-3">
	                                            <button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
	                                            <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo base_url()?>admin/roles'">Cancel</button>
											</div>
										</div>
                                   </form>
                               </div>								
							</div>
						</div>
					</div>
                </div><!-- end row -->
            </div> <!-- content -->
             <?php echo $footer; ?>

        </div>
    </div>
    <?php echo $commonJs; ?>
	<script src="<?php echo base_url() ?>ui/assets/js/my-scripts/roles.js"></script>
    </body>
</html>