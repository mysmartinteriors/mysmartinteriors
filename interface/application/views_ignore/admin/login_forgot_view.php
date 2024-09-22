<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Fogot Admin Login</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <?php echo $commonCss ?>
    <style>
        body {
            background-repeat: no-repeat;
            background-size: cover;
            background-image: url(<?php echo base_url().'ui/images/login_bg.jpg'?>);
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
        <form class="ibox-body" id="aforgot-form" action="javascript:;" method="POST">
            <div class="row">
                <div class="col-12">
                    <h4 class="font-strong text-center mb-3">Forgot Password?</h4>
                    <p class="text-center mb-4">Do not worry, we will help you to get access to your account. Please enter your username and registred email address, then you will receive you an email instructions.</p>
                    <div id="login_res"></div>
                </div>
                <div class="col-12 form-group mb-4">
                    <input class="form-control form-control-line" type="text" name="uid" placeholder="Your username" value="">
                </div>
                <div class="col-12 form-group mb-4">
                    <input class="form-control form-control-line" type="email" name="email" placeholder="Your email address" value="">
                </div>
                <div class="col-12 mb-2">
                    <a class="text-primary pull-right" href="<?php echo base_url()?>admin/login">Login again?</a>
                </div>

                <div class="col-12 text-center mb-4">
                    <button type="submit" class="btn btn-primary btn-rounded btn-block" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing request..." id="login-btn">Retrieve Password</button>
                </div>
            </div>
        </form>
    </div>

    <?php echo $commonJs ?>
    <script src="<?php echo base_url(); ?>ui/assets/js/my-scripts/login.js"></script>
</body>
</html>