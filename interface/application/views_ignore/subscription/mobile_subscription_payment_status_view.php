<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment <?php echo $status=='success'?'Successful':'Failed' ?></title>

    <style type="text/css">
        *,
        *:after,
        *:before {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Sans-Serif;
        }

        <?php if($status=='success'){ ?>

        .tick {
            display: inline-block;
            transform: rotate(45deg);
            height: 36px;
            width: 18px;
            border-bottom: solid 3px #1f9f0a;
            border-right: solid 3px #1f9f0a;
            margin-bottom: 8px;
        }

        .tick-container {
            padding: 20px;
            border-radius: 100px;
            height: 56px;
            width: 56px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            margin-bottom: 12px;
        }

        body {
            background: #015806;
            text-align: center;
            font-family: sans-serif;
        }
        <?php }else{ ?>

            .tick {
            display: inline-block;
            transform: rotate(45deg);
            height: 36px;
            width: 18px;
            border-right: solid 3px #fd1212;
            margin-bottom: 8px;
            position: absolute;
            position: absolute;
            left: 15px;
            top: 5px;
        }

        .tick2 {
            display: inline-block;
            transform: rotate(-45deg);
            height: 36px;
            width: 18px;
            border-right: solid 3px #fd1212;
            margin-bottom: 8px;
            position: absolute;
            left: 14px;
            top: 16px;
        }

        .tick-container {
            padding: 20px;
            border-radius: 100px;
            height: 56px;
            width: 56px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            margin-bottom: 12px;
            position: relative;
        }

        body {
            background: #db0c0c;
            text-align: center;
            font-family: sans-serif;
        }
        
        <?php } ?>

        .heading {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            flex-direction: column;
            margin-bottom: 32px;
        }
        .container {
            color: #fff;
        }
        .text-container {
            line-height: 1.8em;
        }
        .primary-button {
            color: #1da349;
            background-color: #fff;
            padding: 12px 16px;
            display: inline-block;
            margin-top: 32px;
            border-radius: 6px;
            text-decoration: none;
            text-transform: uppercase;
        }
        .payment_wrapper {
            min-height: 100svh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="payment_wrapper">
            <?php if($status=='success'){ ?>
            <div class="heading"><span class="tick-container"><i class="tick">&nbsp;</i></span><span>Your payment is
                    successful</span></div>
            <?php }else{ ?>
                <div class="heading"><span class="tick-container"><i class="tick">&nbsp;</i> <i
                        class="tick2">&nbsp;</i></span><span>Your payment is
                    unsuccessful</span></div>
            <?php } ?>
        
            <div class="text-container">
                <div><?php echo $message; ?></div>
                <div>You can close this window now. Thank You.</div>
            </div>
        </div>
    </div>
</body>

</html>