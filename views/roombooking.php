<?php
    require_once 'nav/header.php';
    include_once '../model/BookingModel.php';
    include_once '../model/Booking_Model.php';

    $model = new BookingModel();
    $bookingModel = new Booking_Model();

    // Get all services
    $services = $bookingModel->get_service();

    // Include the Connector class
    require_once '../model/server.php';
    $connector = new Connector();

    // Fetch all bookings that are pending approval
   
    // Store POST data if available
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['check_in'] = $_POST['checkin_date'];
        $_SESSION['check_out'] = $_POST['checkout_date'];
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : 'Default Name';
        $email = isset($_POST['email']) ? $_POST['email'] : 'default@example.com';
        $number = isset($_POST['number']) ? $_POST['number'] : 'Default Number';
        $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
        $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;
        $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null;

        // Attempt to insert the booking with check-in and check-out dates
        $result = $bookingModel->insert_booking($fullname, $email, $number, $check_in, $check_out, $service_id);

        if ($result === true) {
            $_SESSION['booking_success'] = true;
            header("Location: confirmation.php");
            exit();
        } else {
            $_SESSION['error'] = $result;
            header("Location: books.php");
            exit();
        }
    }
    ?>

     <link rel="stylesheet" href="../assets/css/books.css">

        <div class="content-wrapper">
            <div class="rooms-container">
                <section id="services" class="services section">
                    <div class="service-list">
                        <?php foreach ($services as $srvc): 
                            // Check room status from services_tb
                            $sql = "SELECT status FROM services_tb 
                                   WHERE services_id = :service_id";
                            $stmt = $connector->getConnection()->prepare($sql);
                            $stmt->execute([':service_id' => $srvc['services_id']]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $isAvailable = ($result['status'] === 'available');
                        ?>
                            <div class="service-item">
                                <img src="../images/<?= $srvc['services_image'] ?>" alt="<?= $srvc['services_name'] ?>" class="service-image">
                                <div class="service-content">
                                    <div>
                                        <h3><?= $srvc['services_name'] ?></h3>
                                        <p class="description"><?= $srvc['services_description'] ?></p>
                                        <div class="status-badge <?= $isAvailable ? 'available' : ($result['status'] === 'maintenance' ? 'maintenance' : 'occupied') ?>">
                                        <?= ($isAvailable ? 'Available' : ($result['status'] === 'maintenance' ? 'Maintenance' : 'Occupied')) ?>    
                                        </div>
                                    </div>
                                    <div>
                                        <div class="service-price">
                                            ₱<?= number_format($srvc['services_price'], 2) ?>
                                        </div>
                                        <a href="reservation.php?service_id=<?= $srvc['services_id'] ?>" 
                                           class="readmore <?= !$isAvailable ? 'disabled' : '' ?>"
                                           <?= !$isAvailable ? 'onclick="return false"' : '' ?>>
                                            <span><?= $isAvailable ? 'Reserve Now' : 'Not Available' ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>

        <style>
            .status-badge {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 15px;
                font-size: 0.9rem;
                font-weight: 600;
                margin-top: 10px;
            }
            .status-badge.available {
                background-color: #28a745;
                color: white;
            }
            .status-badge.occupied {
                background-color:rgb(224, 125, 59);
                color: white;
            }
            .status-badge.maintenance {
                background-color: #dc3545;
                color: white;
            }
            .readmore.disabled {
                background-color: #6c757d;
                cursor: not-allowed;
                opacity: 0.65;
            }
        </style>

    <?php include 'nav/footer.php'; ?>