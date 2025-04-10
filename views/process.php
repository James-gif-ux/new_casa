<?php
require_once '../model/server.php';
$connector = new Connector();

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get reservation data if ID is provided
if (isset($_GET['reservation_id']) || isset($_POST['reservation_id'])) {
    $reservationId = $_GET['reservation_id'] ?? $_POST['reservation_id'];
    
    // Modified query to get services price
    $query = "SELECT r.*, s.services_price, s.services_name, pm.payment_method as payment_method,
              r.res_method_id, p.payment_id, p.reference_number, p.date_of_payment, 
              p.proof_of_payment
              FROM reservations r
              LEFT JOIN services_tb s ON r.res_services_id = s.services_id
              LEFT JOIN pay_method pm ON r.res_method_id = pm.method_id
              LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
              WHERE r.reservation_id = :id";
              
    $stmt = $connector->getConnection()->prepare($query);
    $stmt->execute([':id' => $reservationId]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Process form submission
        $reservationId = $_POST['reservation_id'];
        $paymentType = $_POST['payment_type'];
        $amount = floatval($_POST['amount']);
        $referenceNumber = htmlspecialchars($_POST['reference_number']);
        $dateOfPayment = htmlspecialchars($_POST['date_of_payment']);
        
        // Determine payment status based on payment type
        $paymentStatus = ($paymentType === 'full') ? 'paid' : 'partially_paid';

        // Handle file upload
        $proofOfPayment = '';
        if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../images/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, true)) {
                    throw new Exception("Failed to create upload directory");
                }
            }
            
            // Generate unique filename
            $fileExtension = strtolower(pathinfo($_FILES['proof_of_payment']['name'], PATHINFO_EXTENSION));
            $fileName = uniqid('receipt_') . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }
            
            // Validate file size (max 5MB)
            if ($_FILES['proof_of_payment']['size'] > 5 * 1024 * 1024) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }
            
            if (!move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload file. Please try again.");
            }
            
            $proofOfPayment = $fileName;
        }

        // Get the payment method ID first
        $methodSql = "SELECT pm.method_id 
                      FROM reservations r
                      JOIN pay_method pm ON r.res_method_id = pm.method_id
                      WHERE r.reservation_id = :resId";
        $methodStmt = $connector->getConnection()->prepare($methodSql);
        $methodStmt->execute([':resId' => $reservationId]);
        $methodId = $methodStmt->fetchColumn();

        if (!$methodId) {
            throw new Exception("Invalid payment method for this reservation");
        }

        // Get reservation and service details
        $detailsSql = "SELECT r.name, s.services_price 
                       FROM reservations r 
                       JOIN services_tb s ON r.res_services_id = s.services_id 
                       WHERE r.reservation_id = :resId";
        $detailsStmt = $connector->getConnection()->prepare($detailsSql);
        $detailsStmt->execute([':resId' => $reservationId]);
        $details = $detailsStmt->fetch(PDO::FETCH_ASSOC);

        // Validate payment amount
        $totalAmount = floatval($details['services_price']);
        if ($amount > $totalAmount) {
            throw new Exception("Payment amount cannot exceed the total amount");
        }

        // Insert payment record
        $sql = "INSERT INTO payments (name, amount, reference_number, date_of_payment, 
                proof_of_payment, status, pay_reservation_id, pay_method_id, payment_type) 
                VALUES (:name, :amount, :ref, :date, :proof, :status, :resId, :methodId, :payType)";
        
        $stmt = $connector->getConnection()->prepare($sql);
        $result = $stmt->execute([
            ':name' => $details['client_name'] ?? $details['name'] ?? 'Unknown',  // Try client_name first, then name, with fallback
            ':amount' => $amount,
            ':ref' => $referenceNumber,
            ':date' => $dateOfPayment,
            ':proof' => $proofOfPayment,
            ':status' => $paymentStatus,
            ':resId' => $reservationId,
            ':methodId' => $methodId,
            ':payType' => $paymentType
        ]);

        if ($result) {
            // Update reservation status
            $updateSql = "UPDATE reservations SET payment_status = :status WHERE reservation_id = :id";
            $updateStmt = $connector->getConnection()->prepare($updateSql);
            $updateStmt->execute([
                ':status' => $paymentStatus,
                ':id' => $reservationId
            ]);

            header("Location: home.php");
            exit;
        }
    } catch (Exception $e) {
        $errorMessage = addslashes($e->getMessage());
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to process payment: {$errorMessage}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method</title>
    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        form {
            background-color: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        form:hover {
            transform: translateY(-5px);
        }
        div {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
            font-size: 1.1em;
        }
        select, input[type="number"], input[type="text"], input[type="date"], input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 1em;
        }
        select:focus, input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 8px rgba(52,152,219,0.3);
        }
        input[type="submit"] {
            background: linear-gradient(to right, #2ecc71, #27ae60);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1.2em;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input[type="submit"]:hover {
            background: linear-gradient(to right, #27ae60, #219a52);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46,204,113,0.3);
        }
        .payment-icon {
            width: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>💳 Payment Method</h2>
    <form method="post" action="" id="paymentForm" enctype="multipart/form-data" onsubmit="return confirmPayment(event)">
        <input type="hidden" name="reservation_id" value="<?php echo $_GET['reservation_id'] ?? $_POST['reservation_id'] ?? ''; ?>">
        
        <div>
            <label for="payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required onchange="handlePaymentType()">
                <option value="">Select Payment Type</option>
                <option value="full">Full Payment</option>
                <option value="partial">Partial Payment</option>
            </select>
        </div>
        
        <div>
            <label for="total_amount">Total Amount (<?php echo htmlspecialchars($reservation['services_name'] ?? ''); ?>):</label>
            <input type="text" id="total_amount" value="<?php echo number_format($reservation['services_price'] ?? 0, 2); ?>" readonly>
        </div>
        
        <div id="amount_div">
            <label for="amount">Amount to Pay:</label>
            <input type="number" name="amount" id="amount" step="0.01" required 
                   value="<?php echo $reservation['services_price'] ?? 0; ?>" 
                   data-full-amount="<?php echo $reservation['services_price'] ?? 0; ?>">
        </div>

        <script>
            // Initialize the amount field based on payment type when page loads
            document.addEventListener('DOMContentLoaded', function() {
                handlePaymentType();
            });
        </script>
        
        <div>
            <label for="reference_number">Reference Number:</label>
            <input type="text" name="reference_number" placeholder="Enter reference number" required>
        </div>
        <div>
            <label for="date_of_payment">Date:</label>
            <input type="date" name="date_of_payment" required>
        </div>
        <div>
            <label for="proof_of_payments">Upload Receipt:</label>
            <input type="file" name="proof_of_payment" accept="image/*">
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>

   
    <script>
       function handlePaymentType() {
            const paymentType = document.getElementById('payment_type').value;
            const totalAmount = parseFloat(document.getElementById('total_amount').value.replace(/,/g, ''));
            const amountInput = document.getElementById('amount');

            if (paymentType === 'full') {
                amountInput.value = totalAmount;
                amountInput.readOnly = true;
            } else if (paymentType === 'partial') {
                amountInput.value = '';
                amountInput.readOnly = false;
                amountInput.max = totalAmount;
            }
        }
    
        function confirmPayment(event) {
            event.preventDefault();
            const amountInput = document.getElementById('amount');
            const totalAmount = parseFloat(document.getElementById('total_amount').value);
            const paymentType = document.getElementById('payment_type').value;
            const amount = parseFloat(amountInput.value);
        
            if (paymentType === 'partial' && amount >= totalAmount) {
                Swal.fire({
                    title: 'Invalid Amount',
                    text: 'Partial payment amount cannot be greater than or equal to the total amount',
                    icon: 'error'
                });
                return false;
            }
        
            if (paymentType === 'partial' && amount <= 0) {
                Swal.fire({
                    title: 'Invalid Amount',
                    text: 'Payment amount must be greater than 0',
                    icon: 'error'
                });
                return false;
            }
        
            Swal.fire({
                title: 'Confirm Payment',
                text: 'Are you sure you want to submit this payment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paymentForm').submit();
                }
            });
            return false;
        }
    </script>
</body>
</html>
