<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nalaa Blogs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php echo $commonCss ?>

</head>

<body>
<?php echo $header_main ?>
    <!-- background-image: url('ui/assets/img/blog/blog_banner.jpg'); -->
<section class="blog_banner" style="background-image: url('<?php echo base_url() ?>ui/assets/img/blog/blog_banner.jpg')">
        <div class="bog_banner_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog_banner_txt">
                        <h3>Blogs</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 blog_left_sidebar">
                    <div class="blog_recipe_heading">
                        <h3> Popular Recipes</h3>
                    </div>
                    <div class="blogs_list_box">
                        <div class="blog_card" data-tab-target="#corn-salsa">
                            <div class="row align-items-center">
                                <div class="col-lg-5">
                                    <a href='#' class="blog_card_img">
                                        <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa.webp" alt="Corn Salsa"
                                            class="img-fluid">
                                    </a>
                                </div>
                                <div class="col-lg-7">
                                    <div class="blog_card_cont">
                                        <span class="badge rounded-pill text-bg-success"
                                            style="font-size: 9px;">Food</span>
                                        <p>Corn Salsa Recipe</p>
                                        <div class="blog_card_publish_wrap">
                                            <div class="blog_card_user ">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Arjun</span>
                                            </div>
                                            <div class="blog_publish_date">
                                                <span><i class="bi bi-calendar-check-fill"></i></span>
                                                <span>02-04-2024</i></span>
                                            </div>
                                        </div>
                                        <div class="blog_post_btn">
                                            <a href="#">Read more</a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="blog_card" data-tab-target="#zucchini-fritters">
                            <div class="row align-items-center">
                                <div class="col-lg-5">
                                    <a href='#' class="blog_card_img">
                                        <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini.png" alt="Zucchini Fritters"
                                            class="img-fluid">
                                    </a>

                                </div>

                                <div class="col-lg-7">
                                    <div class="blog_card_cont">
                                        <span class="badge rounded-pill text-bg-success"
                                            style="font-size: 9px;">Food</span>
                                        <p>Zucchini Fritters Recipe</p>
                                        <div class="blog_card_publish_wrap">
                                            <div class="blog_card_user ">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Vinod</span>
                                            </div>
                                            <div class="blog_publish_date">
                                                <span><i class="bi bi-calendar-check-fill"></i></span>
                                                <span>01-04-2024</i></span>
                                            </div>
                                        </div>
                                        <div class="blog_post_btn">
                                            <a href="#">Read more</a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="blog_card" data-tab-target="#bhindi-masala">
                            <div class="row align-items-center">
                                <div class="col-lg-5">
                                    <a href='#' class="blog_card_img">
                                        <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi-masala.png" alt="Bhindi Masala"
                                            class="img-fluid">
                                    </a>

                                </div>

                                <div class="col-lg-7">
                                    <div class="blog_card_cont">
                                        <span class="badge rounded-pill text-bg-success"
                                            style="font-size: 9px;">Food</span>
                                        <p>Bhindi Masala Recipe</p>
                                        <div class="blog_card_publish_wrap">
                                            <div class="blog_card_user ">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Vijay</span>
                                            </div>
                                            <div class="blog_publish_date">
                                                <span><i class="bi bi-calendar-check-fill"></i></span>
                                                <span>31-04-2024</i></span>
                                            </div>
                                        </div>
                                        <div class="blog_post_btn">
                                            <a href="#">Read more</a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="blog_card" data-tab-target="#gobi-masala">
                            <div class="row align-items-center">
                                <div class="col-lg-5">
                                    <a href='#' class="blog_card_img">
                                        <img src="<?php echo base_url()?>ui/assets/img/blog/gobi-masala.png" alt="Gobi Masala"
                                            class="img-fluid">
                                    </a>

                                </div>

                                <div class="col-lg-7">
                                    <div class="blog_card_cont">
                                        <span class="badge rounded-pill text-bg-success"
                                            style="font-size: 9px;">Food</span>
                                        <p>Gobi Masala Recipe</p>
                                        <div class="blog_card_publish_wrap">
                                            <div class="blog_card_user ">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Aamir</span>
                                            </div>
                                            <div class="blog_publish_date">
                                                <span><i class="bi bi-calendar-check-fill"></i></span>
                                                <span>30-03-2024</i></span>
                                            </div>
                                        </div>
                                        <div class="blog_post_btn">
                                            <a href="#">Read more</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="blog_card" data-tab-target="#green-juice">
                            <div class="row align-items-center">
                                <div class="col-lg-5">
                                    <a href='#' class="blog_card_img">
                                        <img src="<?php echo base_url()?>ui/assets/img/blog/green-juice.png" alt="Corn Salsa Recipe"
                                            class="img-fluid">
                                    </a>

                                </div>

                                <div class="col-lg-7">
                                    <div class="blog_card_cont">
                                        <span class="badge rounded-pill text-bg-success"
                                            style="font-size: 9px;">Food</span>
                                        <p>Green Juice</p>
                                        <div class="blog_card_publish_wrap">
                                            <div class="blog_card_user">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Rita</span>
                                            </div>
                                            <div class="blog_publish_date">
                                                <span><i class="bi bi-calendar-check-fill"></i></span>
                                                <span>29-04-2024</i></span>
                                            </div>
                                        </div>
                                        <div class="blog_post_btn">
                                            <a href="#">Read more</a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="blog_detail_sect tab-content">

                        <div id="corn-salsa" data-tab-content class="active">

                            <div class="blog_detail_heading">
                                <h2>Fresh and Flavorful Corn Salsa Recipe </h2>
                                <p>Looking for a vibrant and zesty addition to your next gathering or Taco Tuesday? Try
                                    this easy-to-make corn salsa bursting with color and flavor!
                                </p>
                            </div>

                            <div class="blog_share_box">

                                <div class="blog_card_publish_wrap">
                                    <div class="blog_card_user ">
                                        <i class="bi bi-person-fill" style="color: #05b505;"></i>
                                        <span>Arjun</span>
                                    </div>
                                    <div class="blog_publish_date">
                                        <span><i class="bi bi-calendar-check-fill" style="color: #05b505;"></i></span>
                                        <span>02-04-2024</i></span>
                                    </div>
                                </div>

                                <div class="blog_details_socials">

                                    <p>Share this post :</p>
                                    <div class="share_socials">
                                        <a href="https://www.facebook.com/profile.php?id=61557720083912"><i class="bi bi-facebook" style="color: #1877F2;"></i></a>
                                        <a href="https://x.com/nalaaorganic"><i class="bi bi-twitter-x" style="color: #657786 ;"></i></a>
                                        <a href="https://www.instagram.com/nalaaorganic"><i class="bi bi-instagram" style="color: #C13584;"></i></a>
                                    </div>
                                </div>


                            </div>

                            <div class="blog_detail_sect_img">
                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-1.png" alt="Corn Salsa Recipe"
                                    class="img-fluid">
                            </div>

                            <div class="blog_detail_cont">
                                <h3>How To Make Corn Salsa</h3>

                                <p>
                                    First, <b>cook the corn</b> if you are using fresh corn.
                                </p>


                                <ul class="cooking_methods">
                                    <li><span>Stovetop pressure cooking:</span> In a 3-litre pressure cooker place 1
                                        medium-to-large corn cob. Add just enough water to almost cover the ear of corn.
                                        Pressure cook on medium heat for 8 to 10 minutes. When the pressure falls on its
                                        own
                                        in the cooker, remove the lid and let the corn cob cool at room temperature.
                                    </li>

                                    <li><span>Instant pot cooking:</span> Take 1 cup water in the steel insert of a 6
                                        quart
                                        IP. Keep a
                                        trivet in the steel insert and place the corn cob on the trivet. Pressure cook
                                        on
                                        high for 3 to 5 minutes. Then do a quick release.
                                        If preferred, you can also simply steam the corn on the stovetop.

                                        To use frozen corn, cook according to package instructions. If you’re using
                                        cooked
                                        canned corn there’s no need to heat first.
                                    </li>
                                </ul>



                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-2.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <p> Allow the cooked corn to cool completely. Then, use a knife to carefully slice the
                                    kernels off of the corn cob. You want to wind up with at least <b>1 cup of corn</b>
                                    for
                                    this
                                    salsa recipe.
</p> <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-3.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Place the corn kernels in a medium bowl, and add ¼ cup of finely chopped green
                                    onions, 1
                                    teaspoon of chopped green chili or jalapeño or serrano pepper, and 2 tablespoons of
                                    chopped coriander (cilantro) leaves.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-4.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Next add the spices: ¼ teaspoon of cumin powder, ¼ teaspoon of red chili powder, ¼
                                    teaspoon of black pepper powder and salt to taste.

                                    Again, you can add more chili powder and also include a dash of cayenne and/or diced
                                    jalapeno for extra heat if you like
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-5.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Lastly, squeeze in some fresh lemon juice. The acid from the lemon adds a terrific
                                    bit
                                    of sour flavor that really brings all of the ingredients in this corn salsa
                                    together.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-6.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>
                            <div class="blog_detail_cont">
                                <p>
                                    Mix very well to thoroughly combine.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/corn-salsa-7.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <h3>How To Serve</h3>

                                <p>Serve corn salsa as a chunky dip with tortilla chips or crackers, or even fresh
                                    veggies
                                    that are thick enough for scooping – like slices of bell pepper.

                                    Enjoy freshly made salsa with some of your favorite dishes like nachos, Quesadillas,
                                    Vegetarian Tacos, and Refried Beans.</p>

                            </div>

                            <div class="blog_detail_cont">
                                <p>Variations</p>

                                <ul class="cooking_methods">
                                    <li><span>Grilled Corn:</span> Get a smoky flavor and taste to your corn salsa, by
                                        replacing steamed corn with fire-roasted grilled corn. You can also roast the
                                        corn
                                        in the oven.</li>
                                    <li><span>Onions:</span> Green onions (scallions) have a mild sweet taste and add a
                                        nice
                                        crunch to
                                        the salsa recipe. But if they are unavailable to you, replace them with red or
                                        white
                                        onions. Adding onions will give a more sharper flavor. Avoid onions that are
                                        highly
                                        pungent.</li>
                                    <li><span>Beans:</span> Add some cooked or canned kidney beans, black beans, or
                                        white
                                        beans to perk up the salsa and make it more healthier.</li>
                                    <li><span>Fats:</span> For a richer texture, add some butter or olive oil. You can
                                        also
                                        add some mashed avocado which will give a lovely creamy texture to the corn
                                        salsa.
                                    </li>
                                    <li><span>Herbs:</span> Aromatic fresh soft herbs like basil, parsley, and mint also
                                        taste great. If you do not have fresh herbs handy, then dried herbs make a good
                                        option as well.</li>
                                    <li><span>Tomatoes: </span>Fresh or canned tomatoes add a great tangy taste and some
                                        umami flavor to the salsa.</li>
                                </ul>

                            </div>

                        </div>

                        <!-- ========Zucchini-fritters========= -->
                        <div id="zucchini-fritters" data-tab-content>

                            <div class="blog_detail_heading">
                                <h2>Crispy Zucchini Fritters Recipe </h2>
                                <p>Elevate your appetizer game with these irresistible zucchini fritters! Golden brown
                                    and crispy on the outside, soft and flavorful on the inside, these fritters are a
                                    delightful way to enjoy seasonal zucchini.
                                </p>
                            </div>

                            <div class="blog_share_box">

                                <div class="blog_card_publish_wrap">
                                    <div class="blog_card_user ">
                                        <i class="bi bi-person-fill" style="color: #05b505;"></i>
                                        <span>Arjun</span>
                                    </div>
                                    <div class="blog_publish_date">
                                        <span><i class="bi bi-calendar-check-fill" style="color: #05b505;"></i></span>
                                        <span>02-04-2024</i></span>
                                    </div>
                                </div>

                                <div class="blog_details_socials">

                                    <p>Share this post :</p>
                                    <div class="share_socials">
                                        <a href="https://www.facebook.com/profile.php?id=61557720083912"><i class="bi bi-facebook" style="color: #1877F2;"></i></a>
                                        <a href="https://x.com/nalaaorganic"><i class="bi bi-twitter-x" style="color: #657786 ;"></i></a>
                                        <a href="https://www.instagram.com/nalaaorganic"><i class="bi bi-instagram" style="color: #C13584;"></i></a>
                                    </div>
                                </div>


                            </div>

                            <div class="blog_detail_sect_img">
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-1.png" alt="Corn Salsa Recipe"
                                    class="img-fluid">
                            </div>

                            <div class="blog_detail_cont">
                                <h3>How To Make Zucchini Fritters</h3>

                                <p>Finely chop 1 medium-sized onion, 2 to 3 inches small celery stalk, 2 to 3 medium
                                    garlic cloves, and a few parsley leaves.</p>

                                <p>You will need ⅓ cup finely chopped onions, ½ teaspoon finely chopped garlic, 2
                                    teaspoons finely chopped celery, and 2 tablespoons chopped parsley leaves.</p>



                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-2.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <p>
                                    Rinse 2 medium-sized zucchinis (300 grams) and then pat dry with a kitchen towel.
                                    Grate them using a handheld grater, box grater, or food processor.
                                </p>
                                <p>Do not grate them too finely. Use the larger holes on whichever grater you choose.
                                    You can peel the zucchini if you want to, but you don’t have to.</p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-3.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Put the grated zucchini in a mixing bowl. I directly grate the zucchini into a
                                    mixing bowl to cut back on the dishes.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-4.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Add the finely chopped onions, garlic and celery.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-5.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Add ½ heaped cup whole wheat flour and ¼ cup chickpea flour or gram flour.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-6.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>
                            <div class="blog_detail_cont">
                                <p>
                                    Season with ¼ to ½ teaspoon black pepper (crushed), ⅛ teaspoon black salt
                                    (optional), ½ teaspoon salt, or add it as required.Also, add ½ teaspoon baking
                                    powder.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-7.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <p>Mix very well. Depending on the water content in the zucchini which will vary with
                                    the variety and farming methods used, the batter can become too thick or thin.

                                    If the batter is very thick and floury, then sprinkle it with some water. If the
                                    batter becomes thin and runny, then add some wheat flour.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-8.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> In a 10 to 10.5 inches seasoned, heavy cast-iron skillet or pan, add 2 tablespoons
                                    olive oil. For frying each batch of 3 fritters add 2 to 3 tablespoons of oil.Let the
                                    oil become hot. Using a serving spoon or ¼ cup measuring cup begin to pour the
                                    batter on the pan.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-9.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Gently place the batter on the pan.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-10.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Then with the back of the spoon or cup, spread lightly. Do not spread thin as then
                                    the fritters break.

                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-11.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Begin to fry on medium-low to medium heat. I usually fry on medium-low heat so that
                                    the insides are cooked well.The cast iron skillet retains heat and this helps in
                                    crisping the fritters.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-12.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Depending on the size of the skillet, you can fry three to four or even more
                                    zucchini fritters at a time.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-13.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> When one side is crisp and golden, gently remove and flip. Fry the second side.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-14.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Fry the second side till golden and crisp. For even frying you can flip once or
                                    twice.

                                </p>

                                <img src=".<?php echo base_url()?>ui/assets/img/blog//zucchini-15.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Remove and place them on kitchen paper towels for a brief period just for the extra
                                    oil to be soaked up. Do not keep them for long as then they can stick to the paper
                                    towel.You can even use a strainer with a bowl underneath to drain the oil. Make all
                                    zucchini fritters this way.

                                </p>

                                <img src=".<?php echo base_url()?>ui/assets/img/blog//zucchini-16.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <h3> Serving Suggestions </h3>

                                <p>
                                    Serve Zucchini Fritters with a dipping sauce of your choice. You can even serve it
                                    with sour cream, yogurt, ketchup or creme fraiche.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/zucchini-16.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                        </div>

                        <!-- ============bhindi-masala============ -->
                        <div id="bhindi-masala" data-tab-content>

                            <div class="blog_detail_heading">
                                <h2>Flavorful Bhindi Masala Recipe </h2>
                                <p>Looking to add a burst of flavor to your dinner table? Try this delicious Bhindi
                                    Masala recipe! With its aromatic spices and tender okra, it's sure to become a
                                    family favorite.
                                </p>
                            </div>

                            <div class="blog_share_box">

                                <div class="blog_card_publish_wrap">
                                    <div class="blog_card_user ">
                                        <i class="bi bi-person-fill" style="color: #05b505;"></i>
                                        <span>Arjun</span>
                                    </div>
                                    <div class="blog_publish_date">
                                        <span><i class="bi bi-calendar-check-fill" style="color: #05b505;"></i></span>
                                        <span>02-04-2024</i></span>
                                    </div>
                                </div>

                                <div class="blog_details_socials">

                                    <p>Share this post :</p>
                                    <div class="share_socials">
                                        <a href="https://www.facebook.com/profile.php?id=61557720083912"><i class="bi bi-facebook" style="color: #1877F2;"></i></a>
                                        <a href="https://x.com/nalaaorganic"><i class="bi bi-twitter-x" style="color: #657786 ;"></i></a>
                                        <a href="https://www.instagram.com/nalaaorganic"><i class="bi bi-instagram" style="color: #C13584;"></i></a>
                                    </div>
                                </div>


                            </div>

                            <div class="blog_detail_sect_img">
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi0.png" alt="Corn Salsa Recipe"
                                    class="img-fluid">
                            </div>

                            <div class="blog_detail_cont">
                                <h3>How To Make Bhindi Masala</h3>

                                <p>
                                    Rinse 250 grams bhindi (okra) well in water using a colander or strainer. Spread
                                    them on a tray or plate and let them dry on their own under the fan.You can even
                                    wipe dry each okra pod with a clean kitchen towel.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi1.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <p>
                                    When they are completely dry, chop each bhindi in 1 or 2 inch pieces.
                                    Before chopping, make sure there is not even a single drop of water or any moisture
                                    on the okra pods.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi2.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    In a heavy kadai, pan or skillet, heat 2 tablespoons oil and add the chopped okra.
                                    You can use any neutral-tasting oil. I mostly use sunflower oil or peanut oil.


                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi3.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Mix chopped bhindi well with the oil.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi4.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Now, sauté bhindi, stirring often, on low to medium-low heat.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi5.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Sauté bhindi until tender and cooked. You should see a few blisters or golden spots
                                    on the okra. Remove the sautéed okra and keep aside.

                                    Taste the sautéed okra. The crunchiness should not be there. Instead, you should
                                    taste a nicely softened bhindi. This means that it is well cooked.

                                    Frying or sautéing okra in oil minimizes the sliminess and stickiness in this
                                    recipe.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi6.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">

                                <p>Remove the cooked bhindi in a plate and keep aside. Also chop onions, tomatoes, green
                                    chilies and keep aside. Crush ginger and garlic in mortar-pestle to get
                                    ginger-garlic paste.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi6.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>In the same kadai or pan, heat 1 tablespoon oil. Add 1 medium-sized chopped onion (⅓
                                    cup chopped onions).

                                    Note: For a richer taste, you can swap oil with ghee (clarified butter).</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi7.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>Mix and begin to sauté the onions on low to medium heat.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi8.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Sauté onions, stirring often, until translucent. The onions should be softened.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi9.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Then, add 1 teaspoon ginger-garlic paste and chopped green chilies.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi10.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Stir and sauté on low heat until the raw aroma of ginger-garlic goes away. This takes
                                    about a few seconds.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi11.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Next, add 2 medium sized chopped tomatoes (1 cup chopped tomatoes).
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi12.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Mix well and begin to sauté tomatoes on low to medium-low heat.

                                    If the tomato mixture becomes too dry and starts sticking to the pan, add a few
                                    splashes of water – about 3 to 4 tablespoons water. Mix well and continue to sauté,
                                    stirring often.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi13.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Sauté tomatoes till soft and mushy.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi14.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Now, add all of the ground spices listed below:
                                </p>

                                <ul>
                                    <li>½ teaspoon Kashmiri red chili powder or ½ teaspoon sweet paprika or ¼ teaspoon
                                        cayenne pepper.</li>
                                    <li>½ teaspoon turmeric powder (ground turmeric).</li>
                                    <li>½ teaspoon garam masala powder</li>
                                </ul>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi15.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Next, add the following ground spices:
                                </p>

                                <ul>
                                    <li>½ teaspoon fennel powder, optional.</li>
                                    <li>1 teaspoon coriander powder.</li>
                                    <li>½ teaspoon dried mango powder (amchur powder).</li>
                                </ul>

                                <p>If you do not have dried mango powder, add ½ teaspoon dried pomegranate seeds powder
                                    (anardana powder).

                                    You can add lemon juice, but I suggest to squeeze fresh lemon juice on top of the
                                    Bhindi Masala while eating it. Adding lemon juice directly to the dish can make it
                                    very tangy.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi16.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Mix the ground spices very well and sauté for a couple of seconds.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi17.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Add the sautéed bhindi.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi18.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Season with salt as per taste.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi19.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Mix well.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi20.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Now, add ½ teaspoon crushed dried fenugreek leaves (kasuri methi). If you do not have
                                    dried fenugreek leaves, then skip it.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi21.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Stir the mixture well. Cook for about 2 minutes, stirring in between. Check the taste
                                    of Bhindi Masala and add more of the ground spice powders and salt, if required.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi22.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p>Lastly, add 2 tablespoons chopped coriander leaves. Mix again. You can also add 1
                                    tablespoon chopped mint leaves, if you do not have coriander leaves.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi23.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>

                            <div class="blog_detail_cont">
                                <p> Serve Bhindi Masala hot or warm.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/bhindi24.png" alt=""
                                    class="img-fluid cooking_method_img">
                            </div>
                            s
                            <div class="blog_detail_cont">
                                <h3> Serving Suggestions.</h3>
                                <p>Like all other dry okra recipes, you can savor this Bhindi Masala too with some soft
                                    phulka, roti, or chapati. You can also enjoy it with naan, paratha or rumali roti. A
                                    side of cucumber raita or veg raita also pairs really well with these combinations.
                                </p>
                                <p>Make the Bhindi Masala Recipe and also try it as a side dish with some dal-rice or
                                    any rice-curry combination. You can also serve it as a side with any North Indian
                                    meal.</p>
                                <p>It can also be packed in the tiffin or lunch box with roti or paratha.</p>
                            </div>



                        </div>


                        <!-- =============gobi masala============= -->

                        <div id="gobi-masala" data-tab-content>

                            <div class="blog_detail_heading">
                                <h2>Spicy Gobi Masala Recipe </h2>
                                <p>Looking for a tasty way to incorporate cauliflower into your meals? Try this
                                    irresistible Gobi Masala recipe! Bursting with flavor and spice, it's sure to become
                                    a favorite in your household.
                                </p>
                            </div>

                            <div class="blog_share_box">

                                <div class="blog_card_publish_wrap">
                                    <div class="blog_card_user ">
                                        <i class="bi bi-person-fill" style="color: #05b505;"></i>
                                        <span>Arjun</span>
                                    </div>
                                    <div class="blog_publish_date">
                                        <span><i class="bi bi-calendar-check-fill" style="color: #05b505;"></i></span>
                                        <span>02-04-2024</i></span>
                                    </div>
                                </div>

                                <div class="blog_details_socials">

                                    <p>Share this post :</p>
                                    <div class="share_socials">
                                        <a href="https://www.facebook.com/profile.php?id=61557720083912"><i class="bi bi-facebook" style="color: #1877F2;"></i></a>
                                        <a href="https://x.com/nalaaorganic"><i class="bi bi-twitter-x" style="color: #657786 ;"></i></a>
                                        <a href="https://www.instagram.com/nalaaorganic"><i class="bi bi-instagram" style="color: #C13584;"></i></a>
                                    </div>
                                </div>


                            </div>

                            <div class="blog_detail_sect_img">
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi0.png" alt="Corn Salsa Recipe"
                                    class="img-fluid">
                            </div>

                            <div class="blog_detail_cont">
                                <h3>How To Make Gobi Masala</h3>

                                <p>
                                    Chop 1 medium size cauliflower (gobi) into medium florets. Rinse well and keep
                                    aside. Heat 3 cups water with salt till it starts boiling.
                                </p>

                                <p>Switch off the heat and add the florets to the hot water. Cover and keep aside for 15
                                    to 20 minutes.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi1.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">
                                <p>
                                    In the meantime, chop 1 medium size onion and crush the ginger-garlic (3 to 4 garlic
                                    cloves and ½ inch ginger).
                                </p>

                                <p>Also, grind the tomato and cashews (2 medium size tomatoes and 1 tablespoon whole
                                    cashews) to a smooth and fine paste on high speed in a blender with very little
                                    water.</p>

                                <p>If there is too much water, then the mixture starts spluttering when sautéing it.
                                    Keep aside.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi2.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Drain the cauliflower florets and gently wipe them dry. Heat 3 tablespoons oil in a
                                    kadai or wok. You can use any neutral-flavored oil.
                                </p>

                                <p>Add the blanched cauliflower florets to the hot oil and sauté on low to medium heat
                                    till they start getting light brown spots on them. Approximately 8 to 10 minutes on
                                    low to medium heat.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi3.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    Then, remove the sautéed cauliflower florets on a plate and keep them aside.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi4.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>
                                    In the same pan or kadai, add 2 tablespoons of oil. Add 1 tej patta and ½ teaspoon
                                    of caraway seeds (shahi jeera). Fry for a few seconds on low heat or till the oil
                                    becomes aromatic.
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi5.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>
                            <div class="blog_detail_cont">
                                <p>
                                    Add 1 medium size finely chopped onions (about 1/3 cup).
                                </p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi6.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>


                            <div class="blog_detail_cont">

                                <p>Sauté stirring often on low to medium heat till the onions turn golden and are
                                    caramelized. Make sure the onions don’t get burnt, as then the curry will have a
                                    bitter taste.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi7.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>Then, add 1 teaspoon ginger-garlic paste and sauté on low heat for a few seconds till
                                    the raw aroma of ginger-garlic goes away.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi8.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>Add the prepared tomato-cashew paste and stir to mix to get an even mixture.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi9.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p>Add the following spice powders:</p>

                                <ul>
                                    <li>1 teaspoon coriander powder</li>
                                    <li>½ teaspoon cumin powder</li>
                                    <li>½ teaspoon red chili powder</li>
                                    <li>½ teaspoon garam masala powder</li>
                                    <li>¼ teaspoon turmeric powder</li>
                                </ul>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi10.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Sauté, stirring often, till the oil starts to leave the sides of the masala. The
                                    whole masala paste will clump together and you will clearly see the oil leaving the
                                    sides. </p>
                                <p>This is an important step as if not done properly, the flavors don’t come through in
                                    the dish. Takes approximately 12 to 14 minutes on low heat.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi11.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Switch off the heat and add 3 to 4 tablespoons full fat whisked yogurt (curd). Make
                                    sure the curd you use is fresh and not sour. </p>


                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi12.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Immediately stir and mix very well. Then add 1 to 1.5 cups water. Add water as
                                    required. </p>
                                <p>Here I get cauliflower which takes more time to cook. Thus, I added more water. If
                                    the cauliflower takes less time to cook, add less water.</p>
                                <p>If you have sauteed the cauliflower more than what is mentioned in the above steps,
                                    then also you will have to add less water as the cauliflower will be already cooked.
                                </p>
                                <p>So, just add enough water as needed to get a medium consistency gravy, then add the
                                    cauliflower and simmer for a minute.</p>


                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi13.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Stir again and keep the kadai or pan on the stovetop again. Now, add the sautéed
                                    cauliflower florets and salt as required. </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi14.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Stir and cover the kadai or pan with a tight lid. In the photo below I have not
                                    covered the pan with the lid as I wanted to show the consistency of the curry.
                                </p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi15.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Cook Gobi Masala on low to medium heat for about 10 to 15 minutes or till the
                                    florets are cooked completely and are fork tender. But don’t make them mushy.
                                </p>

                                <p>The cooking time for the cauliflower will depend upon its quality.</p>
                                <p>Keep checking at intervals. If the water dries up, then you can always add some more
                                    water.</p>

                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi16.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Lastly, add ½ teaspoon crushed dried fenugreek leaves (kasuri methi), 2 tablespoons
                                    low fat cream or 1 tablespoon heavy cream and a pinch of nutmeg powder. Stir the
                                    curry.
                                </p>
                                <p>If you don’t have kasuri methi then skip adding them.</p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi17.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <p> Serve Gobi Masala hot or warm with phulka or paratha or tandoori roti or naan or
                                    rumali roti . In the picture below, I have served the Cauliflower Masala with
                                    paratha and a vegetable raita.
                                </p>
                                <p>It can also be paired with jeera rice, ghee rice or plain steamed basmati rice.</p>
                                <img src="<?php echo base_url()?>ui/assets/img/blog/gobi18.png" alt=""
                                    class="img-fluid cooking_method_img">

                            </div>

                            <div class="blog_detail_cont">
                                <h3>Expert Tips </h3>

                                <p>Blanch the cauliflower in hot water before using it in this recipe. Helps to get rid
                                    of the hidden worms or insects in it, if any.</p>
                                <p>It is best to use full fat fresh yogurt (curd) or yogurt made with whole milk in this
                                    recipe, or else it will end up curdled while getting cooked. You can also skip the
                                    yogurt, if you want. The curd should not be very sour.</p>
                                <p>While sautéing the onions, ensure to not burn them as then you will end up with a
                                    bitter tasting curry.</p>
                                <p>You have to sauté the masala very well, till you see oil leaving the sides. A good 12
                                    to 15 minutes on low heat. If you don’t do this properly, then the flavors will not
                                    come through in this preparation.</p>
                                <p>Add water accordingly to cook the cauliflower. If it takes less time to cook, then
                                    add less water. If you have sauteed the gobi more than what is mentioned in the
                                    recipe, then also add less water as it will be already cooked.</p>
                                <p>If you don’t have the nutmeg powder, then skip adding it. But according to me, kasuri
                                    methi and cream are essentials in this gravy for that specific restaurant feel in
                                    the dish.</p>
                                <p>Use fresh and firm cauliflower as it is the star ingredient in the recipe.</p>

                            </div>

                        </div>



                        <!-- ================Green juice============== -->
                        <div id="green-juice" data-tab-content>

                            <div class="blog_detail_heading">
                                <h2>Refreshing Green Juice Recipe</h2>
                                <p>Looking to boost your day with a burst of freshness and nutrients? Try this
                                    invigorating Green Juice recipe! Packed with vibrant green vegetables and fruits,
                                    it's the perfect way to kickstart your morning or rejuvenate your energy levels any
                                    time of day.
                                </p>
                            </div>

                            <div class="blog_share_box">

                                <div class="blog_card_publish_wrap">
                                    <div class="blog_card_user ">
                                        <i class="bi bi-person-fill" style="color: #05b505;"></i>
                                        <span>Arjun</span>
                                    </div>
                                    <div class="blog_publish_date">
                                        <span><i class="bi bi-calendar-check-fill" style="color: #05b505;"></i></span>
                                        <span>02-04-2024</i></span>
                                    </div>
                                </div>

                                <div class="blog_details_socials">
                                    <p>Share this post :</p>
                                    <div class="share_socials">
                                        <a href="https://www.facebook.com/profile.php?id=61557720083912"><i class="bi bi-facebook" style="color: #1877F2;"></i></a>
                                        <a href="https://x.com/nalaaorganic"><i class="bi bi-twitter-x" style="color: #657786 ;"></i></a>
                                        <a href="https://www.instagram.com/nalaaorganic"><i class="bi bi-instagram" style="color: #C13584;"></i></a>
                                    </div>
                                </div>


                            </div>

                            <div class="blog_detail_sect_img">
                                <img src="<?php echo base_url()?>ui/assets/img/blog/green-juice.png" alt="Green Juice"
                                    class="img-fluid">
                            </div>

                            <div class="blog_detail_cont">
                                <h3>How To Make Green Juice</h3>

                                <p>
                                    Ingredients in green juice
                                </p>


                                <ul class="cooking_methods">
                                    <li><span>Spinach:</span> We like using baby spinach; you could also use baby kale
                                        or chopped kale.
                                    </li>

                                    <li><span>Celery:</span> Celery adds a nuance in flavor.</li>
                                    <li><span>Cucumber:</span> Cucumber gives hydrating, herbaceous notes to the
                                        flavor.</li>
                                    <li><span>Apples:</span> Apples round out the flavor, helping to offset any
                                        bitterness from the greens.</li>
                                    <li><span>Ginger:</span> Griner is optional, but it adds a nice spicy nuance.</li>
                                    <li><span>Lemon juice:</span> Citrus adds a brightness to the finish of each sip.
                                    </li>
                                </ul>


                            </div>


                            <div class="blog_detail_cont">
                                <h3>
                                    How to make green juice (in a blender)
                                </h3>

                                <p>You can make this green juice in a blender: it’s easy to make and comes together
                                    quickly. Here are the main steps to the blender version:
                                </p>

                                <ul>
                                    <li>Blend all ingredients on high until pureed and smooth. It will look like a thick
                                        smoothie at this point. You’ll use a little water to help it blend.</li>
                                    <li>Add ice and blend again! Add just 1 cup ice, and it will cool the green juice so
                                        you can drink it right away. This is a fun feature because most juices you’ll
                                        have to wait a few hours for it to chill.</li>
                                    <li>Strain! This is the important part. Strain the juice through a medium or fine
                                        mesh strainer or using a nut milk bag. Using a medium mesh strainer results in
                                        juice with more pulp, which will give you more fiber. A fine mesh sieve makes it
                                        perfectly clear.</li>
                                </ul>

                            </div>

                            <div class="blog_detail_cont">
                                <h3>
                                    How to make Green Juice in a Blender
                                </h3>

                                <p>You can make this green juice in a blender: it’s easy to make and comes together
                                    quickly. Here are the main steps to the blender version:
                                </p>

                                <ul>
                                    <li>Blend all ingredients on high until pureed and smooth. It will look like a thick
                                        smoothie at this point. You’ll use a little water to help it blend.</li>
                                    <li>Add ice and blend again! Add just 1 cup ice, and it will cool the green juice so
                                        you can drink it right away. This is a fun feature because most juices you’ll
                                        have to wait a few hours for it to chill.</li>
                                    <li>Strain! This is the important part. Strain the juice through a medium or fine
                                        mesh strainer or using a nut milk bag. Using a medium mesh strainer results in
                                        juice with more pulp, which will give you more fiber. A fine mesh sieve makes it
                                        perfectly clear.</li>
                                </ul>

                            </div>

                            <div class="blog_detail_cont">
                                <h3>
                                    How to make Green Juice in a Juicer
                                </h3>

                                <p>You can also make this green juice recipe with a standard juicer! Simply pass all
                                    the produce through the juicer, and omit the ice and water in the recipe below. Then
                                    chill the juice until it’s fully cold before serving.
                                </p>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>

  <!-- End .main -->
  <?php echo $footer ?>
         <!-- End .footer -->
     
      <!-- End .page-wrapper -->

      <?php echo $mobile_menu ?>
      <!-- End .mobile-menu-container -->
      
      <?php echo $commonJs ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabsContent = document.querySelectorAll('[data-tab-content]');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = document.querySelector(tab.dataset.tabTarget);
                tabsContent.forEach((tabsContent) => {
                    tabsContent.classList.remove('active');
                })
                target.classList.add('active');
            })
        })
    </script>
</body>

</html>