<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Delivery Boy Login</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>uploads/logo/favicon.png">  

    <?php echo $commonCss ?>
    <style>
        body {
            background-repeat: no-repeat;
            background-size: cover;
            background-image: url('<?php echo base_url() ?>ui/images/login_bg.jpg');
        }

        .cover {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(117, 54, 230, .1);
        }

        .login-content {
            max-width: 400px;
            margin: 100px auto 50px;
        }

        .auth-head-icon {
            position: relative;
            height: 60px;
            width: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            background-color: #fff;
            color: #5c6bc0;
            box-shadow: 0 5px 20px #d6dee4;
            border-radius: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="cover"></div>
    <div class="ibox login-content">
        <div class="text-center">
            <span class="auth-head-icon"><i class="la la-user"></i></span>
        </div>
        <form class="ibox-body" id="login-form" action="javascript:;" method="POST">
            <div class="row">
                <div class="col-12">
                    <h4 class="font-strong text-center mb-5">DELIVERY BOY LOGIN</h4>
                    <div id="login_res"></div>
                </div>
                <div class="col-12 form-group mb-4">
                    <label for="">Mobile Number <span class="text-danger">*</span></label>
                    <input class="form-control form-control-line" type="text" name="uid" placeholder="Mobile Number" value="<?php if(isset($_COOKIE['user_remember_me'])) echo $this->input->cookie('user_remember_me',TRUE);  ?>">
                </div>
                <div class="col-12 form-group mb-4 Togglepwd">
                    <label for="">Password <span class="text-danger">*</span></label>
                    <input class="form-control form-control-line" type="password" name="pwd" placeholder="Password" value="<?php if(isset($_COOKIE['user_password']))echo $_COOKIE['user_password']; ?>">
                    <i class="fa fa-eye-slash pwd-input"></i>
                </div>
                <div class="col-12 flexbox mb-5">
                    <span>
                        <label class="ui-switch switch-icon mr-2 mb-0">
                            <input type="checkbox" name="remember" <?php if(isset($_COOKIE['user_remember_me'])) { echo 'checked="checked"'; } else { echo ''; } ?> >
                            <span></span>
                        </label>Remember</span>
                    <!--<a class="text-primary" href="<?php echo base_url()?>admin/login/forgot">Forgot password?</a>-->
                </div>

                <div class="col-12 text-center mb-4">
                    <button type="submit" class="btn btn-primary btn-rounded btn-block" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Logging in..." id="login-btn">LOGIN</button>
                </div>
            </div>
        </form>
    </div>

    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/delivery/js/login.js"></script>
</body>
</html>