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
        <?php //echo $common_banner ?>
        <section class="sub-banner_section">
            <figure class="banner-backgroundimage mb-0">
                <img src="<?php echo base_url() ?>ui/assets/images/project-backgroundimage.jpg" alt=""
                    class="img-fluid">
            </figure>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12 col-12">
                        <div class="banner_content" data-aos="fade-up">
                            <h1>Projects</h1>
                            <p class="text-size-14 text-white mb-0">Duis aute irure dolor in reprehenderit in voluptate
                                velit esse cillum
                                dolore eu fugiat nulla pariat cepteur sint o ccaecat cupidatat.
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 col-md-4 col-sm-12 col-12 d-lg-block d-none">
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Content View Begining -->
    <!-- Projects -->
    <section class="projectpage-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="subheading">
                        <h2>LATEST PROJECTS</h2>
                    </div>
                </div>
            </div>
            <div class="tabs-box tabs-options">
                <ul class="nav nav-tabs">
                    <li><a class="active" data-toggle="tab" href="#all">All</a></li>
                    <li><a data-toggle="tab" href="#residential">Residential</a></li>
                    <li><a data-toggle="tab" href="#corporate">Corporate</a></li>
                    <li><a data-toggle="tab" href="#restaurant">Restaurant</a></li>
                    <li><a data-toggle="tab" href="#commercial">Commercial</a></li>
                </ul>
                <div class="tab-content">
                    <div id="all" class="tab-pane fade in active show">
                        <div class="row position-relative">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-md-0 pr-md-4">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 p-0 pr-sm-1 pb-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image1.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-1"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 p-0 pl-sm-1 pb-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image2.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-2"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0 tab-image3">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image3.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon3">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-3"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-0 pb-md-1">
                                <div class="image overlay">
                                    <figure class="tab-image tab-image4 mb-0">
                                        <img src="<?php echo base_url() ?>ui/assets/images/tab-image4.jpg"
                                            class="img-fluid" alt="">
                                    </figure>
                                    <div class="icon icon4">
                                        <a href="#" class="text-decoration-none" data-toggle="modal"
                                            data-target="#blog-model-4"><i class="fa-solid fa-house"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="middle-image">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image5.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon5">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-5"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 pl-sm-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image6.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon5">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-6"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pl-sm-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image7.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon5">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-7"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="last-image">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-0 pr-md-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image tab-image8 mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image8.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon3">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-8"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pr-sm-1 pl-md-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image tab-image9 mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image9.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-9"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pl-sm-1 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image tab-image9 mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image10.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-10"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="residential" class="tab-pane fade">
                        <div class="middle-image">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image5.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-5"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image6.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-6"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image7.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-7"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="corporate" class="tab-pane fade">
                        <div class="last-image">
                            <div class="row position-relative">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-0 pr-md-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image8 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image8.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon3">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-8"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pr-sm-1 pl-md-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image9 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image9.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-9"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image9 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image10.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-10"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="restaurant" class="tab-pane fade">
                        <div class="last-image">
                            <div class="row position-relative">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-0 pr-md-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image8 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image8.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon3">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-8"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pr-sm-1 pl-md-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image9 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image9.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-9"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 p-0 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image tab-image9 mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image10.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-10"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="middle-image">
                            <div class="row position-relative">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image5.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-5"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pr-sm-1 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image6.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-6"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12 p-0 pl-sm-1 py-md-1">
                                    <div class="image overlay">
                                        <figure class="tab-image mb-0">
                                            <img src="<?php echo base_url() ?>ui/assets/images/tab-image7.jpg"
                                                class="img-fluid" alt="">
                                        </figure>
                                        <div class="icon icon5">
                                            <a href="#" class="text-decoration-none" data-toggle="modal"
                                                data-target="#blog-model-7"><i class="fa-solid fa-house"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="commercial" class="tab-pane fade">
                        <div class="row position-relative">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-md-0 pr-md-4">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 p-0 pr-sm-1 pb-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image1.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-1"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 p-0 pl-sm-1 pb-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image2.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-2"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 py-md-1">
                                        <div class="image overlay">
                                            <figure class="tab-image mb-0 tab-image3">
                                                <img src="<?php echo base_url() ?>ui/assets/images/tab-image3.jpg"
                                                    class="img-fluid" alt="">
                                            </figure>
                                            <div class="icon icon3">
                                                <a href="#" class="text-decoration-none" data-toggle="modal"
                                                    data-target="#blog-model-3"><i class="fa-solid fa-house"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-0 pb-md-1">
                                <div class="image overlay">
                                    <figure class="tab-image tab-image4 mb-0">
                                        <img src="<?php echo base_url() ?>ui/assets/images/tab-image4.jpg"
                                            class="img-fluid" alt="">
                                    </figure>
                                    <div class="icon icon4">
                                        <a href="#" class="text-decoration-none" data-toggle="modal"
                                            data-target="#blog-model-4"><i class="fa-solid fa-house"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Content View Ending-->
    <!-- Footer -->
    <?php echo $common_footer ?>
    <!-- Javascript -->
    <?php echo $common_js ?>
</body>

</html>