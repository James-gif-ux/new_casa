<?php 
    // Start output buffering at the very beginning
    ob_start();
    
    include 'nav/admin_sidebar.php';
    require_once '../model/server.php';
    require_once '../model/Booking_Model.php';
    
    $model = new Booking_Model();
    $services = new Booking_Model();
    $connector = new Connector();
    $rooms = $model->add_services();

    // Handle form submission for adding new room
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the required POST variables exist
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $name = isset($_POST['room_name']) ? $_POST['room_name'] : '';
        $price = isset($_POST['services_price']) ? $_POST['services_price'] : 0;
        
        if (isset($_POST['services_id'])) {
            // Edit operation
            $services_id = $_POST['services_id'];
            
            // Get current image
            $sql = "SELECT services_image FROM services_tb WHERE services_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$services_id]);
            $current_image = $stmt->fetchColumn();
            
            // Handle file upload
            if (!empty($_FILES['services_image']['name'])) {
                $image = $_FILES['services_image']['name'];
                $target = "../images/" . basename($image);
                // Delete old image if exists
                if ($current_image && file_exists("../images/" . $current_image)) {
                    unlink("../images/" . $current_image);
                }
                move_uploaded_file($_FILES['services_image']['tmp_name'], $target);
            } else {
                $image = $current_image;
            }

            $sql = "UPDATE services_tb SET services_description = ?, services_name = ?, services_price = ?, services_image = ? WHERE services_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$description, $name, $price, $image, $services_id]);
        } else {
            // Add operation
            $image = '';
            if (!empty($_FILES['services_image']['name'])) {
                $image = $_FILES['services_image']['name'];
                $target = "../images/" . basename($image);
                move_uploaded_file($_FILES['services_image']['tmp_name'], $target);
            }

            // Insert new room
            $sql = "INSERT INTO services_tb (services_description, services_name, services_price, services_image) VALUES (?, ?, ?, ?)";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$description, $name, $price, $image]);
            
            // Use JavaScript redirect instead of header()
            echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }
    }

    // Fetch existing rooms
    $sql = "SELECT * FROM services_tb";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $services = $services->get_service();
?>

<link rel="stylesheet" href="../assets/css/admin_rooms.css">
<!-- Carousel wrapper -->
<div class="container" id="carouselMultiItemExample" data-mdb-carousel-init class="carousel slide carousel-dark text-center" data-mdb-ride="carousel">
  <!-- Inner -->
    <div class="page-inner carousel-inner py-4">
    <div class="row">
        <div class="col-md-5">
            <div class="card mb-5">
                <div class="card-header">
                    <h5 class="mb-0">Add New Room</h5>
                </div>
                <div class="card-body">
                    <form action="../pages/admin_rooms.php?function=add_services&&sub_page=add_services" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="room_name" class="form-control" placeholder="Room Name" aria-label="Room Name" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="description" class="form-control" placeholder="Description" aria-label="Description"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="services_price" class="form-control" placeholder="Room Price" aria-label="Room Price" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="services_image" class="form-control" placeholder="Room Image" aria-label="Room Image">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Room</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <!-- Existing carousel content will go here -->
        </div>
    </div>
        <!-- Single item -->
<div class="card py-4">
    <div class="card-body">
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
</div>
    </div>
    <!-- Inner -->
</div>

<?php include 'nav/admin_footer.php'; ?>