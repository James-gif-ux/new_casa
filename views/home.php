<?php include 'nav/header.php'; ?>
<?php
    require_once '../model/server.php';
    include_once '../model/reservationModel.php';
    include_once '../model/Booking_Model.php';
    $model = new Booking_Model();
    $services = $model->get_service(); 
    $model = new Reservation_Model();
    $reservationModel = new Reservation_Model();
    $connector = new Connector(); // Initialize connector before using it
    $sql = "SELECT * FROM image_tb";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute();
    $breakfast = $stmt->fetchAll(PDO::FETCH_ASSOC);

  

   

    // Include the Connector class
    require_once '../model/server.php';
    $connector = new Connector();

    // Fetch all bookings that are pending approval
    $sql = "SELECT reservation_id, name, email, phone, checkin, checkout, message FROM reservations WHERE status = 'pending'";
    $reservations = $connector->executeQuery($sql);



    ?>
<div id="viewport">
        <div id="js-scroll-content">
            <section class="main-banner" id="home">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="banner-text">
                                    <h1 class="h1-title" style="font-size: 5.5rem; line-height: 1.2;">
                                        Welcome to Casa
                                        <span style="font-size: 4.8rem;">Marcos</span> resort & villas.
                                    </h1>
                                    <p>your luxurious escape in a beautiful setting. 
                                       Our dedicated team ensures an exceptional stay with options to relax, enjoy the spa, explore, or dine.
                                       We look forward to exceeding your expectations!
                                    </p>
                                    <div class="banner-btn mt-4">
                                        <a href="roombooking.php" class="sec-btn">Reserve Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="banner-img-wp">
                                    <div class="banner-img" style="background-image: url(../assets/images/offers.jpg);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="about-sec section" id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sec-title text-center mb-5">
                                <p class="sec-sub-title mb-3">About Us</p>
                                <h2 class="h2-title">Discover our <span>resort & villas story</span></h2>
                                <div class="sec-title-shape mb-4">
                                    <img src="assets/images/title-shape.svg" alt="">
                                </div>
                                <p> At Casa Marcos, we believe in creating extraordinary experiences through exceptional care and attention to detail. 
                                    Our dedicated team works tirelessly to ensure every guest feels welcomed and valued, combining professional 
                                    service with genuine Filipino warmth.We pride ourselves on understanding and anticipating our guests' needs, offering personalized services that 
                                    make every stay memorable. Our commitment to excellence extends beyond luxury amenities to create meaningful 
                                    connections and experiences that will last a lifetime
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 m-auto">
                            <div class="about-video">
                                <div class="about-video-img" style="background-image: url(../assets/images/abouts.jpg);">
                                </div>
                                <div class="play-btn-wp">
                                    <a href="../images/casa.mp4" data-fancybox="video" class="play-btn">
                                        <i class="uil uil-play"></i>

                                    </a>
                                    <span>Casa Marcos View</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section style="background-image: url(../assets/images/menu-bg.png);"
                class="our-menu section bg-light repeat-img" id="menu">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">our menu</p>
                                    <h2 class="h2-title">wake up early, <span>eat fresh & healthy</span></h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-tab-wp">
                            <div class="row">
                                <div class="col-lg-12 m-auto">
                                    <div class="menu-tab text-center">
                                        <ul class="filters">
                                            <div class="filter-active"></div>
                                            <li class="filter" data-filter=".all, .breakfast, .lunch, .dinner">
                                                <img src="../assets/images/menu-1.png" alt="">
                                                All
                                            </li>
                                            <li class="filter" data-filter=".breakfast">
                                                <img src="../assets/images/menu-2.png" alt="">
                                                Breakfast
                                            </li>
                                            <li class="filter" data-filter=".lunch">
                                                <img src="../assets/images/menu-3.png" alt="">
                                                Lunch
                                            </li>
                                            <li class="filter" data-filter=".dinner">
                                                <img src="../assets/images/menu-4.png" alt="">
                                                Dinner
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-list-row">
                            <div class="row g-xxl-5 bydefault_show" id="menu-dish">
                                <!-- 0 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp breakfast" data-cat="breakfast">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 0):
                                                $menu = $breakfast[0]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                        <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- 1 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp breakfast" data-cat="breakfast">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 1):
                                                $menu = $breakfast[1]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                      <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- 2 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp lunch" data-cat="lunch">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 2):
                                                $menu = $breakfast[2]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                      <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- 3 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp lunch" data-cat="lunch">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 3):
                                                $menu = $breakfast[3]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                       <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- 4 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp dinner" data-cat="dinner">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 4):
                                                $menu = $breakfast[4]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                       <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- 5 -->
                                <div class="col-lg-4 col-sm-6 dish-box-wp dinner" data-cat="dinner">
                                    <div class="dish-box text-center">
                                        <?php 
                                            // Only show the first menu item
                                            if($breakfast && count($breakfast) > 5):
                                                $menu = $breakfast[5]; // Get first item
                                        ?>
                                            <div class="dist-img">
                                                <img src="../images/<?php echo $menu['image_img']?>" alt="">
                                            </div>
                                            <div class="dish-title">
                                                <h3 class="h3-title"><?php echo $menu['image_name']?></h3>
                                            </div> 
                                            <hr>
                                            <div class="dist-bottom-row">
                                                <ul>
                                                    <li>
                                                       <b>₱<?php echo number_format($menu['image_price'] , 2)?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="book-table section bg-light">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">Book Now</p>
                                    <h2 class="h2-title">Enjoy your Stay!</h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                            </div>
                        </div>

                        <div class="row" id="gallery">
                            <div class="col-lg-10 m-auto">
                                <div class="book-table-img-slider" id="icon">
                                    <div class="swiper-wrapper">
                                        <style>
                                            .room-info {
                                                opacity: 0;
                                                transition: opacity 0.3s ease;
                                            }
                                            .book-table-img:hover .room-info {
                                                opacity: 1;
                                            }
                                        </style>
                                            <?php foreach ($services as $srvc): ?>
                                                <div class="book-table-img back-img swiper-slide"
                                                    style="background-image: url(../images/<?php echo $srvc['services_image']?>)">
                                                    <div class="room-info" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 15px; background: rgba(0,0,0,0.7); border-radius:25px;">
                                                        <h3 style="margin: 0; font-size: 1.5rem; color: white; font-family: impact; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"><?php echo $srvc['services_name']?></h3>
                                                        <p style="margin: 5px 0; font-size: 1.2rem; color: #ffc107; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">₱<?php echo number_format($srvc['services_price'], 2); ?></p>
                                                        <p style="margin: 0; font-size: 0.9rem; color: white;"><?php echo $srvc['services_description']?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                    </div>

                                    <div class="swiper-button-wp">
                                        <div class="swiper-button-prev swiper-button">
                                            <i class="uil uil-angle-left"></i>
                                        </div>
                                        <div class="swiper-button-next swiper-button">
                                            <i class="uil uil-angle-right"></i>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="our-team section">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">Our Team</p>
                                    <h2 class="h2-title">Meet our leadership!</h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="team-box text-center">
                                    <div style="background-image: url(../assets/images/chef/owner.jpg);"
                                        class="team-img back-img">
                                    </div>
                                    <h3 class="h3-title">Maritess Cayaco Marcos</h3>
                                    <div class="social-icon">
                                        <ul>
                                            <li>
                                                <a href="https://www.facebook.com/"><i class="uil uil-facebook-f"></i></a>
                                            </li>
                                            <li>
                                                <a href="https://www.instagram.com/">
                                                    <i class="uil uil-instagram"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.youtube.com/">
                                                    <i class="uil uil-youtube"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="team-box text-center">
                                    <div style="background-image: url(../assets/images/chef/manager.jpg);"
                                        class="team-img back-img">
                                    </div>
                                    <h3 class="h3-title">Nicole Marie Marcos</h3>
                                    <div class="social-icon">
                                        <ul>
                                            <li>
                                                <a href="https://www.facebook.com/"><i class="uil uil-facebook-f"></i></a>
                                            </li>
                                            <li>
                                                <a href="https://www.instagram.com/">
                                                    <i class="uil uil-instagram"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.youtube.com/">
                                                    <i class="uil uil-youtube"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="team-box text-center">
                                    <div style="background-image: url(../assets/images/chef/cashier.jpg);"
                                        class="team-img back-img">
                                    </div>
                                    <h3 class="h3-title">Myca Jacinto</h3>
                                    <div class="social-icon">
                                        <ul>
                                            <li>
                                                <a href="https://www.facebook.com/"><i class="uil uil-facebook-f"></i></a>
                                            </li>
                                            <li>
                                                <a href="https://www.instagram.com/">
                                                    <i class="uil uil-instagram"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.youtube.com/">
                                                    <i class="uil uil-youtube"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="testimonials section bg-light">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">What they say</p>
                                    <h2 class="h2-title">What our customers <span>say about us</span></h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="testimonials-img">
                                    <img src="../assets/images/testimonial-img.png" alt="">
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img"
                                                    style="background-image: url(assets/images/testimonials/t1.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:85%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Nilay Hirpara
                                                </h3>
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque,
                                                    quisquam.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img"
                                                    style="background-image: url(assets/images/testimonials/t2.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:80%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Ravi Kumawat
                                                </h3>
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque,
                                                    quisquam.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img"
                                                    style="background-image: url(assets/images/testimonials/t3.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:89%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Navnit Kumar
                                                </h3>
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque,
                                                    quisquam.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img"
                                                    style="background-image: url(assets/images/testimonials/t4.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:100%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Somyadeep Bhowmik
                                                </h3>
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque,
                                                    quisquam.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


       <?php include 'nav/footer.php'; ?>