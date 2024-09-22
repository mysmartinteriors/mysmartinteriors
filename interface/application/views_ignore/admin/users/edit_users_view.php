<?php 
$adminId="";
$firstName="";
$lastName="";
$login_id="";
$password="";
$email="";
$mobile="";
$temp_address="";
$perma_address="";
$roles_id="";
$is_sadmin="";
$is_restricted="";
$status=1;
// print_R($dataQ);exit();
// foreach ($dataQ as $admin){
	$adminId=$dataQ['id'];
	$firstName=$dataQ['first_name'];
	$lastName=$dataQ['last_name'];
	$login_id=$dataQ['login_id'];
	$password=$dataQ['password'];
	$email=$dataQ['email'];
	$mobile=$dataQ['mobile'];
	$roles_id=$dataQ['roles_id'];
	$is_sadmin=$dataQ['is_sadmin'];
	$temp_address=$dataQ['temp_address'];
	$perma_address=$dataQ['perma_address'];
	$is_restricted=$dataQ['data_restriction'];
	$status=$dataQ['status'];
// }

?>

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
                <h1 class="page-title">Edit User</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/adminusers">Users</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">                        
                        <div class="ibox">
                            <div class="ibox-head page-head-btns">
                                <div class="ibox-title">Edit user details</div>
                            </div>
                            <div class="ibox-body">   							
								<div class="p-20 form-holders">
                                     <form role="form" data-parsley-validate novalidate id="addedit_userform">
										<input name="id" value="<?php echo $adminId ?>" type="hidden">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="firstName" class="col-sm-4 form-control-label">First
														Name<span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<input name="firstName" class="form-control"
															value="<?php echo $firstName ?>" type="text">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="lastName" class="col-sm-4 form-control-label">Last
														Name<span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<input name="lastName" class="form-control"
															value="<?php echo $lastName ?>" type="text">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="roleId" class="col-sm-4 form-control-label">Select
														role<span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="custom-select" name="roles_id">
															<option value="">--select role--</option>
															<?php if(!empty($roles_data)){
																foreach ($roles_data as $r) {
																?>
																<option value="<?php echo $r['id']; ?>" <?php if ($r['id'] == $roles_id) { ?>selected<?php } ?>>
																	<?php echo $r['name']; ?>
																</option>
															<?php }
															} ?>
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="username"
														class="col-sm-4 form-control-label">Login Id<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<input name="login_id" class="form-control"
															value="<?php echo $login_id ?>" type="text" data-edit="<?php echo $login_id; ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="customeremail"
														class="col-sm-4 form-control-label">Email<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<input name="email" class="form-control"
															value="<?php echo $email ?>" type="email"
															data-edit="<?php echo $email; ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="customerphone"
														class="col-sm-4 form-control-label">Phone<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<input name="mobile" class="form-control"
															value="<?php echo $mobile ?>" type="number"
															data-edit="<?php echo $mobile; ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="hori-pass1"
														class="col-sm-4 form-control-label">Password<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<div class="Togglepwd">
															<input type="password" class="form-control" name="password"
																value="">
															<i class="fa fa-eye-slash pwd-input"></i>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="cpassword" class="col-sm-4 form-control-label">Retype
														Password<span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<div class="Togglepwd">
															<input type="password" class="form-control" name="cpassword"
																>
															<i class="fa fa-eye-slash pwd-input"></i>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="customerphone"
														class="col-sm-4 form-control-label">Permanent Address<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<textarea name="perma_address" class="form-control" id="" rows="2"><?php echo $perma_address ?></textarea>
														
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="customerphone"
														class="col-sm-4 form-control-label">Temporary Address</label>
													<div class="col-sm-7">
														<textarea name="temp_address" class="form-control" id="" rows="2"><?php echo $temp_address ?></textarea>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="is_sadmin" class="col-sm-4 form-control-label">Is Super Admin<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="custom-select" name="is_sadmin">
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<script>$('[name=is_sadmin]').val(<?php echo $is_sadmin ?>);</script>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="is_restricted" class="col-sm-4 form-control-label">Is Data Restricted<span
															class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="custom-select" name="is_restricted">
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<script>$('[name=is_restricted]').val(<?php echo $is_restricted ?>);</script>
													</div>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<div class="col-12 text-center mt-3">
												<button type="submit" class="btn btn-primary btn-air mr-2">Save</button>
												<button type="button" class="btn btn-secondary"
													onclick="window.location.href='<?php echo base_url() ?>admin/adminusers'">Cancel</button>
											</div>
										</div>
									</form>
                               </div>								
							</div>
						</div>
					</div> <!-- end row -->

             		
                </div>
            </div> <!-- content-->
				<?php echo $footer; ?>
        </div>
    </div>
    <?php echo $commonJs; ?>

	<script src="<?php echo base_url() ?>ui/assets/js/my-scripts/admin_users.js" type="text/javascript"></script>        
    </body>
</html>