<?php 
    include 'nav/admin_sidebar.php';
    require_once '../model/server.php';
    require_once '../model/image_model.php';
    
    $model = new image_model();
    $images = $model->getimages();
    $connector = new Connector(); 

     // Handle form submission for adding new room
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the required POST variables exist
        $name = isset($_POST['image_name']) ? $_POST['image_name'] : '';
        $price = isset($_POST['image_price']) ? $_POST['image_price'] : 0;
        
        if (isset($_POST['image_id'])) {
            // Edit operation
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

            $sql = "UPDATE image_tb SET  image_name = ?, image_price = ?, image_img = ? WHERE image_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$name, $price, $image, $image_id]);
        } else {
            // Add operation
            $image = '';
            if (!empty($_FILES['image_img']['name'])) {
                $image = $_FILES['image_img']['name'];
                $target = "../images/" . basename($image);
                move_uploaded_file($_FILES['image_img']['tmp_name'], $target);
            }

            try {
                // Insert new food item
                $sql = "INSERT INTO image_tb (image_name, image_price, image_img) VALUES (?, ?, ?, ?)";
                $stmt = $connector->getConnection()->prepare($sql);
                $result = $stmt->execute([$name, $price, $image]);

                if ($result) {
                    // Check if image was uploaded successfully
                    if (!empty($_FILES['image_img']['name']) && !file_exists($target)) {
                        throw new Exception("Failed to upload image file.");
                    }
                    
                    // Redirect on success
                    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
                    exit();
                } else {
                    throw new Exception("Failed to insert record into database.");
                }
            } catch (Exception $e) {
                // If there was an error, remove uploaded file if it exists
                if (!empty($image) && file_exists($target)) {
                    unlink($target);
                }
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        }
    }
   
?>

<link rel="stylesheet" href="../assets/css/admin_rooms.css">
<!-- Carousel wrapper -->
<div class="container" id="carouselMultiItemExample">
  <!-- Inner -->
    <div class="page-inner carousel-inner py-4">
        <div class="card mb-5" style="max-width: 600px;">
            <div class="card-header">
            <h5 class="card-title mb-0">Add New Food</h5>
            </div>
            <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="text" name="image_name" class="form-control" placeholder="Food Name" required>
                </div>
                <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" name="image_price" class="form-control" placeholder="Food Price" required>
                </div>
                </div>
                <div class="mb-3">
                    <input type="file" name="image_img" class="form-control" placeholder="Food Image" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Food</button>
            </form>
            </div>
        </div>
        <!-- Single item -->
        <div class="card mb-5 py-4">
          <div class="card-body">
            <div class="carousel-item active">
                <div class="container">
                <div class="row">
                    <?php foreach ($images as $img) : ?>
                    <div class="col-lg-4">
                        <div class="card-body">
                            <img src="../images/<?php echo $img['image_img']?>" 
                                class="card-img-top" 
                                style="width: 100%; height: 200px; object-fit: cover;" 
                                alt="Food Image"/>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $img['image_name']?></h5>
                                <p class="card-text">₱<?= number_format($img['image_price'], 2) ?></p>
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
                                    <input type="hidden" name="image_id" value="<?php echo $img['image_id']?>">
                                    <div class="mb-3">
                                        <label class="form-label">Food Name</label>
                                        <input type="text" class="form-control" name="image_name" 
                                            value="<?php echo $img['image_name']?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="image_price" 
                                            value="<?php echo $img['image_price']?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                        <img src="../images/<?php echo $img['image_img']?>" 
                                            class="img-fluid mb-2" 
                                            style="max-height: 200px; width: 100%; object-fit: cover;">
                                        <input type="file" class="form-control" name="image_img">
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
        </div>
    </div>
    <!-- Inner -->
</div>

<?php include 'nav/admin_footer.php'; ?>