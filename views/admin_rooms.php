<?php include 'nav/admin_sidebar.php'; ?>
<?php
    $sql = "INSERT INTO services_tb (services_description, services_price, services_image) VALUES (?, ?, ?)";
    $stmt = $this->getConnection()->prepare($sql);
    $stmt->execute([$description, $price, $image]);
    $services = $stmt->fetchAll();
    
?>
<!-- Carousel wrapper -->
<div class="container" id="carouselMultiItemExample" data-mdb-carousel-init class="carousel slide carousel-dark text-center" data-mdb-ride="carousel">
  <!-- Inner -->
    <div class="page-inner carousel-inner py-4">
        <div>
            <input type="text" class="form-control" placeholder="Description" aria-label="Description">
        </div>
        <div>
            <input type="number" class="form-control" placeholder="Room Price" aria-label="Room Price">
        </div>
        <div>
            <input type="file" class="form-control" placeholder="Room Image" aria-label="Room Image">
        </div>
            <button type="submit" class="btn btn-primary">Add Room</button>
        <div class="page-header">
            <h3 class="fw-bold mb-3">Admin Rooms</h3>
        </div>
        <!-- Single item -->
        <div class="carousel-item active">
            <div class="container">
                <div class="row">
                    <?php foreach ($services as $srvc) : ?>
                        <div class="col-lg-4">
                            <div class="card">
                                <img src="../images/<?php echo $srvc['services_image']?>" class="card-img-top"  alt="Waterfall"/>
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk
                                        of the card's content.
                                    </p>
                                    <a href="#!" data-mdb-ripple-init class="btn btn-primary">Edit</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Inner -->
</div>

<?php include 'nav/admin_footer.php'; ?>