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
        // Check if this is a status update
        if (isset($_POST['update_status'])) {
            $services_id = $_POST['services_id'];
            $new_status = $_POST['new_status'];
            $status_note = $_POST['status_note'];

            try {
                // Validate status value
                $allowed_statuses = ['available', 'occupied', 'maintenance'];
                if (in_array($new_status, $allowed_statuses)) {
                    $sql = "UPDATE services_tb SET status = ?, status_note = ? WHERE services_id = ?";
                    $stmt = $connector->getConnection()->prepare($sql);
                    $stmt->execute([$new_status, $status_note, $services_id]);
                    
                    // Redirect with success message
                    echo "<script>
                        window.location.href = 'admin_rooms.php?status_updated=1';
                    </script>";
                    exit();
                }
            } catch (Exception $e) {
                echo "<script>alert('Error updating status: " . $e->getMessage() . "');</script>";
            }
        }

        if (isset($_POST['services_id'])) {
            // Edit operation
            $services_id = $_POST['services_id'];
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $name = isset($_POST['room_name']) ? $_POST['room_name'] : '';
            $price = isset($_POST['services_price']) ? $_POST['services_price'] : 0;
            
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
    if (isset($_GET['services_id'])) {
        try {
            $services_id = $_GET['services_id'];
            
            // Get the image filename first
            $sql = "SELECT services_image FROM services_tb WHERE services_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$services_id]);
            $image = $stmt->fetchColumn();
            
            // Delete the image file if it exists
            if ($image && file_exists("../images/" . $image)) {
                unlink("../images/" . $image);
            }
            
            // Delete the record
            $sql = "DELETE FROM services_tb WHERE services_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$services_id]);
            
            // Redirect back to the same page
            echo "<script>window.location.href = 'admin_rooms.php';</script>";
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error deleting service: " . $e->getMessage() . "');</script>";
        }
    }
    // Fetch existing rooms
    $sql = "SELECT * FROM services_tb";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $services = $services->get_service();

    // Remove this section that resets all booked rooms to available
    // $sql = "UPDATE services_tb SET status = 'available' WHERE status = 'booked'";
    // $stmt = $connector->getConnection()->prepare($sql);
    // $stmt->execute();
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../assets/css/admin_rooms.css">
<!-- Carousel wrapper -->
<div class="container-fluid py-4" style="min-height: calc(100vh - 60px);">
    <div class="row g-4">
        <!-- Add New Room Form -->
        <div class="col-md-4" style="padding-top: 90px;">
            <div class="card shadow-lg border-0 rounded-3" style="position: sticky; top: 90px;">
                <div class="card-body p-4">
                    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Room Name</label>
                            <input type="text" name="room_name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3" style="resize: none;"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">₱</span>
                                <input type="number" name="services_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Room Image</label>
                            <div class="input-group">
                                <input type="file" name="services_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <div id="imagePreview" class="mt-2 d-none">
                                <img src="" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="fas fa-save me-2"></i>Add Room
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Room List -->
        <div class="col-md-8" style="padding-top: 90px;">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="row g-3">
                        <?php foreach ($services as $srvc) : ?>
                            <div class="col-xl-4 col-lg-6">
                                <div class="card h-100 shadow-sm hover-shadow transition-all">
                                    <div class="position-relative">
                                        <img src="../images/<?php echo $srvc['services_image']?>" 
                                             class="card-img-top" 
                                             style="height: 180px; object-fit: cover;" 
                                             alt="Room Image"/>
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <span class="badge bg-dark">
                                                ₱<?= number_format($srvc['services_price'], 2) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body" style="height: 180px; overflow-y: auto;">
                                        <h5 class="card-title text-dark mb-2"><?php echo $srvc['services_name']?></h5>
                                        <p class="card-text text-muted small">
                                            <?php echo $srvc['services_description']?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 p-3">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" 
                                                    class="btn btn-outline-dark btn-sm flex-grow-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $srvc['services_id']?>">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm flex-grow-1" 
                                                    onclick="deleteService(<?php echo $srvc['services_id'] ?>)">
                                                <i class="fas fa-trash-alt me-1"></i>Delete
                                            </button>
                                            <div class="card-header">
                                                    <h5 class="card-title">Room Status Management</h5>
                                                </div>
                                                <div class="card-body">
                                                    <!-- Current Status Badge -->
                                                    <div class="mb-3">
                                                        <span class="badge rounded-pill 
                                                            <?php 
                                                                switch($srvc['status']) {
                                                                    case 'available': echo 'bg-success'; break;
                                                                    case 'occupied': echo 'bg-warning'; break;
                                                                    case 'maintenance': echo 'bg-danger'; break;
                                                                    default: echo 'bg-secondary';
                                                                }
                                                            ?>">
                                                            <?php echo ucfirst($srvc['status']); ?>
                                                        </span>
                                                    </div>

                                                <form action="" method="POST" class="mb-3">
                                                    <input type="hidden" name="services_id" value="<?php echo $srvc['services_id']; ?>">
                                                    <div class="input-group">
                                                        <select name="new_status" class="form-select">
                                                            <option value="available" <?php echo $srvc['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                                            <option value="occupied" <?php echo $srvc['status'] == 'occupied' ? 'selected' : ''; ?>>occupied</option>
                                                            <option value="maintenance" <?php echo $srvc['status'] == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                                        </select>
                                                        <input type="text" name="status_note" class="form-control" placeholder="Add note (optional)">
                                                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $srvc['services_id']?>">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg border-0 rounded-3">
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title text-black m-0">
                                                <i class="fas fa-edit me-2"></i>Edit Room Details
                                            </h5>
                                            <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="services_id" value="<?php echo $srvc['services_id']?>">
                                                
                                                <!-- Room Information Section -->
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-secondary">Room Name</label>
                                                        <input type="text" class="form-control border-0 bg-light" 
                                                               name="room_name" value="<?php echo $srvc['services_name']?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-secondary">Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text border-0 bg-light">₱</span>
                                                            <input type="number" class="form-control border-0 bg-light" 
                                                                   name="services_price" value="<?php echo $srvc['services_price']?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-secondary">Description</label>
                                                        <textarea class="form-control border-0 bg-light" 
                                                                  name="description" rows="3"><?php echo $srvc['services_description']?></textarea>
                                                    </div>

                                                    <!-- Image Upload Section -->
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-secondary">Room Image</label>
                                                        <div class="card bg-light border-0 p-2 mb-2">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <img src="../images/<?php echo $srvc['services_image']?>" 
                                                                     class="rounded shadow-sm" 
                                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                                <div class="flex-grow-1">
                                                                    <input type="file" class="form-control border-0 bg-white" 
                                                                           name="services_image" accept="image/*">
                                                                    <small class="text-muted mt-1 d-block">
                                                                        Upload a new image or keep the existing one
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="d-flex gap-2 justify-content-end mt-4">
                                                    <button type="button" class="btn btn-light px-4 hover-shadow" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-dark px-4 hover-shadow">
                                                        <i class="fas fa-save me-2"></i>Save Changes
                                                    </button>
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

<script>
    function deleteService(serviceId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#212529',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                try {
                    window.location.href = 'admin_rooms.php?services_id=' + serviceId;
                } catch (error) {
                    Swal.fire('Error!', 'Error deleting room: ' + error, 'error');
                }
            }
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const image = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('d-none');
        }
    }
</script> 


<script>
    function updateRoomStatus(serviceId, status, element) {
    fetch('update_room_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `services_id=${serviceId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Update UI to show current status
            const dropdownItems = element.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.querySelector('.fa-check')?.remove();
            });
            
            // Add check mark to selected status
            const checkIcon = document.createElement('i');
            checkIcon.className = 'fas fa-check text-success ms-2';
            element.appendChild(checkIcon);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: `Room status has been updated to ${status}`,
                showConfirmButton: false,
                timer: 1500
            });
            
            // Redirect to refresh the page
            window.location.href = window.location.pathname;
            exit();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update room status'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the status'
        });
    });
    }
    </script>

<?php include 'nav/admin_footer.php'; ?>