<?php 
    include 'nav/admin_sidebar.php';
    require_once '../model/server.php';
    require_once '../model/Booking_Model.php';
    
    $model = new Booking_Model();
    $imagesModel = new Booking_Model();
    $connector = new Connector(); // Add database connection

    // Handle form submission for adding new room
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'];
        $name = $_POST['image_name'];
        $price = $_POST['image_price'];
        $image_id = $_POST['image_id'];
        
        // Get current image
        $sql = "SELECT image_img FROM image_tb WHERE image_id = ?";
        $stmt = $connector->getConnection()->prepare($sql);
        $stmt->execute([$image_id]);
        $current_image = $stmt->fetchColumn();
        
        // Handle file upload
        if (!empty($_FILES['image_img']['name'])) {
            $image = $_FILES['image_img']['name'];
            $target = "../images/" . basename($image);
            // Delete old image if exists
            if ($current_image && file_exists("../images/" . $current_image)) {
                unlink("../images/" . $current_image);
            }
            move_uploaded_file($_FILES['image_img']['tmp_name'], $target);
        } else {
            $image = $current_image;
        }

        $sql = "UPDATE image_tb SET image_description = ?, image_name = ?, image_price = ?, image_image = ? WHERE image_id = ?";
        $stmt = $connector->getConnection()->prepare($sql);
        $stmt->execute([$description, $name, $price, $image, $image_id]);
    }

    // Fetch existing rooms
    $sql = "SELECT * FROM image_tb";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $images = $imagesModel->get_images();
?>

<link rel="stylesheet" href="../assets/css/admin_rooms.css">
<!-- Carousel wrapper -->
<div class="container" id="carouselMultiItemExample" data-mdb-carousel-init class="carousel slide carousel-dark text-center" data-mdb-ride="carousel">
  <!-- Inner -->
    <div class="page-inner carousel-inner py-4">
        <form action="admin_food.php" method="POST" enctype="multipart/form-data">
            <div>
                <input type="text" class="form-control" placeholder="Food Name" aria-label="Food Name">
            </div>
            <div>
                <textarea type="text" class="form-control" placeholder="Description" aria-label="Description"></textarea>
            </div>
            <div>
                <input type="number" class="form-control" placeholder="Food Price" aria-label="Food Price">
            </div>
            <div>
                <input type="file" class="form-control" placeholder="Food Image" aria-label="Food Image">
            </div>
            <button type="submit" class="btn btn-primary">Add Room</button>
        </form>
        <div class="page-header">
            <h3 class="fw-bold mb-3">Admin Foods</h3>
        </div>
        <!-- Single item -->
        <div class="carousel-item active">
            <div class="container">
                <div class="row">
                    <?php foreach ($images as $img) : ?>
                        <div class="col-lg-4">
                            <div class="card">
                                <img src="../images/<?php echo $img['image_img']?>" 
                                     class="card-img-top" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     alt="Food Image"/>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $img['image_name']?></h5>
                                    <p class="card-text"><?php echo $img['image_description']?></p>
                                    <p class="card-text">₱<?= number_format($img['image_price'], 2) ?></p>
                                    <!-- Change button to trigger Bootstrap modal -->
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $img['image_id']?>">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for each room -->
                        <div class="modal fade" 
                             id="editModal<?php echo $img['image_id']?>" 
                             tabindex="-1" 
                             aria-labelledby="editModalLabel<?php echo $img['image_id']?>" 
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Room Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="services_id" value="<?php echo $img['image_id']?>">
                                            <div class="mb-3">
                                                <label class="form-label">Room Name</label>
                                                <input type="text" class="form-control" name="food_name" 
                                                       value="<?php echo $img['image_name']?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" 
                                                          rows="3"><?php echo $img['image_description']?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" class="form-control" name="food_price" 
                                                           value="<?php echo $img['image_price']?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <img src="../images/<?php echo $img['image_img']?>" 
                                                     class="img-fluid mb-2" 
                                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                                <input type="file" class="form-control" name="food_image">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
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