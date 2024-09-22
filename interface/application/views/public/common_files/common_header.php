<figure class="banner-background mb-0">
    <img src="<?php echo base_url() ?>ui/assets/images/banner-background.jpg" alt="" class="img-fluid">
</figure>
<header class="header">
    <div class="main-header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.html">
                    <figure class="mb-0 banner-logo"><img src="<?php echo base_url() ?>ui/assets/images/logo.png" alt=""
                            class="img-fluid" style="width: 135px"></figure>
                </a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item <?php echo $url == 'home' ? 'active' : '' ?>">
                            <a class="nav-link" href="<?php echo base_url() ?>">Home</a>
                        </li>
                        <li class="nav-item <?php echo $url == 'about_us' ? 'active' : '' ?>">
                            <a class="nav-link" href="<?php echo base_url() ?>about_us">About Us</a>
                        </li>
                        <li class="nav-item <?php echo $url == 'services' ? 'active' : '' ?>">
                            <a class="nav-link" href="<?php echo base_url() ?>services">Services</a>
                        </li>
                        <li class="nav-item <?php echo $url == 'projects' ? 'active' : '' ?>">
                            <a class="nav-link" href="<?php echo base_url() ?>projects">Projects</a>
                        </li>
                        <!-- <li class="nav-space nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-color navbar-text-color" href="#"
                                id="navbarDropdownMenu2" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"> Blogs </a>
                            <div class="dropdown-menu drop-down-content">
                                <ul class="list-unstyled drop-down-pages">
                                    <li class="nav-item"><a class="dropdown-item nav-link" href="blog.html">Blog</a>
                                    </li>
                                    <li class="nav-item"><a class="dropdown-item nav-link"
                                            href="single-blog.html">Single Blog</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link"
                                            href="infinite-scroll.html">Infinite Scroll</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link" href="load-more.html">Load
                                            More</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link" href="one-column.html">One
                                            Column</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link" href="two-column.html">Two
                                            Column</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link"
                                            href="three-column.html">Three Column</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link"
                                            href="three-colum-sidbar.html">Three Column Sidebar</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link" href="four-column.html">Four
                                            Column</a></li>
                                    <li class="nav-item"><a class="dropdown-item nav-link"
                                            href="six-colum-full-wide.html">Six Column</a></li>
                                </ul>
                            </div>
                        </li> -->
                        <li class="nav-item <?php echo $url == 'contact_us' ? 'active' : '' ?>">
                            <a class="nav-link" href="<?php echo base_url() ?>contact_us">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link get_a_quote" href="javascript:void(0)">Get a Quote
                                <i class="dot fa-regular fa-circle-small"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
    $(document).ready(function () {
        ajax_modal('.get_a_quote', 'contact_us/get_a_quote', 'Submit Enquiry', 'medium', save_quote);
    })
    // function ajax_modal(buttonElement, url, mod_title,modal_size,submit_func) {

    function save_quote() {
        var formName = '#myForm';
        var ajax_type = 'button';
        var ajax_text = 'Processing...';
        var url = data_module + '/save_data';
        $(formName).validate({
            errorClass: 'error',
            validClass: 'valid',
            rules: {},
            messages: {},
            submitHandler: function () {
                ajax_request(formName, url, ajax_type, ajax_text, render_profile_save);
            }
        });
    }

</script>