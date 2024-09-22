<script src="https://accounts.google.com/gsi/client" async defer></script>


<div class="modal-wrapper">
    <div class="container">
        <div class="user_forms" id="div-forms">
            <div class="row" id="user_login_panel">
                <div class="col-md-5 content-sec login_modal_cont">
                <div class="login_overlay"></div>
                    <h2 class="title mb-2">Get access to your Account, Orders and Recommendations</h2>
                </div><!-- End .col-md-6 -->
                <div class="col-md-7 form-sec">
                    <h2 class="title mb-2"><i class="fa fa-sign-in"></i> Login</h2>
                    <form id="user_login_form" class="mb-1" method="post" role="form">
                        <div class="row">
                            <div class="form-group col-12">
                                <label class="col-form-label">Email address/Mobile Number <span
                                        class="required">*</span></label>
                                <input type="text" class="form-input form-wide" name="email" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group Togglepwd">
                                <label class="col-form-label">Password <span class="required">*</span></label>
                                <input type="password" class="form-input form-wide" name="password" />
                                <i class="fa fa-eye-slash pwd-input"></i>
                            </div>
                        </div>
                        <div class="form-footer row">
                            <button type="submit" class="col-12 btn btn-primary btn-md" id="user_login"
                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Logging in...">LOGIN</button>                                
                        </div>
                        <div class="row my-1">
                            <div class="col-12 d-flex justify-content-center">
                                <div id="g_id_onload" data-callback="<?php echo base_url(); ?>" data-login_uri="<?php echo base_url() ?>account/handleOAuth" data-client_id="346360535258-b0712geelkfomiiuc1l1ior3u5c357ng.apps.googleusercontent.com">
                                </div>
                                <div class="g_id_signin" data-button="button1" data-click_listener="onClickHandler" data-theme="filled_blue" data-size="large" data-text="signup_with" data-auto_prompt="true" data-use_fedcm_for_prompt="true" data-shape="pill" data-type="standard"></div>
                            </div>
                        </div>
                    </form>
                    <a href="javascript:void(0);" class="forget-password" id="forgot_pwd_btn"> Forgot your password?</a>
                    <div class="form-footer">
                        Not a member? <a href="javascript:void(0);" id="user_regstr_btn">&nbsp;Signup Now!</a>
                    </div>
                </div><!-- End .col-md-6 -->
            </div><!-- End .row -->
            <div class="row" id="user_pwd_panel">
                <div class="col-md-5 content-sec login_modal_cont">
                <div class="login_overlay"></div>

                    <h2 class="title mb-2">You can retrieve your account access by using your registered email address
                    </h2>
                </div><!-- End .col-md-6 -->
                <div class="col-md-7 form-sec">
                    <form id="forgotpwd_form">
                        <h2 class="title mb-2"><i class="fa fa-info-circle"></i> Lost your Password ?</h2>
                        <p>If you lost your password, enter your registered email address and you will receive an email
                            with the login instructions to access your account.</p>
                        <div class="form-group">
                            <label class="col-form-label">Email Address <span class="required">*</span></label>
                            <input type="email" class="form-input form-wide" name="email">
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary btn-md" id="forgot_pwd_submit"
                                data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Please wait...">Retrive
                                Access</button>
                        </div>
                    </form>
                    <div class="form-footer">
                        <a href="javascript:void(0);" id="lost_login_btn" class=""> Login Back <i
                                class="fa fa-sign-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="row" id="user_reg_panel">
                <div class="col-md-5 content-sec login_modal_cont">
                <div class="login_overlay"></div>

                    <h2 class="title mb-2">No worries, we will not share your personal details with anyone</h2>
                </div><!-- End .col-md-6 -->
                <div class="col-md-7 form-sec">
                    <form id="user_regstr_form">
                        <h2 class="title mb-2"><i class="fa fa-user-circle-o"></i> Signup</h2>
                        <span class="form_error text-danger"></span>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="col-form-label">First Name <span class="required">*</span></label>
                                <input type="text" class="col-12  form-input form-wide" name="firstName">
                            </div>
                            <div class="col-6 form-group">
                                <label class="col-form-label">Last Name</label>
                                <input type="text" class="col-12  form-input form-wide" name="lastName">
                            </div>
                            <div class="col-12 form-group">
                                <label class="col-form-label">Email address <span class="required">*</span></label>
                                <input type="email" class="col-12  form-input form-wide" name="email" data-edit="">
                            </div>
                            <div class="col-12 form-group"> 
                                <label class="col-form-label">Reference Code <span class="required">(Please enter if you have)</span></label>
                                <input type="text" class="col-12 form-input form-wide" name="referral_code" data-edit="">
                            </div>
                            <div class="col-6 form-group">
                                <label class="col-form-label">Mobile Number <span class="required">*</span></label>
                                <input type="phone" class="col-12  form-input form-wide" name="phone" data-edit="">
                            </div>
                            <div class="col-6 form-group Togglepwd">
                                <label class="col-form-label">Choose Password <span class="required">*</span></label>
                                <input type="password" class="form-input form-wide" name="password">
                                <i class="fa fa-eye-slash pwd-input"></i>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary btn-md" id="reg_submit" data-loading-text="<span class='btn-label'><i class='fa fa-spinner fa-spin '></i></span> Please wait...">Continue</button>
                        </div>
                    </form>
                    <div class="form-footer">
                        Existing User? <a href="javascript:void(0);" id="reg_login_btn" class="">&nbsp;Login Now <i
                                class="fa fa-sign-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End .container -->
</div>