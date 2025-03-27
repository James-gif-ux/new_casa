<?php
require_once '../model/server.php';
$connector = new Connector();

try {
    // Handle file upload first
    $proofOfPayment = '';
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
        $proof = $_FILES['proof_of_payment']['name'];
        $target = "../images/" . basename($proof);
        
        // Create directory if it doesn't exist
        if (!file_exists("../images/")) {
            mkdir("../images/", 0777, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $target)) {
            $proofOfPayment = $proof;
        } else {
            throw new Exception("Failed to upload file");
        }
    }

    // Database insertion
    $sql = "INSERT INTO payments (name, amount, payment_method, reference_number, date_of_payment, proof_of_payment, status) VALUES (?,?,?,?,?,?,?)";
    $stmt = $connector->getConnection()->prepare($sql);

    // Validate and sanitize inputs
    $name = $_POST['name'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $paymentMethod = $_POST['payment_method'] ?? 'default_payment_method';
    $referenceNumber = $_POST['reference_number'] ?? '';
    $dateOfPayment = $_POST['date_of_payment'] ?? '';
    $status = $_POST['status'] ?? 'paid';

    // Bind parameters
    $stmt->bindParam(1, $name, PDO::PARAM_STR);
    $stmt->bindParam(2, $amount, PDO::PARAM_STR);
    $stmt->bindParam(3, $paymentMethod, PDO::PARAM_STR);
    $stmt->bindParam(4, $referenceNumber, PDO::PARAM_STR);
    $stmt->bindParam(5, $dateOfPayment, PDO::PARAM_STR);
    $stmt->bindParam(6, $proofOfPayment, PDO::PARAM_STR);
    $stmt->bindParam(7, $status, PDO::PARAM_STR);

    // Execute and check for success
    if (!$stmt->execute()) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception("Database insertion failed: " . $errorInfo[2]);
    }
    // Redirect or show success message
    header("Location: home.php");
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "An error occurred. Please try again.";
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
        <div>
            <label for="payment_method">Select Payment Method:</label>
            <input type="text" name="payment_method" placeholder="Gcash">
        </div>
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" placeholder="Your name">
        </div>
        <div>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" placeholder="Enter amount" required>
        </div>
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
        function confirmPayment(event) {
            event.preventDefault();
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
