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
                <img src="<?php echo base_url() ?>ui/assets/images/contact-backgroundimage.jpg" alt=""
                    class="img-fluid">
            </figure>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12 col-12">
                        <div class="banner_content" data-aos="fade-up">
                            <h1>Contact Us</h1>
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
    <!-- Contact -->
    <section class="contactinfo-section position-relative">
        <div class="container-fluid p-0">
            <!-- Contact map -->
            <div class="contact_map_section position-relative">
                <div class="row">
                    <div class="col-12">
                        <iframe
                            src="https://maps.google.com/maps?q=21KingStreetMelbourne,3000,Australia&amp;t=&amp;z=10&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
                            width="1920" height="556" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="info_content">
                <div class="row">
                    <div class="col-lg-7 col-md-8 col-sm-12 col-12 order-md-1 order-2">
                        <div class="badge c-black" id="badge2">
                            <span class="badge__char"> </span>
                            <span class="badge__char">B</span>
                            <span class="badge__char">E</span>
                            <span class="badge__char">A</span>
                            <span class="badge__char">U</span>
                            <span class="badge__char">T</span>
                            <span class="badge__char">Y</span>
                            <span class="badge__char"> </span>
                            <span class="badge__char">I</span>
                            <span class="badge__char">N</span>
                            <span class="badge__char"> </span>
                            <span class="badge__char">S</span>
                            <span class="badge__char">I</span>
                            <span class="badge__char">M</span>
                            <span class="badge__char">P</span>
                            <span class="badge__char">L</span>
                            <span class="badge__char">I</span>
                            <span class="badge__char">C</span>
                            <span class="badge__char">I</span>
                            <span class="badge__char">T</span>
                            <span class="badge__char">Y</span>
                            <span class="mid-circle"></span>
                        </div>
                        <figure class="contactpage-rightborder mb-0">
                            <img src="<?php echo base_url() ?>ui/assets/images/servicepage-imagerightborder.png" alt=""
                                class="img-fluid">
                        </figure>
                        <div class="content-box">
                            <form id="contactpage" method="POST"
                                action="https://html.designingmedia.com/hillcrest/contact-form.php">
                                <div class="form-group">
                                    <input type="text" class="form_style" placeholder="Name:" name="name">
                                </div>
                                <div class="form-group mr-0">
                                    <input type="email" class="form_style" placeholder="E-mail:" name="emailid">
                                </div>
                                <div class="form-group form3">
                                    <input type="tel" class="form_style" placeholder="Phone:" name="phone">
                                </div>
                                <div class="form-group mr-0 message">
                                    <textarea class="form_style" placeholder="Message:" rows="3" name="msg"></textarea>
                                </div>
                                <div class="button">
                                    <button type="submit" class="submit">Submit
                                        <i class="dot fa-solid fa-circle-small"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-12 col-12 order-md-2 order-1">
                        <div class="contactpage_content" data-aos="fade-left">
                            <h2>Get In Touch</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact -->
    <section class="contactpage-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="box box1">
                        <div class="icon">
                            <figure class="mb-0 contact-addressicon">
                                <img src="<?php echo base_url() ?>ui/assets/images/contact-addressicon.png" alt=""
                                    class="img-fluid">
                            </figure>
                        </div>
                        <h5>Address</h5>
                        <p class="mb-0 text-size-16">21 King Street Melbourne, 3000, Australia</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="box">
                        <div class="icon">
                            <figure class="mb-0 contact-phoneicon">
                                <img src="<?php echo base_url() ?>ui/assets/images/contact-phoneicon.png" alt=""
                                    class="img-fluid">
                            </figure>
                        </div>
                        <h5>Phone</h5>
                        <a href="tel:+61383766284" class="mb-0 text-decoration-none text-size-16">+613 8376 6284</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="box">
                        <div class="icon">
                            <figure class="mb-0 contact-emailicon">
                                <img src="<?php echo base_url() ?>ui/assets/images/contact-emailicon.png" alt=""
                                    class="img-fluid">
                            </figure>
                        </div>
                        <h5>Email</h5>
                        <a href="mailto:Info@hillcrestinteriors.com"
                            class="mb-0 text-decoration-none text-size-16">Info@hillcrestinteriors.com</a>
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