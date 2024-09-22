<!DOCTYPE html>
<html lang="zxx">

<head>
    <title><?php echo $title; ?></title>
    <!-- /SEO Ultimate -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta charset="utf-8">
    <!-- CSS/Assets -->
    <?php echo $common_css ?>
</head>

<body>
    <!-- Header  -->
    <div class="banner_outer">
        <!-- Nav -->
        <?php echo $common_header ?>
        <!-- Banner -->
        <?php echo $common_banner ?>
    </div>
    <!-- Content View -->
    <?php echo $content_view; ?>
    <!-- Footer -->
    <?php echo $common_footer ?>
    <!-- Javascript -->
    <?php echo $common_js ?>
</body>

</html>