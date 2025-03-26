<?php 
    ob_start();
    
    include 'nav/admin_sidebar.php';
    require_once '../model/server.php';
    require_once '../model/Booking_Model.php';
    
    $connector = new Connector();
    $model = new Booking_Model();
    
    // Handle form submission for adding new room
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $name = isset($_POST['room_name']) ? $_POST['room_name'] : '';
            $price = isset($_POST['services_price']) ? $_POST['services_price'] : 0;
            
            if (isset($_POST['services_id'])) {
                // Edit operation
                $services_id = $_POST['services_id'];
                
                // Handle file upload
                if (!empty($_FILES['services_image']['name'])) {
                    $image = $_FILES['services_image']['name'];
                    $target = "../images/" . basename($image);
                    move_uploaded_file($_FILES['services_image']['tmp_name'], $target);
                } else {
                    $image = ''; // or get current image from database
                }

                // Use model method to edit room
                $result = $model->edit_rooms($services_id, $description, $name, $price, $image);
                
                if($result) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            } else {
                // Add operation
                $image = '';
                if (!empty($_FILES['services_image']['name'])) {
                    $image = time() . '_' . $_FILES['services_image']['name'];
                    $target = "../images/" . basename($image);
                    if (move_uploaded_file($_FILES['services_image']['tmp_name'], $target)) {
                        // File uploaded successfully
                        $result = $model->add_room($name, $description, $price, $image);
                        
                        if($result) {
                            echo "<script>
                                alert('Room added successfully!');
                                window.location.href = 'admin_rooms.php';
                            </script>";
                            exit();
                        }
                    }
                } else {
                    // No image uploaded, still add the room
                    $result = $model->add_room($name, $description, $price, $image);
                    
                    if($result) {
                        echo "<script>
                            alert('Room added successfully!');
                            window.location.href = 'admin_rooms.php';
                        </script>";
                        exit();
                    }
                }
            }
        } catch(PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    // Fetch existing rooms
    $services = $model->get_service();
    // Remove this line as it's causing the error
    // $rooms = $model->add_room();
?>

<link rel="stylesheet" href="../assets/css/admin_rooms.css">
<!-- Carousel wrapper -->
<div class="container" id="carouselMultiItemExample" data-mdb-carousel-init class="carousel slide carousel-dark text-center" data-mdb-ride="carousel">
  <!-- Inner -->
    <div class="page-inner carousel-inner py-4">
        <form action="admin_rooms.php" method="post" enctype="multipart/form-data">
            <div>
                <input type="text" name="room_name" class="form-control" placeholder="Room Name" aria-label="Room Name" required>
            </div>
            <div>
                <textarea name="description" class="form-control" placeholder="Description" aria-label="Description"></textarea>
            </div>
            <div>
                <input type="number" name="services_price" class="form-control" placeholder="Room Price" aria-label="Room Price" required>
            </div>
            <div>
                <input type="file" name="services_image" class="form-control" placeholder="Room Image" aria-label="Room Image">
            </div>
            <button type="submit" class="btn btn-primary">Add Room</button>
        </form>
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
                                <img src="../images/<?php echo $srvc['services_image']?>" 
                                     class="card-img-top" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     alt="Room Image"/>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $srvc['services_name']?></h5>
                                    <p class="card-text"><?php echo $srvc['services_description']?></p>
                                    <p class="card-text">₱<?= number_format($srvc['services_price'], 2) ?></p>
                                    <!-- Change button to trigger Bootstrap modal -->
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $srvc['services_id']?>">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for each room -->
                        <div class="modal fade" 
                             id="editModal<?php echo $srvc['services_id']?>" 
                             tabindex="-1" 
                             aria-labelledby="editModalLabel<?php echo $srvc['services_id']?>" 
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Room Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="services_id" value="<?php echo $srvc['services_id']?>">
                                            <div class="mb-3">
                                                <label class="form-label">Room Name</label>
                                                <input type="text" class="form-control" name="room_name" 
                                                       value="<?php echo $srvc['services_name']?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" 
                                                          rows="3"><?php echo $srvc['services_description']?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" class="form-control" name="services_price" 
                                                           value="<?php echo $srvc['services_price']?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <img src="../images/<?php echo $srvc['services_image']?>" 
                                                     class="img-fluid mb-2" 
                                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                                <input type="file" class="form-control" name="services_image">
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