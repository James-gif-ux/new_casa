<?php
    session_start();
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
            header("Location: number.php");
            exit();
        }
    } else {
        // Redirect back to books.php if no service_id is provided
        header("Location: number.php");
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
                header('location:number.php');
                exit();
            } else {
                echo "<script>alert('Error submitting reservation.');</script>";
            }
            
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>   
   
    <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Reservred Booking</title>
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@500&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="../assets/css/reservation.css">
            </head>
        <body>
            <div class="reservation-page">
                <!-- Room Image Section -->
                <div class="room-image-section">
                    <img src="../images/<?= $service['services_image'] ?>" alt="Room Image" class="room-image">
                    <h3><?= $service['services_name'] ?></h3>
                    <p><?= $service['services_description'] ?></p>
                    <p class="service-price">₱<?= number_format($service['services_price'], 2) ?></p>
                </div>
                <?php
                    ?>
                <!-- Reservation Form Section -->
                <div class="reservation-container">
                    <div class="right-section">
                        <h2>Make a Reservation</h2>
                        <form method="POST" action="" class="reservation-form">
                            <div class="form-group">
                                <label for="name">Full Name:</label>
                                <input type="text" id="name" name="name" required onblur="checkDuplicate('name', this.value)">
                                <span id="name-error" class="error-message"></span>
                            </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" required onblur="checkDuplicate('email', this.value)">
                                    <span id="email-error" class="error-message"></span>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="tel" id="phone" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label for="checkin">Check in:</label>
                                    <input type="date" id="checkin" name="checkin" required min="<?= date('Y-m-d') ?>" 
                                           data-reserved='<?= $disabled_dates_json ?>'>
                                </div>
                                <div class="form-group">
                                    <label for="checkout">Check out:</label>
                                    <input type="date" id="checkout" name="checkout" required 
                                           data-reserved='<?= $disabled_dates_json ?>'>
                                </div>

                                <div class="form-group">
                                    <label for="message">Special Requests:</label>
                                    <textarea id="message" name="message" rows="4"></textarea>
                                </div>

                                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">

                                <div style="display: flex; gap: 10px; justify-content: space-between;">
                                    <button type="submit" class="submit-btn" style="width: 48%;">Make Reservation</button>
                                    <button type="button" onclick="window.location.href='roombooking.php'" 
                                        class="submit-btn" 
                                        style="width: 48%; background-color: #8B7355;">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    document.querySelector('.reservation-form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (confirm('Are you sure you want to make this reservation?')) {
                            this.submit();
                        }
                    });
                </script>
                

                <script>
                    let nameExists = false;
                    let emailExists = false;

                    function checkDuplicate(field, value) {
                        if (!value.trim()) return;
                        
                        fetch(`../pages/check_duplicate.php?field=${field}&value=${encodeURIComponent(value)}`)
                            .then(response => response.json())
                            .then(data => {
                                const errorElement = document.getElementById(`${field}-error`);
                                if (data.exists) {
                                    errorElement.textContent = `This ${field} is already registered`;
                                    errorElement.style.color = 'red';
                                    if (field === 'name') nameExists = true;
                                    if (field === 'email') emailExists = true;
                                } else {
                                    errorElement.textContent = '';
                                    if (field === 'name') nameExists = false;
                                    if (field === 'email') emailExists = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error checking duplicate:', error);
                            });
                    }

                    function validateForm() {
                        const name = document.getElementById('name').value;
                        const email = document.getElementById('email').value;
                        
                        // Check both fields one last time before submission
                        return new Promise((resolve) => {
                            Promise.all([
                                fetch(`../pages/check_duplicate.php?field=name&value=${encodeURIComponent(name)}`),
                                fetch(`../pages/check_duplicate.php?field=email&value=${encodeURIComponent(email)}`)
                            ])
                            .then(responses => Promise.all(responses.map(res => res.json())))
                            .then(([nameData, emailData]) => {
                                if (nameData.exists || emailData.exists) {
                                    alert('Please fix the duplicate entries before submitting');
                                    resolve(false);
                                } else {
                                    resolve(true);
                                }
                            })
                            .catch(error => {
                                console.error('Validation error:', error);
                                resolve(false);
                            });
                        });
                    }
                
                  
                </script>
                <script>
                    const disabledDates = JSON.parse(document.getElementById('checkin').dataset.reserved);
                    
                    function disableDates(element) {
                        const date = new Date(element.value);
                        const dateString = date.toISOString().split('T')[0];
                        if (disabledDates.includes(dateString)) {
                            alert('This date is already reserved. Please select another date.');
                            element.value = '';
                        }
                    }

                    document.getElementById('checkin').addEventListener('change', function() {
                        disableDates(this);
                    });

                    document.getElementById('checkout').addEventListener('change', function() {
                        disableDates(this);
                    });
                </script>
                <script>
                    // Set min dates and add validation
                    const checkin = document.getElementById('checkin');
                    const checkout = document.getElementById('checkout');
                    
                    checkin.addEventListener('change', function() {
                        checkout.min = this.value;
                        if(checkout.value && checkout.value < this.value) {
                            checkout.value = this.value;
                        }
                    });
                    
                    checkout.addEventListener('change', function() {
                        if(this.value < checkin.value) {
                            alert('Check-out date cannot be before check-in date');
                            this.value = checkin.value;
                        }
                    });
                </script>
            </body>
        </html>
