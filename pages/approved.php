<?php
require_once '../model/Booking_Model.php';
require_once '../model/server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? null;
    $action = $_POST['action'] ?? null;
    
    if (!$booking_id || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    $model = new Booking_Model();
    $status = $action === 'check_in' ? 'Checked In' : 'Checked Out';
    
    try {
        $result = $model->updateBookingStatus($booking_id, $status);
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}


?>