<?php 
    session_start();
    require_once '../model/server.php';
    require_once '../model/Booking_Model.php';
    
    $connector = new Connector();
    $model = new Booking_Model();

    // Get reservation ID from URL
    $reservation_id = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : '';
    
    // Fetch specific reservation details if ID exists
    $reservation = null;
    if (!empty($reservation_id)) {
        $reservation = $model->get_reservation_with_payment($reservation_id);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Debug information
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            // Get and validate reservation_id from POST or GET
            $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : 
                            (isset($_GET['reservation_id']) ? $_GET['reservation_id'] : '');
                            
            if (empty($reservation_id)) {
                throw new Exception("Invalid reservation ID. Please try again.");
            }

            // Sanitize the reservation ID
            $reservation_id = filter_var($reservation_id, FILTER_SANITIZE_NUMBER_INT);
            
            // Validate reservation exists
            $check_reservation = $model->get_reservation_with_payment($reservation_id);
            if (!$check_reservation) {
                throw new Exception("Reservation not found.");
            }

            // Get and validate other inputs
            $reference = $_POST['payment_reference'] ?? '';
            $amount = $_POST['payment_amount'] ?? 0;
            $date = $_POST['payment_date'] ?? '';
            $status = 'pending';

            // Validate file upload
            if (empty($_FILES['payment_image']['name'])) {
                throw new Exception("Payment proof is required");
            }

            // Process image upload
            $image = time() . '_' . $_FILES['payment_image']['name'];
            $target = "../images/" . $image;
            
            if (!move_uploaded_file($_FILES['payment_image']['tmp_name'], $target)) {
                throw new Exception("Failed to upload image");
            }

            // Process the payment
            $result = $model->process_payment($reservation_id, $reference, $image, $amount, $date, $status);

            if ($result) {
                $_SESSION['success'] = "Payment processed successfully!";
                header("Location: ../pages/roomBooking.php");
                exit();
            } else {
                throw new Exception("Payment processing failed");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            error_log("Payment Error: " . $e->getMessage());
        }
    }

    // Display any error messages at the top of the form
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']);
    }
?>
<?php
    require_once '../model/server.php';
    include_once '../model/reservationModel.php';

    $model = new Reservation_Model();
    $reservationModel = new Reservation_Model();
    $connector = new Connector(); // Initialize connector before using it

    // Get specific service based on URL parameter
    if (isset($_GET['service_id'])) {
        $service_id = $_GET['service_id'];
        $sql = "SELECT * FROM services_tb WHERE services_id = :service_id";
        $stmt = $connector->getConnection()->prepare($sql);
        $stmt->execute([':service_id' => $service_id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$service) {
            header("Location: roombooking.php");
            exit();
        }
    } else {
        // Redirect back to books.php if no service_id is provided
        header("Location: roombooking.php");
        exit();
    }

    // Get all services
    $services = $reservationModel->get_booking();

    // Include the Connector class
    require_once '../model/server.php';
    $connector = new Connector();

    // Fetch all bookings that are pending approval
    $sql = "SELECT reservation_id, name, email, phone, checkin, checkout, message FROM reservations WHERE status = 'pending'";
    $reservations = $connector->executeQuery($sql);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $connector = new Connector();
            
            // Updated SQL to include res_services_id
            $sql = "INSERT INTO reservations (name, email, phone, checkin, checkout, message, status, res_services_id) 
                    VALUES (:name, :email, :phone, :checkin, :checkout, :message, 'pending', :service_id)";
            
            $stmt = $connector->getConnection()->prepare($sql);
            $result = $stmt->execute([
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':phone' => $_POST['phone'],
                ':checkin' => $_POST['checkin'],
                ':checkout' => $_POST['checkout'],
                ':message' => $_POST['message'],
                ':service_id' => $_POST['service_id'] // This gets the hidden service_id field value
            ]);

            if ($result) {
                echo "<script>alert('Reservation submitted successfully!');</script>";
            }
            
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>   
    <?php
    // Fetch all reserved dates
    $sql = "SELECT checkin, checkout FROM reservations WHERE res_services_id = :service_id AND status != 'cancelled'";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute([':service_id' => $service_id]);
    $reserved_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create array of reserved dates
    $disabled_dates = [];
    foreach ($reserved_dates as $reservation) {
        $period = new DatePeriod(
            new DateTime($reservation['checkin']),
            new DateInterval('P1D'),
            new DateTime($reservation['checkout'])
        );
        foreach ($period as $date) {
            $disabled_dates[] = $date->format('Y-m-d');
        }
    }
    $disabled_dates_json = json_encode($disabled_dates);
?>
<title>Payment Process  </title>

<div class="container mt-4">
    <link rel="stylesheet" href="../assets/css/process.css">

    <div class="payment-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="payment-card">
                    <div class="card-header">
                        <h4>Process Payment</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="reservation_id" value="<?php echo isset($_GET['reservation_id']) ? $_GET['reservation_id'] : ''; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="payment_reference" required 
                                       placeholder="Enter reference number">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" name="payment_amount" 
                                           step="0.01" required placeholder="Enter amount">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date of Payment</label>
                                <input type="date" class="form-control" name="payment_date" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Proof of Payment</label>
                                <input type="file" class="form-control" name="payment_image" 
                                       accept="image/*" required>
                                <small class="text-muted">Upload screenshot or photo of payment receipt</small>
                            </div>

                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary">Submit Payment</button>
                                <a href="../pages/roomBooking.php" class="btn btn-primary">Back to Bookings</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>