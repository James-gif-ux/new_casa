<?php 
    include 'nav/header.php'; 
    include '../model/Booking_Model.php'; 
    
    $model = new Booking_Model();
    $services = $model->get_service();
?>

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

<?php include 'nav/footer.php'; ?>   
