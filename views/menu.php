<?php 
require_once '../model/server.php';
$connector = new Connector();

$sql = "SELECT * FROM image_tb";
$stmt = $connector->getConnection()->prepare($sql);
$stmt->execute();
$breakfast = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'nav/header.php'; 
?>  


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


<?php include 'nav/footer.php'; ?>               