<?php
require_once '../model/server.php';
$connector = new Connector();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['reservation_id'];
    $timeOut = $_POST['time_out'];
    $amount = $_POST['amount'];
    $paymentType = $_POST['payment_type'];

    try {
        $connector->getConnection()->beginTransaction();

        // Get the payment method ID first
        $methodSql = "SELECT method_id FROM pay_method WHERE payment_method = ?";
        $methodStmt = $connector->getConnection()->prepare($methodSql);
        $methodStmt->execute([$paymentType]);
        $payMethodId = $methodStmt->fetchColumn();

        if (!$payMethodId) {
            throw new Exception('Invalid payment method');
        }

        // First check if time record exists
        $checkSql = "SELECT * FROM time_tb WHERE time_reservation_id = ?";
        $checkStmt = $connector->getConnection()->prepare($checkSql);
        $checkStmt->execute([$reservationId]);
        
        if ($checkStmt->rowCount() > 0) {
            $timeoutSql = "UPDATE time_tb SET time_out = ? WHERE time_reservation_id = ?";
            $timeoutStmt = $connector->getConnection()->prepare($timeoutSql);
            $timeoutStmt->execute([$timeOut, $reservationId]);
        } else {
            $timeoutSql = "INSERT INTO time_tb (time_reservation_id, time_out) VALUES (?, ?)";
            $timeoutStmt = $connector->getConnection()->prepare($timeoutSql);
            $timeoutStmt->execute([$reservationId, $timeOut]);
        }

        // Insert payment record with payment method ID
        $paymentSql = "INSERT INTO payments (pay_reservation_id, amount, pay_method_id, date_of_payment, status) 
                       VALUES (?, ?, ?, NOW(), 'paid')";
        $paymentStmt = $connector->getConnection()->prepare($paymentSql);
        $paymentStmt->execute([$reservationId, $amount, $payMethodId]);

        // Update reservation status
        $statusSql = "UPDATE reservations SET status = 'checked out' WHERE reservation_id = ?";
        $statusStmt = $connector->getConnection()->prepare($statusSql);
        $statusStmt->execute([$reservationId]);

        $connector->getConnection()->commit();
        echo json_encode(['success' => true, 'message' => 'Check-out processed successfully']);
    } catch (Exception $e) {
        $connector->getConnection()->rollBack();
        error_log("Checkout Error: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }
    exit;
}