 <!-- footer starts  -->
<footer class="site-footer" id="contact" style="display: flex; flex-direction: column; justify-content: center;">
    <div class="top-footer section">
        <div class="sec-wp">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="footer-info text-center">
                            <div class="footer-logo">
                                <a href="index.html">
                                    <img src="logo.png" alt="">
                                </a>
                            </div>
                            Welcome to Casa Marcos. Visit us for authentic Mexican flavors in a cozy atmosphere.
                            </p>
                            <div class="social-icon">
                                <ul class="d-flex justify-content-center">
                                    <li>
                                        <a href="https://www.facebook.com/">
                                            <i class="uil uil-facebook-f"></i>
                                        </a>
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
                    <div class="col-lg-8">
                        <div class="footer-flex-box d-flex justify-content-center">
                            <div class="footer-table-info text-center mx-4">
                                <h3 class="h3-title">open hours</h3>
                                <ul>
                                    <li><i class="uil uil-clock"></i> Mon-Thurs : 9am - 22pm</li>
                                    <li><i class="uil uil-clock"></i> Fri-Sun : 11am - 22pm</li>
                                </ul>
                            </div>
                            <div class="footer-menu food-nav-menu text-center mx-4">
                                <h3 class="h3-title">Links</h3>
                                <ul class="column-2">
                                    <?php 
                                    $current_page = basename($_SERVER['PHP_SELF']);
                                    $menu_items = [
                                        'home.php' => 'Home',
                                        'about.php' => 'About',
                                        'menu.php' => 'Food Menu',
                                        'rooms.php' => 'Our Rooms',
                                        'contact.php' => 'Contact'
                                    ];
                                    
                                    foreach ($menu_items as $page => $label) {
                                        $active_class = ($current_page === $page) ? 'footer-active-menu' : '';
                                        echo "<li><a href=\"$page\" class=\"$active_class\">$label</a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="copyright-text d-flex justify-content-between align-items-center">
                        <p>Copyright &copy; 2021 <span class="name">TechieCoder.</span>All Rights Reserved.</p>
                        <p class="name"><a href="termC.php" style="color: #ff8243;">Terms & Conditions</a></p>
                    </div>
                </div>
            </div>
            <button class="scrolltop"><i class="uil uil-angle-up"></i></button>
        </div>
     </div>
</footer>



       




    <!-- jquery  -->
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <!-- bootstrap -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>

    <!-- fontawesome  -->
    <script src="../assets/js/font-awesome.min.js"></script>

    <!-- swiper slider  -->
    <script src="../assets/js/swiper-bundle.min.js"></script>

    <!-- mixitup -- filter  -->
    <script src="../assets/js/jquery.mixitup.min.js"></script>

    <!-- fancy box  -->
    <script src="../assets/js/jquery.fancybox.min.js"></script>

    <!-- parallax  -->
    <script src="../assets/js/parallax.min.js"></script>

    <!-- gsap  -->
    <script src="../assets/js/gsap.min.js"></script>

    <!-- scroll trigger  -->
    <script src="../assets/js/ScrollTrigger.min.js"></script>
    <!-- scroll to plugin  -->
    <script src="../assets/js/ScrollToPlugin.min.js"></script>
    <!-- rellax  -->
    <!-- <script src="assets/js/rellax.min.js"></script> -->
    <!-- <script src="assets/js/rellax-custom.js"></script> -->
    <!-- smooth scroll  -->
    <script src="../assets/js/smooth-scroll.js"></script>
    <!-- custom js  -->
    <script src="../assets/js/main.js"></script>

</body>

</html>